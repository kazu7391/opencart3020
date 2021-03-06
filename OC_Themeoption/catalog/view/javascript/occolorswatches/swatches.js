var swatches = {
    'changeOption' : function (element) {
        var product_option = element.closest('.ul-swatches-colors').data('product-option');
        var product_option_value = element.data('product-option-value-id');
        var product_option_name = element.attr('title');
        var product_option_type = element.closest('.ul-swatches-colors').data('type');
        var use_zoom = $('#check-use-zoom').val();

        if(element.closest('.swatches-options').hasClass('checked')) {
            element.closest('.swatches-options').removeClass('checked');
            element.closest('.ul-swatches-colors').find('.swatches-info').html('');

            if(product_option_type == 'select') {
                $('#input-option' + product_option).val(0).trigger('change');
            }

            if(product_option_type == 'radio') {
                $('#input-option' + product_option).find('.radio-option-value').removeAttr('checked');
            }

            if(use_zoom == '1') {
                $('#product-image-default').find('img').trigger('click');
            } else {
                var thumb = $('#product-image-default').data('thumb');
                var popup = $('#product-image-default').data('popup');
                $('#swatches-image-container .swatches-image').attr('href', popup).find('img').attr('src', thumb);
            }
        } else {
            element.closest('.ul-swatches-colors').find('.swatches-options').removeClass('checked');
            element.closest('.swatches-options').addClass('checked');
            element.closest('.ul-swatches-colors').find('.swatches-info').html(product_option_name);

            if(product_option_type == 'select') {
                $('#input-option' + product_option).val(product_option_value).trigger('change');
            }

            if(product_option_type == 'radio') {
                $('#input-option' + product_option).find('#radio-option-value-' + product_option_value).trigger('click');
            }

            if(use_zoom == '1') {
                $('#product-image-options-' + product_option_value).find('img').trigger('click');
            } else {
                var thumb = $('#product-image-options-' + product_option_value).data('thumb');
                var popup = $('#product-image-options-' + product_option_value).data('popup');
                $('#swatches-image-container .swatches-image').attr('href', popup).find('img').attr('src', thumb);
            }
        }
    },
    
    'changeSwatchInCategory' : function (element) {
        var product_option_value_id = element.data('product-option-value-id');
        var product_id = element.data('product-id');

        if(element.closest('.swatches-options').hasClass('checked')) {
            element.closest('.swatches-options').removeClass('checked');

            $('.img-cate-' + product_id).hide();
            $('.img-default-' + product_id).show();
        } else {
            element.closest('.ul-swatches-colors').find('.swatches-options').removeClass('checked');
            element.closest('.swatches-options').addClass('checked');

            $('.img-cate-' + product_id).hide();
            if($('.img-swatch-' + product_id + '-' + product_option_value_id).length) {
                $('.img-swatch-' + product_id + '-' + product_option_value_id).show();
            } else {
                $('.img-default-' + product_id).show();
            }
        }
    }
}