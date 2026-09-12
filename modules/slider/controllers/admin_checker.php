<?php
namespace HB\Modules\Slider\Controllers;
use HB\HiddenCMS\Loadables\Controllers\Module_Checker;
class Admin_Checker extends Module_Checker
{
    public function index() { return []; }
    private function authorize() { if (!$this->is_authorized('manage_sliders')) { $this->error->unauthorized(); } }
    public function add() { $this->authorize(); return []; }
    public function _edit($id) { $this->authorize(); return ($row = $this->model()->get($id)) ? [$row] : NULL; }
    public function _slides($id) { return $this->_edit($id); }
    public function _sort($id)
    {
        $this->ajax();
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !is_string(post('token')) || !hash_equals($this->form()->token('slider-sort'), post('token'))) { $this->error->unauthorized(); }
        return $this->_edit($id);
    }
    public function _preview($id) { return $this->_edit($id); }
    public function delete($id) { $this->ajax(); return $this->_edit($id); }
    public function _slide_add($id) { return $this->_edit($id); }
    public function _slide_edit($id) { $this->authorize(); return ($row = $this->model()->slide($id)) ? [$row] : NULL; }
    public function _slide_delete($id) { $this->ajax(); return $this->_slide_edit($id); }
}
