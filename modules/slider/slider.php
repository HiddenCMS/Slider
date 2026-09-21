<?php
namespace HB\Modules\Slider;
use HB\HiddenCMS\Addons\Module;
class Slider extends Module
{
    protected function __info()
    {
        return ['title' => $this->lang('Sliders'), 'description' => $this->lang('Slideshows for website widgets.'), 'icon' => 'far fa-images',
            'author' => 'HiddenCMS', 'license' => 'GPLv3', 'version' => '0.2.1', 'admin' => TRUE, 'front' => FALSE,
            'routes' => ['admin/slides/add/{id}' => '_slide_add', 'admin/slides/edit/{id}' => '_slide_edit',
                'admin/slides/delete/{id}' => '_slide_delete', 'admin/sort/{id}' => '_sort', 'admin/delete/{id}' => 'delete',
                'admin/edit/{id}' => '_edit', 'admin/preview/{id}' => '_preview', 'admin/slides/{id}' => '_slides', 'admin/add' => 'add', 'admin' => 'index']];
    }
    public function permissions()
    {
        return ['default' => ['access' => [['title' => 'Sliders', 'icon' => 'far fa-images', 'access' => [
            'manage_sliders' => ['title' => (string)$this->lang('Manage sliders and slides'), 'icon' => 'fas fa-edit', 'admin' => TRUE]
        ]]]]];
    }
}
