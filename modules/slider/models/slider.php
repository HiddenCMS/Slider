<?php
namespace HB\Modules\Slider\Models;
use HB\HiddenCMS\Loadables\Model;
use HiddenCMS\Slider\Settings;
use InvalidArgumentException;
class Slider extends Model
{
    public function all() { return $this->db->from('slider_sets')->order_by('title')->get(); }
    public function listing()
    {
        $sliders = $this->all();
        $slides = $this->db->select('s.*', 'f.path AS image_path')->from('slider_slides s')->join('file f', 'f.id = s.image_id', 'LEFT')->order_by('s.position', 's.slide_id')->get();
        $groups = [];
        foreach ($slides as $slide) { $groups[$slide['slider_id']][] = $slide; }
        foreach ($sliders as &$slider) { $slider['slides'] = $groups[$slider['slider_id']] ?? []; }
        return $sliders;
    }
    public function sort_slides($slider_id, $order)
    {
        if (!$this->get($slider_id) || !is_array($order)) { throw new InvalidArgumentException('Ordre invalide.'); }
        $ids = [];
        foreach ($order as $id) {
            if (filter_var($id, FILTER_VALIDATE_INT) === FALSE || (int)$id < 1) { throw new InvalidArgumentException('Ordre invalide.'); }
            $ids[] = (int)$id;
        }
        $existing = array_map('intval', array_column($this->slides($slider_id), 'slide_id'));
        $sorted = $ids; sort($sorted); sort($existing);
        if ($sorted !== $existing || count(array_unique($ids)) !== count($ids)) { throw new InvalidArgumentException('La liste a change. Rechargez la page avant de la reordonner.'); }
        if (!$ids) { return; }
        // One statement keeps all positions atomic, including on a failed update.
        $cases = [];
        foreach ($ids as $position => $id) { $cases[] = 'WHEN '.$id.' THEN '.($position + 1); }
        $this->db->execute_checked('UPDATE slider_slides SET position = CASE slide_id '.implode(' ', $cases).' END WHERE slider_id = '.(int)$slider_id.' AND slide_id IN ('.implode(',', $ids).')');
    }
    public function get($id) { return $this->db->from('slider_sets')->where('slider_id', (int)$id)->row(); }
    public function slide($id) { return $this->db->from('slider_slides')->where('slide_id', (int)$id)->row(); }
    public function slides($id, $public = FALSE)
    {
        $this->db->select('s.*', 'f.path AS image_path')->from('slider_slides s')->join('file f', 'f.id = s.image_id', 'LEFT')->where('s.slider_id', (int)$id)->order_by('s.position', 's.slide_id');
        if ($public) { $this->db->where('s.published', 1); }
        return $this->db->get();
    }
    private function text($data, $key, $max, $required = FALSE)
    {
        $value = trim(utf8_html_entity_decode($data[$key] ?? '', ENT_QUOTES));
        if (($required && $value === '') || mb_strlen($value) > $max) { throw new InvalidArgumentException('Champ '.$key.' invalide (maximum '.$max.' caracteres).'); }
        return $value;
    }
    public function save(array $data, $id = 0)
    {
        $values = ['title' => $this->text($data, 'title', 150, TRUE), 'effect' => $data['effect'] ?? 'slide',
            'delay_ms' => Settings::number($data['delay_ms'] ?? 5000, 1000, 60000, 'Pause'),
            'speed_ms' => Settings::number($data['speed_ms'] ?? 600, 100, 3000, 'Transition'),
            'height' => Settings::number($data['height'] ?? 480, 160, 1000, 'Hauteur'),
            'autoplay' => !empty($data['autoplay']) ? 1 : 0, 'published' => !empty($data['published']) ? 1 : 0];
        if (!isset(Settings::effects()[$values['effect']])) { throw new InvalidArgumentException('Animation invalide.'); }
        if ($id) { if (!$this->get($id)) { throw new InvalidArgumentException('Slider introuvable.'); } $this->db->where('slider_id', (int)$id)->update('slider_sets', $values); return (int)$id; }
        return $this->db->insert('slider_sets', $values);
    }
    public function save_slide(array $data, $slider_id, $id = 0)
    {
        if (!$this->get($slider_id)) { throw new InvalidArgumentException('Slider introuvable.'); }
        $image_id = (int)($data['image_id'] ?? 0);
        $file = $this->db->from('file')->where('id', $image_id)->row();
        if (!$file || !in_array(strtolower(pathinfo($file['path'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'], TRUE)) { throw new InvalidArgumentException('Choisissez une image dans la mediatheque.'); }
        $values = ['slider_id' => (int)$slider_id, 'image_id' => $image_id,
            'title' => $this->text($data, 'title', 150), 'description' => $this->text($data, 'description', 2000),
            'alt' => $this->text($data, 'alt', 255), 'button_label' => $this->text($data, 'button_label', 100),
            'button_url' => Settings::url(utf8_html_entity_decode($data['button_url'] ?? '', ENT_QUOTES)),
            'position' => Settings::number($data['position'] ?? 0, 0, 100000, 'Ordre'), 'published' => !empty($data['published']) ? 1 : 0];
        if (($values['button_label'] === '') !== ($values['button_url'] === '')) { throw new InvalidArgumentException('Renseignez le texte et le lien du bouton, ou laissez les deux vides.'); }
        if ($id) {
            $old = $this->slide($id);
            if (!$old || (int)$old['slider_id'] !== (int)$slider_id) { throw new InvalidArgumentException('Slide introuvable.'); }
            $this->db->where('slide_id', (int)$id)->update('slider_slides', $values); return (int)$id;
        }
        return $this->db->insert('slider_slides', $values);
    }
    public function delete($id) { $this->db->where('slider_id', (int)$id)->delete('slider_sets'); }
    public function delete_slide($id) { $this->db->where('slide_id', (int)$id)->delete('slider_slides'); }
}
