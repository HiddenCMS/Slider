const assert = require('node:assert/strict');
const {execFileSync} = require('node:child_process');
const vm = require('node:vm');
const source = execFileSync('php', [require('node:path').join(__dirname, 'script.php')], {encoding:'utf8'});
const effects = ['slide', 'fade', 'cube', 'flip', 'coverflow', 'cards', 'creative', 'zoom', 'vertical', 'fade-move'];

function options(effect, reduced = false) {
    let result;
    const root = {
        dataset: {effect, speed: '600', delay: '5000', autoplay: '1'},
        querySelectorAll: () => [{}, {}],
        querySelector: () => null,
        addEventListener() {},
        classList: {contains: () => false}
    };
    const window = {matchMedia: () => ({matches: reduced}), addEventListener() {}};
    function Swiper(element, config) {
        result = config;
        this.slides = [];
    }
    window.Swiper = Swiper;
    vm.runInNewContext(source, {
        window, Swiper,
        document: {readyState: 'complete', querySelectorAll: () => [root]}
    });
    return result;
}

for (const effect of effects) {
    const config = options(effect);
    assert.equal(config.effect, effect === 'vertical' ? 'slide' : ['zoom', 'fade-move'].includes(effect) ? 'creative' : effect);
    assert.equal(config.direction, effect === 'vertical' ? 'vertical' : 'horizontal');
    assert.equal(config.speed, 600);
    const reduced = options(effect, true);
    assert.equal(reduced.effect, 'slide');
    assert.equal(reduced.direction, 'horizontal');
    assert.equal(reduced.speed, 0);
    assert.equal(reduced.autoplay, false);
    console.log('PASS Effect and reduced motion:', effect);
}
assert.equal(options('unknown').effect, 'slide');
assert.equal(options('zoom').creativeEffect.prev.scale, 1.15);
assert.equal(options('fade-move').creativeEffect.next.translate[0], '6%');
console.log('PASS Fallback and custom transitions');
