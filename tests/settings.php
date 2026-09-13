<?php
require dirname(__DIR__).'/src/Settings.php';
use HiddenCMS\Slider\Settings;
function check($value, $label) { if (!$value) throw new RuntimeException($label); echo "PASS $label\n"; }
check(array_keys(Settings::effects()) === ['slide','fade','cube','flip','coverflow','cards','creative','zoom','vertical','fade-move'], 'Ten supported effects');
check(Settings::url('/fr/contact') === '/fr/contact', 'Local link accepted');
check(Settings::url('https://example.com/?a=1&b=2') !== '', 'HTTPS link accepted');
foreach (['javascript:alert(1)', '//example.com', '/\\example.com', 'data:text/html,test', "https://example.com/\n"] as $value) {
    if (str_ends_with($value, "\n")) { $value = "https://example.com/\ninjected"; }
    try { Settings::url($value); check(FALSE, 'Unsafe link rejected'); } catch (InvalidArgumentException $error) { check(TRUE, 'Unsafe link rejected'); }
}
check(Settings::number('480', 160, 1000, 'Height') === 480, 'Valid height');
foreach (['abc', 0, 1001] as $value) { try { Settings::number($value,160,1000,'Height'); check(FALSE,'Invalid height'); } catch (InvalidArgumentException $error) { check(TRUE,'Invalid height'); } }
