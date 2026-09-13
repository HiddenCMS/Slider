<?php
$root=$argv[1] ?? '';$destination=$argv[2] ?? '';
if (!$root || !$destination) throw new RuntimeException('Provide site and temporary HTML output paths.');
chdir($root);define('HIDDENCMS_CLI',TRUE);require 'index.php';
$model=HB()->module('slider')->model();$db=HB()->db;$id=(int)($argv[3] ?? 1);
$row=$model->get($id);if (!$row) throw new RuntimeException('Test slider required.');
$html='<!doctype html><html lang="fr"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
$html.='<link rel="stylesheet" href="/fr/vendor/hiddencms/slider/widgets/slider/css/swiper.css"><link rel="stylesheet" href="/fr/vendor/hiddencms/slider/widgets/slider/css/slider.css"><style>body{margin:0;font-family:Arial,sans-serif}.fixture{max-width:960px;margin:40px auto;padding:0 20px}button{font:inherit}</style><main class="fixture">';
$db->begin_transaction();
try {
    foreach (\HiddenCMS\Slider\Settings::effects() as $effect=>$title) {
        $settings=$row;$settings['effect']=$effect;$model->save($settings,$id);
        $html.='<h2>'.utf8_htmlentities($title).'</h2>'.HB()->widget('slider')->output(['slider_id'=>$id,'full_width'=>TRUE]);
    }
} finally {$db->rollback();}
$html.='</main><script src="/fr/vendor/hiddencms/slider/widgets/slider/js/swiper.js"></script><script src="/fr/vendor/hiddencms/slider/widgets/slider/js/slider.js"></script></html>';
foreach (['css/slider.css','js/slider.js'] as $asset) {
    $html=str_replace('/widgets/slider/'.$asset.'"','/widgets/slider/'.$asset.'?v='.filemtime(dirname(__DIR__).'/widgets/slider/'.$asset).'"',$html);
}
file_put_contents($destination,$html);echo $destination."\n";
