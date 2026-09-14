<?php
namespace HiddenCMS\Slider;
use InvalidArgumentException;
class Settings
{
    private static function text($source, ...$args)
    {
        return function_exists('HB') ? (string)\HB()->lang($source, ...$args) : sprintf($source, ...$args);
    }
    public static function effects()
    {
        return [
            'slide' => self::text('Slide'), 'fade' => self::text('Fade'), 'cube' => 'Cube', 'flip' => self::text('Flip'),
            'coverflow' => 'Coverflow', 'cards' => self::text('Cards'), 'creative' => self::text('Creative'),
            'zoom' => 'Zoom', 'vertical' => self::text('Vertical slide'), 'fade-move' => self::text('Fade with movement')
        ];
    }
    public static function number($value, $min, $max, $label)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === FALSE || $value < $min || $value > $max) {
            throw new InvalidArgumentException(self::text('%s must be between %d and %d.', $label, $min, $max));
        }
        return (int)$value;
    }
    public static function url($value)
    {
        $value = trim($value);
        if ($value === '') { return ''; }
        if (strlen($value) > 2048 || preg_match('/[\x00-\x20\x7f\\\\]/', $value)) { throw new InvalidArgumentException(self::text('Invalid button link.')); }
        if ($value[0] === '/' && substr($value, 0, 2) !== '//') { return $value; }
        if (filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], TRUE)) { return $value; }
        throw new InvalidArgumentException(self::text('Use an https:// URL or a /page path for the button.'));
    }
}
