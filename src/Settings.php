<?php
namespace HiddenCMS\Slider;
use InvalidArgumentException;
class Settings
{
    public static function effects()
    {
        return [
            'slide' => 'Glissement', 'fade' => 'Fondu', 'cube' => 'Cube', 'flip' => 'Retournement',
            'coverflow' => 'Coverflow', 'cards' => 'Cartes', 'creative' => 'Créatif',
            'zoom' => 'Zoom', 'vertical' => 'Glissement vertical', 'fade-move' => 'Fondu avec déplacement'
        ];
    }
    public static function number($value, $min, $max, $label)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === FALSE || $value < $min || $value > $max) {
            throw new InvalidArgumentException($label.' : entre '.$min.' et '.$max.'.');
        }
        return (int)$value;
    }
    public static function url($value)
    {
        $value = trim($value);
        if ($value === '') { return ''; }
        if (strlen($value) > 2048 || preg_match('/[\x00-\x20\x7f\\\\]/', $value)) { throw new InvalidArgumentException('Lien du bouton invalide.'); }
        if ($value[0] === '/' && substr($value, 0, 2) !== '//') { return $value; }
        if (filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], TRUE)) { return $value; }
        throw new InvalidArgumentException('Utiliser une URL https:// ou un chemin /page pour le bouton.');
    }
}
