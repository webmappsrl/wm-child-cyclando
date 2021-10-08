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
        var seasonname = $(this).attr("seasonname");
        var tr = $(this).closest("tr");
        var variationcells = tr.find(".dp-delete-icon");
        var deleterow = 'false';
        if (variationcells.length == 1) {
            var confirmdelrow = window.confirm('Questa riga verrà eliminata, sei sicuro di voler procedere?'); 
            if (confirmdelrow) {
                deleterow = tr.attr('id');
                ajaxDeleteProductVariationPrice(name,id,catname,seasonname,deleterow)
                
            } else {
            }
        } else {
            var confirm = window.confirm('Sei sicuro di voler cancellare questo prodotto?'); 
            if (confirm) {
                ajaxDeleteProductVariationPrice(name,id,catname,seasonname,deleterow)
            } else {
            }
        }
    });

    // product elimination ajax
    function ajaxDeleteProductVariationPrice(name,id,catname,seasonname,deleterow){
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
                catnamer = catname.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '')
                objs = JSON.parse(response);
                var prductoid = objs['productid']
                if (objs['response'] == 'true') {
                    if (deleterow !== 'false') {
                        jQuery("#dp_"+seasonname+"_variation_"+name+" #dp_category_"+catnamer+"_variation_"+id+"").html("<div class='.input-"+name+"-"+id+"'><i class='fas fa-times-circle'></i></div><input type='text' placeholder='-' productid="+prductoid+">")
                        setTimeout(function(){ 
                            $("#"+deleterow).fadeOut(1000,function() {
                                $("#"+deleterow).remove();
                            }); 
                        }, 1000);
                        
                    } else {
                        jQuery("#dp_"+seasonname+"_variation_"+name+" #dp_category_"+catnamer+"_variation_"+id+"").html("<div class='.input-"+name+"-"+id+"'><i class='fas fa-times-circle'></i></div><input type='text' placeholder='-' productid="+prductoid+">")
                    }
                }
            }
        });
    }

    // delete all products in a row 
    $( ".dp-row-delete-icon" ).click(function(e) { 
        var delRawAllVariations = window.confirm('Tutte le variazioni di questa riga verrano eliminte, sei sicuro di voler procedere?');
        if (delRawAllVariations) {
            var tr = $(this).closest("tr");
            var variationcells = tr.find(".dp-delete-icon");
            variationcells.each(function(index){
                var id = $(this).attr("id");
                var name = $(this).attr("name");
                var catname = $(this).attr("catname");
                var seasonname = $(this).attr("seasonname");
                var deleterow = 'false';
                if (index == (variationcells.length - 1)) {
                    deleterow = tr.attr('id');
                    ajaxDeleteProductVariationPrice(name,id,catname,seasonname,deleterow)
                        
                } else {
                    ajaxDeleteProductVariationPrice(name,id,catname,seasonname,deleterow)
                }
            })
        } else {
        }
    });
    

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