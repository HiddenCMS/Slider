<?php
namespace HB\Widgets\Slider;
use HB\HiddenCMS\Addons\Widget;
class Slider extends Widget
{
    protected function __info()
    {
        return ['title' => $this->lang('Slider'), 'description' => $this->lang('Display a slideshow from the Sliders module.'), 'icon' => 'far fa-images', 'author' => 'HiddenCMS', 'license' => 'GPLv3', 'version' => '0.2.2'];
    }
}
