<?php
// Render template expressions before exercising the client in a DOM fixture.
$renderer = new class {
    public function lang($source) { return $source; }
    public function render() {
        ob_start();
        include dirname(__DIR__).'/widgets/slider/js/slider.js';
        return ob_get_clean();
    }
};
echo $renderer->render();
