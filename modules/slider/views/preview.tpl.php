<div class="slider-admin-tools"><h2><?php echo utf8_htmlentities($slider['title']) ?></h2><?php echo $this->button((string)$this->lang('Back to slides'))->icon('fas fa-arrow-left')->color('secondary')->url('admin/slider/slides/'.$slider['slider_id']) ?></div>
<?php echo $preview ?>
