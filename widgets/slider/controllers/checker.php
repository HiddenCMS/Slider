<?php
namespace HB\Widgets\Slider\Controllers;
use HB\HiddenCMS\Loadables\Controller;
class Checker extends Controller
{
    public function index($settings = [])
    {
        return ['slider_id' => max(0, (int)($settings['slider_id'] ?? 0)), 'full_width' => !empty($settings['full_width']), 'content_only' => TRUE];
    }
}
