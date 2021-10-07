jQuery(document).ready( function($) {

    // product update eventlistener
    $( "input" ).keypress(function(e) {
        if(e.which == 13){
            var price = $(this).val();

            if (!$.isNumeric(price) ) {
                alert('Non usare le lettere!')
            } else if ( !price ) {
                alert('Inserire il prezzo!')
            } else if ($(this).attr("placeholder") == price) {
                alert('Il prezzo non può essere uguale a prima!')
            } else {
                ajaxUpdateProductVariationPrice($(this).attr("name"),$(this).attr("id"),price);
            }
        }
    });

    // product update ajax
    function ajaxUpdateProductVariationPrice(name,id,price){
        var data = {
            'action': 'oc_ajax_variation_price_update',
            'variationid':  id,
            'variationprice':  price,
        };
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type : 'post',
            data: data,
            beforeSend: function(){
                jQuery(".input-"+name+"-"+id).html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success : function( response ) {
                objs = JSON.parse(response);
                if (objs == true) {
                    jQuery(".input-"+name+"-"+id).html('<i class="fas fa-check-circle"></i>');
                    jQuery("input#"+id).attr('placeholder',price);
                }
            }
        });
    }

    // product elimination eventlistner
    $( ".dp-delete-icon" ).click(function(e) { 
        var id = $(this).attr("id");
        var name = $(this).attr("name");
        var catname = $(this).attr("catname");
        var confirm = window.confirm('Sei sicuro di voler cancellare questo prodotto?'); 
        if (confirm) {
            console.log('true');
            ajaxDeleteProductVariationPrice(name,id,catname)
        } else {
            console.log('false');
        }
    });
    // product elimination ajax
    function ajaxDeleteProductVariationPrice(name,id,catname){
        var data = {
            'action': 'oc_ajax_variation_delete',
            'variationid':  id,
        };
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type : 'post',
            data: data,
            beforeSend: function(){
                jQuery(".input-"+name+"-"+id).html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success : function( response ) {
                catnamer = catname.replace(' ','');
                objs = JSON.parse(response);
                var prductoid = objs['productid']
                if (objs['response'] == 'true') {
                    jQuery("#dp_variation_"+name+" #dp_variation_category_"+catnamer+"").html("<div class='.input-"+name+"-"+id+"'><i class='fas fa-times-circle'></i></div><input type='text' placeholder='-' productid="+prductoid+">")
                }
            }
        });
    }

    $( "#tabs" ).tabs({
        activate: function( event, ui ) {
            ui.newPanel.find('.webmapp_post_image').each(function(i,e){
                force_aspect_ratio($(e));
            } );
        }
    });
    $( "#tab-stagioni" ).tabs({
        activate: function( event, ui ) {
            ui.newPanel.find('.webmapp_post_image').each(function(i,e){
                force_aspect_ratio($(e));
            } );
        }
    });
} );

jQuery(function(){
    window.et_pb_smooth_scroll = () => {};
});