<div class="slider-admin-tools"><h2>Mes sliders <span class="slider-count"><?php echo count($sliders) ?></span></h2><?php if ($this->access('slider', 'manage_sliders')) echo $this->button_create('admin/slider/add', 'Creer un slider'); ?></div>
<div class="slider-set-list">
<?php foreach ($sliders as $row): $images = array_values(array_filter($row['slides'], function($slide){ return !empty($slide['image_path']); })); ?>
<article class="slider-set-row">
<a class="slider-set-preview" href="<?php echo url('admin/slider/slides/'.$row['slider_id']) ?>" aria-label="<?php echo utf8_htmlentities($row['title']) ?>">
<?php if ($images): ?><img src="<?php echo utf8_htmlentities(url($images[0]['image_path'])) ?>" alt="" loading="lazy" /><?php else: ?><?php echo icon('far fa-images') ?><?php endif ?>
</a>
<div class="slider-set-info"><h3><a href="<?php echo url('admin/slider/slides/'.$row['slider_id']) ?>"><?php echo utf8_htmlentities($row['title']) ?></a></h3>
<div class="slider-set-meta"><span><?php echo count($row['slides']) ?> slide<?php echo count($row['slides']) > 1 ? 's' : '' ?></span><span><?php echo utf8_htmlentities(\HiddenCMS\Slider\Settings::effects()[$row['effect']]) ?></span><span><?php echo (int)$row['height'] ?> px</span><span><?php echo $row['autoplay'] ? 'Automatique' : 'Manuel' ?></span></div>
<div class="slider-filmstrip"><?php foreach (array_slice($images, 0, 5) as $image): ?><img src="<?php echo utf8_htmlentities(url($image['image_path'])) ?>" alt="" loading="lazy" /><?php endforeach ?></div>
</div>
<span class="ui tiny <?php echo $row['published'] ? 'green' : '' ?> label"><?php echo $row['published'] ? 'Actif' : 'Inactif' ?></span>
<div class="slider-admin-actions"><?php if ($this->access('slider', 'manage_sliders')): ?><?php echo $this->button('Slides')->icon('fas fa-layer-group')->color('secondary')->url('admin/slider/slides/'.$row['slider_id']) ?><?php echo $this->button_update('admin/slider/edit/'.$row['slider_id']) ?><?php echo $this->button_delete('admin/slider/delete/'.$row['slider_id']) ?><?php endif ?></div>
</article><?php endforeach ?>
<?php if (!$sliders): ?><div class="slider-empty"><?php echo icon('far fa-images') ?><p>Aucun slider</p></div><?php endif ?>
</div>
