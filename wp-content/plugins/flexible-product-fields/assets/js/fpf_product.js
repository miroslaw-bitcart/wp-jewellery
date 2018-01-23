jQuery(document).ready(function() {
    function fpf_price_options( field ) {
        var qty = jQuery('input.qty').val(),
            wrap = jQuery('#fpf_totals');
        wrap.empty();
        var adjust_price = 0;
        jQuery.each(fpf_fields,function(i,val){
            var price_display = false;
            var price_value = 0;
            if ( !val.has_options && val.has_price && val.price_value != 0 ) {
                if ( val.type == 'text' || val.type == 'textarea' ) {
                    var field_val = jQuery('#' + val.id).val();
                    if (field_val != '') {
                        price_value = val.price_value;
                        price_display = val.price_display;
                    }
                }
                if ( val.type == 'checkbox' ) {
                    if (jQuery('#' + val.id).is(':checked')) {
                        price_value = val.price_value;
                        price_display = val.price_display;
                    }
                }
            }
            if ( val.has_options ) {
                jQuery.each(val.options,function(i,val_option) {
                    if ( val.type == 'select' ) {
                        var field_val = jQuery('#' + val.id).val();
                        if (field_val == val_option.value) {
                            price_value = val_option.price_value;
                            price_display = val_option.price_display;
                        }
                    }
                    if ( val.type == 'radio' ) {
                        var field_val = jQuery('input[name='+val.id+']:checked').val()
                        if (field_val == val_option.value) {
                            price_value = val_option.price_value;
                            price_display = val_option.price_display;
                        }
                    }
                });
            }
            if ( price_value > 0 ) {
                price_value = price_value * qty;
                price_display = accounting.formatMoney( price_value, {
                    symbol 		: fpf_product.currency_format_symbol,
                    decimal 	: fpf_product.currency_format_decimal_sep,
                    thousand	: fpf_product.currency_format_thousand_sep,
                    precision 	: fpf_product.currency_format_num_decimals,
                    format		: fpf_product.currency_format
                } );
                wrap.append('<dt>' + val.title + ':</dt>');
                wrap.append('<dd>' + price_display + '</dd>');
                adjust_price += price_value;

            }
        });
        if ( adjust_price != 0 ) {
            var total_price = ( qty * fpf_product_price ) + adjust_price;
            total_price = accounting.formatMoney( total_price, {
                symbol 		: fpf_product.currency_format_symbol,
                decimal 	: fpf_product.currency_format_decimal_sep,
                thousand	: fpf_product.currency_format_thousand_sep,
                precision 	: fpf_product.currency_format_num_decimals,
                format		: fpf_product.currency_format
            } );
            wrap.append('<dt>' + fpf_product.total + ':</dt>');
            wrap.append('<dd>' + total_price + '</dd>');
        }
    }
    fpf_price_options();
    jQuery(document).on("change",".fpf-input-field,input.qty",function() {
       fpf_price_options(this);
    });
    jQuery(document).on("keyup",".fpf-input-field,input.qty,.variations select",function() {
        fpf_price_options(this);
    });

    jQuery(document).on( 'found_variation', 'form.cart', function( event, variation ) {
        console.log(variation);
        fpf_product_price = variation.display_price;
        fpf_price_options();
    })
})
