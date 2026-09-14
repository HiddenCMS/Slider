<?php
namespace HB\Modules\Slider\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module as Controller_Module;
use HiddenCMS\Slider\Settings;
class Admin extends Controller_Module
{
    public function index() { $this->css('admin'); return $this->view('admin', ['sliders' => $this->model()->listing()]); }
    public function add() { return $this->slider_form(); }
    public function _edit($row) { return $this->slider_form($row); }
    public function _slides($row) { $this->css('admin')->js('jquery-ui.min')->js('admin'); return $this->view('slides', ['slider' => $row, 'slides' => $this->model()->slides($row['slider_id']), 'sort_token' => $this->form()->token('slider-sort')]); }
    public function _sort($row)
    {
        try { $this->model()->sort_slides($row['slider_id'], post('order')); $this->output->json(['success' => TRUE]); }
        catch (\InvalidArgumentException $error) { $this->output->json(['success' => FALSE, 'message' => $error->getMessage()]); }
        catch (\RuntimeException $error) { error_log('Slider ordering failed: '.$error->getMessage()); $this->output->json(['success' => FALSE, 'message' => (string)$this->lang('Unable to save the order. Please try again.')]); }
    }
    public function _preview($row)
    {
        $this->css('admin');
        return $this->view('preview', ['slider' => $row, 'preview' => $this->widget('slider')->output(['slider_id' => $row['slider_id']])]);
    }
    public function _slide_add($row) { return $this->slide_form($row['slider_id']); }
    public function _slide_edit($row) { return $this->slide_form($row['slider_id'], $row); }
    public function delete($row) { return $this->deletion($row['slider_id'], FALSE); }
    public function _slide_delete($row) { return $this->deletion($row['slide_id'], TRUE); }
    private function deletion($id, $slide)
    {
        $this->form()->confirm_deletion((string)$this->lang('Deletion'), $slide ? (string)$this->lang('Delete this slide? The file will remain in the media library.') : (string)$this->lang('Delete this slider and all its slides? Images will remain in the media library.'));
        if ($this->form()->is_valid()) { $this->model()->{$slide ? 'delete_slide' : 'delete'}($id); return 'OK'; }
        return $this->form()->display();
    }
    private function slider_form($row = [])
    {
        $id = $row['slider_id'] ?? 0;
        return $this->form2()
            ->rule($this->form_text('title')->title((string)$this->lang('Slider name'))->value($row['title'] ?? '')->required())
            ->rule($this->form_select('effect')->title('Animation')->data(Settings::effects())->value($row['effect'] ?? 'slide')->search(0))
            ->rule($this->form_number('delay_ms')->title((string)$this->lang('Pause between slides (ms)'))->value($row['delay_ms'] ?? 5000)->required())
            ->rule($this->form_number('speed_ms')->title((string)$this->lang('Transition duration (ms)'))->value($row['speed_ms'] ?? 600)->required())
            ->rule($this->form_number('height')->title((string)$this->lang('Large-screen height (px)'))->value($row['height'] ?? 480)->required())
            ->rule($this->form_checkbox('autoplay')->size('hb-switch-field')->data(['1' => (string)$this->lang('Autoplay')])->value(!isset($row['autoplay']) || $row['autoplay'] ? ['1'] : []))
            ->rule($this->form_checkbox('published')->size('hb-switch-field')->data(['1' => (string)$this->lang('Active slider')])->value(!isset($row['published']) || $row['published'] ? ['1'] : []))
            ->submit($id ? (string)$this->lang('Save') : (string)$this->lang('Create'))->back('admin/slider')->success(function($data, $form) use ($id){
                try { $saved = $this->model()->save($data, $id); notify((string)$this->lang('Slider saved')); redirect('admin/slider/slides/'.$saved); }
                catch (\InvalidArgumentException $error) { $form->error(utf8_htmlentities($error->getMessage())); }
            })->panel();
    }
    private function slide_form($slider_id, $row = [])
    {
        $this->css('admin')->css('file_picker')->js('file_picker')->js('admin');
        $id = $row['slide_id'] ?? 0;
        $image = !empty($row['image_id']) ? $this->db->from('file')->where('id', (int)$row['image_id'])->row() : NULL;
        return $this->form2()
            ->rule($this->form_text('image_id')->value($row['image_id'] ?? '')->size('slider-image-value'))
            ->info($this->view('image_field', ['image' => $image]))
            ->rule($this->form_text('alt')->title('Alternative text')->value($row['alt'] ?? ''))
            ->rule($this->form_text('title')->title((string)$this->lang('Title (optional)'))->value($row['title'] ?? ''))
            ->rule($this->form_textarea('description')->title((string)$this->lang('Description (optional)'))->value($row['description'] ?? ''))
            ->rule($this->form_text('button_label')->title((string)$this->lang('Button text'))->value($row['button_label'] ?? ''))
            ->rule($this->form_text('button_url')->title('Button link')->placeholder((string)$this->lang('https:// or /page'))->value($row['button_url'] ?? ''))
            ->rule($this->form_number('position')->title((string)$this->lang('Order'))->value($row['position'] ?? count($this->model()->slides($slider_id)) + 1)->required())
            ->rule($this->form_checkbox('published')->size('hb-switch-field')->data(['1' => (string)$this->lang('Active slide')])->value(!isset($row['published']) || $row['published'] ? ['1'] : []))
            ->submit($id ? (string)$this->lang('Save') : (string)$this->lang('Add'))->back('admin/slider/slides/'.$slider_id)->success(function($data, $form) use ($id, $slider_id){
                try { $data['image_id'] = post('image_id'); $this->model()->save_slide($data, $slider_id, $id); notify((string)$this->lang('Slide saved')); redirect('admin/slider/slides/'.$slider_id); }
                catch (\InvalidArgumentException $error) { $form->error(utf8_htmlentities($error->getMessage())); }
            })->panel();
    }
}
