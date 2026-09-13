(function(){
    'use strict';
    function init(){
        document.querySelectorAll('.hc-slider').forEach(function(root){
            if (root.dataset.initialized || !window.Swiper) return;
            root.dataset.initialized = '1';
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var count = root.querySelectorAll('.swiper-slide').length;
            var play = root.querySelector('.hc-slider-play');
            var effects = ['slide','fade','cube','flip','coverflow','cards','creative','zoom','vertical','fade-move'];
            var effect = !reduced && effects.includes(root.dataset.effect) ? root.dataset.effect : 'slide';
            var creative = {
                perspective:true,
                prev:{translate:['-20%',0,-180],rotate:[0,0,-6],opacity:0,scale:0.9},
                next:{translate:['100%',0,0],rotate:[0,0,6],opacity:0,scale:0.9}
            };
            if (effect === 'zoom') creative = {
                perspective:false,
                prev:{translate:[0,0,0],opacity:0,scale:1.15},
                next:{translate:[0,0,0],opacity:0,scale:0.85}
            };
            if (effect === 'fade-move') creative = {
                perspective:false,
                prev:{translate:['-6%',0,0],opacity:0},
                next:{translate:['6%',0,0],opacity:0}
            };
            var swiper = new Swiper(root.querySelector('.swiper'), {
                effect: effect === 'vertical' ? 'slide' : (['zoom','fade-move'].includes(effect) ? 'creative' : effect),
                direction: effect === 'vertical' ? 'vertical' : 'horizontal',
                coverflowEffect:{rotate:35,stretch:0,depth:150,modifier:1,slideShadows:false},
                cardsEffect:{perSlideOffset:6,perSlideRotate:3,slideShadows:false},
                creativeEffect:creative,
                fadeEffect: {crossFade:true}, speed: reduced ? 0 : Number(root.dataset.speed), rewind:true,
                autoplay: count > 1 && !reduced && root.dataset.autoplay === '1' ? {delay:Number(root.dataset.delay),disableOnInteraction:false,pauseOnMouseEnter:true} : false,
                navigation:{prevEl:root.querySelector('.hc-slider-prev'),nextEl:root.querySelector('.hc-slider-next')},
                pagination:{el:root.querySelector('.hc-slider-pagination'),clickable:true,bulletElement:'button'},
                a11y:{prevSlideMessage:'Slide precedente',nextSlideMessage:'Slide suivante',paginationBulletMessage:'Afficher la slide {{index}}'},
                on:{init:sync,autoplayStart:sync,autoplayStop:sync,slideChange:visibleSlide}
            });
            function sync(instance){
                if (!play) return;
                var running = instance.autoplay && instance.autoplay.running;
                play.setAttribute('aria-pressed',running ? 'true':'false');
                play.setAttribute('aria-label',running ? 'Mettre en pause':'Lancer le defilement');
                play.title=play.getAttribute('aria-label');
                play.querySelector('i').className=running ? 'fas fa-pause':'fas fa-play';
            }
            function visibleSlide(instance){
                instance.slides.forEach(function(slide,index){
                    var active=index===instance.activeIndex;
                    slide.inert=!active;
                    slide.setAttribute('aria-hidden',active ? 'false':'true');
                });
            }
            visibleSlide(swiper);
            if (play) play.addEventListener('click',function(){
                if (swiper.autoplay.running) swiper.autoplay.stop();
                else {swiper.params.autoplay={delay:Number(root.dataset.delay),disableOnInteraction:false,pauseOnMouseEnter:true};swiper.autoplay.start();}
            });
            root.addEventListener('focusin',function(event){if(swiper.autoplay.running && (!play || !play.contains(event.target))) swiper.autoplay.stop();});
            function width(){
                if (!root.classList.contains('hc-slider-full-width')) return;
                root.style.width='';root.style.marginLeft='';
                var left=root.getBoundingClientRect().left;
                root.style.width=document.documentElement.clientWidth+'px';root.style.marginLeft=-left+'px';
            }
            width();window.addEventListener('resize',width);
        });
    }
    if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',init); else init();
    if(window.jQuery) jQuery(document).on('nf.load',init);
}());
