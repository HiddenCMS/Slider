<?php
namespace HB\Widgets\Slider\Controllers;
use HB\HiddenCMS\Loadables\Controller;
class Admin extends Controller
{
    public function index($settings = [])
    {
        $module = $this->module('slider');
        return $this->view('admin', ['settings' => $settings, 'sliders' => $module ? $module->model()->all() : []]);
    }
}
