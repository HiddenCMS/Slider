$(function(){
    var initSort = function(){
        $('[data-slider-sort]').each(function(){
            var $list = $(this), before = [], $status = $('.slider-order-status');
            if ($list.data('sort-bound')) return;
            $list.data('sort-bound', true);
            var order = function(){ return $list.children().map(function(){ return $(this).data('id'); }).get(); };
            var save = function(){
                if (JSON.stringify(before) === JSON.stringify(order())) return;
                $list.sortable('disable'); $status.text(<?php echo json_encode((string)$this->lang('Saving the order...'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>).removeClass('slider-missing');
                var restore = function(message){
                    before.forEach(function(id){ $list.append($list.children('[data-id="' + id + '"]')); });
                    $status.text(message || <?php echo json_encode((string)$this->lang('Unable to save the order. Please try again.'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>).addClass('slider-missing');
                };
                $.ajax({url:$list.data('slider-sort'),type:'POST',dataType:'json',data:{order:order(),token:$list.data('token')}})
                    .done(function(result){ if (result && result.success) $status.text(<?php echo json_encode((string)$this->lang('Order saved'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>); else restore(result && result.message); })
                    .fail(function(){ restore(); })
                    .always(function(){ $list.sortable('enable'); });
            };
            $list.sortable({axis:'y',items:'> li',handle:'.slider-drag',cancel:'input,select,textarea,a:not(.slider-drag)',tolerance:'pointer',placeholder:'slider-sort-placeholder',forcePlaceholderSize:true,
                start:function(event,ui){ before = $list.children('[data-id]').map(function(){ return $(this).data('id'); }).get(); ui.placeholder.height(ui.item.outerHeight()); },update:save});
        });
    };
    $('body').on('nf.load',initSort); initSort();
    $('[data-slider-image]').on('click',function(){
        var $field=$(this).closest('.slider-image-field'),$form=$field.closest('form');
        window.HiddenCMS.openFilePicker({accept:'image',selectedId:$form.find('[name="image_id"]').val(),onSelect:function(file){
            $form.find('[name="image_id"]').val(file.id);
            $field.find('[data-slider-image-name]').text(file.name);
            $field.find('[data-slider-image-preview]').attr('src',file.url).prop('hidden',false);
        }});
    });
});
