<?php
namespace HB\Widgets\Slider\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Widget as Controller_Widget;
class Index extends Controller_Widget
{
    public function index($settings = [])
    {
        $module = $this->module('slider');
        if (!$module || !($slider = $module->model()->get($settings['slider_id'] ?? 0)) || !$slider['published']) { return ''; }
        $slides = array_values(array_filter($module->model()->slides($slider['slider_id'], TRUE), function($slide){ return !empty($slide['image_path']); }));
        if (!$slides) { return ''; }
        $this->css('swiper')->css('slider')->js('swiper')->js('slider');
        return $this->view('index', ['slider' => $slider, 'slides' => $slides, 'settings' => $settings]);
    }
}
