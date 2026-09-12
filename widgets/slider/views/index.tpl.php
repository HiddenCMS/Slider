<section class="hc-slider<?php if (!empty($settings['full_width'])) echo ' hc-slider-full-width'; ?>" role="region" aria-roledescription="diaporama" aria-label="<?php echo utf8_htmlentities($slider['title']) ?>" style="--slider-height:<?php echo (int)$slider['height'] ?>px" data-effect="<?php echo utf8_htmlentities($slider['effect']) ?>" data-delay="<?php echo (int)$slider['delay_ms'] ?>" data-speed="<?php echo (int)$slider['speed_ms'] ?>" data-autoplay="<?php echo (int)$slider['autoplay'] ?>">
<div class="swiper"><div class="swiper-wrapper">
<?php foreach ($slides as $index => $slide): ?><div class="swiper-slide">
<img class="hc-slider-image" src="<?php echo utf8_htmlentities(url($slide['image_path'])) ?>" alt="<?php echo utf8_htmlentities($slide['alt']) ?>"<?php echo $index ? ' loading="lazy"' : ' fetchpriority="high"'; ?> />
<?php if ($slide['title'] || $slide['description'] || $slide['button_url']): ?><div class="hc-slider-overlay"><div class="hc-slider-copy">
<?php if ($slide['title']): ?><h2><?php echo utf8_htmlentities($slide['title']) ?></h2><?php endif ?>
<?php if ($slide['description']): ?><p><?php echo nl2br(utf8_htmlentities($slide['description'])) ?></p><?php endif ?>
<?php if ($slide['button_url']): ?><a class="hc-slider-link" href="<?php echo utf8_htmlentities($slide['button_url']) ?>"><?php echo utf8_htmlentities($slide['button_label']) ?></a><?php endif ?>
</div></div><?php endif ?></div><?php endforeach ?>
</div></div>
<?php if (count($slides) > 1): ?><div class="hc-slider-controls"><button type="button" class="hc-slider-prev" aria-label="Slide precedente" title="Slide precedente"><?php echo icon('fas fa-chevron-left') ?></button><button type="button" class="hc-slider-next" aria-label="Slide suivante" title="Slide suivante"><?php echo icon('fas fa-chevron-right') ?></button><button type="button" class="hc-slider-play" aria-label="Lancer le defilement" title="Lancer le defilement" aria-pressed="false"><?php echo icon('fas fa-play') ?></button></div><div class="hc-slider-pagination"></div><?php endif ?>
</section>
