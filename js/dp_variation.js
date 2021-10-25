jQuery(document).ready( function($) {

    // product update eventlistener
    $( "input" ).keypress(function(e) {
        inputKeypressUpdateProduct(e,$(this));
    });
    function inputKeypressUpdateProduct(e,element) {
        if(e.which == 13){
            var price = element.val();

            if (!$.isNumeric(price) ) {
                alert('Non usare le lettere!')
            } else if ( !price ) {
                alert('Inserire il prezzo!')
            } else if (element.attr("placeholder") == price) {
                alert('Il prezzo non può essere uguale a prima!')
            } else {
                ajaxUpdateProductVariationPrice(element.attr("name"),element.attr("id"),price);
            }
        }
    }

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

    // product variation elimination eventlistner
    $( ".dp-delete-icon" ).click(function(e) { 
        dpDeleteIconFunction($(this));
    });
    function dpDeleteIconFunction(e) {
        var id = e.attr("id");
        var name = e.attr("name");
        var catname = e.attr("catname");
        var seasonname = e.attr("seasonname");
        var tr = e.closest("tr");
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
    }

    // product variation elimination ajax
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
        dpRawDeleteIconFunction($(this));
    });
    function dpRawDeleteIconFunction(e){
        var delRawAllVariations = window.confirm('Tutte le variazioni di questa riga verrano eliminte, sei sicuro di voler procedere?');
        if (delRawAllVariations) {
            var tr = e.closest("tr");
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
    }
    
    // Add variation modal (popup) eventlistener
    $('.addVariant').on('click',function(){
        var productarray = $(this).data('productarray');
        var routeid = $(this).data('routeid');
        var place = $(this).data('place');
        var from = $(this).data('from');
        var to = $(this).data('to');
        var seasonname = $(this).data('seasonname');
        $('.dp_add_variation_container').show();
        ajaxAddProductVariationModal(productarray,routeid,place,from,to,seasonname)
    });
    $('.dp_add_variation_container_close').on('click',function(){
        $('.dp_add_variation_container').hide();
    });

   

    // product add variation ajax for add raw modal
    function ajaxAddProductVariationModal(productarray,routeid,place,from,to,seasonname){
        var data = {
            'action': 'oc_ajax_variation_add_modal',
            'productarray':  productarray,
            'routeid':  routeid,
            'place':  place,
            'from':  from,
            'to':  to,
            'seasonname':  seasonname,
        };
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type : 'post',
            data: data,
            beforeSend: function(){
                jQuery(".dp_add_variation_body").html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success : function( response ) {
                objs = JSON.parse(response);
                var output = objs['output']
                if (objs['response'] == 'true') {
                    jQuery(".dp_add_variation_body").html(output);
                    
                    
                    // Create product variants modal (popup)
                    $(".createVariantbtn").on('click',function(){
                        var products = {};
                        var varname = $("#dpvariationsmodal").val();
                        var seasonname = $(".dpseasonnamemodal").attr("id");
                        if (hasValue("input.dpcatinputsmodal")) {
                            $("input.dpcatinputsmodal").each(function(e){	
                                var productid = $(this).attr("id");
                                var price = $(this).val();
    
                                if ( !$.isNumeric(price) ) {
                                    products[productid]= 0;
                                } else {
                                    products[productid]= price;
                                }
                            });
                        } else {
                            alert('Aggiungi almeno un prezzo a una categoria')
                        }
                        if (!$.isEmptyObject(products)) {
                            ajaxCreateProductVariation(products,varname,seasonname,place,from,to,productarray);
                        }
                    })

                } else {
                    jQuery(".dp_add_variation_body").html('Sorry something went wrong! Call Pedram');
                }
            }
        });
    }

    // product variation Create ajax modal (popup)
    function ajaxCreateProductVariation(products,varname,seasonname,place,from,to,productarray){
        var data = {
            'action': 'oc_ajax_variation_create_modal',
            'products':  products,
            'varname':  varname,
            'seasonname':  seasonname,
            'place':  place,
            'from':  from,
            'to':  to,
            'productarray': productarray
        };
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type : 'post',
            data: data,
            beforeSend: function(){
                jQuery(".dp_loader_modal").html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success : function( response ) {
                objs = JSON.parse(response);
                var output = objs['output'];
                if (objs['response'] == 'true') {
                    $('.dp_add_variation_container').hide();
                    $("#tab-"+seasonname+" tbody").append(output);
                }
                // delete one product
                $( ".dp-delete-icon" ).click(function(e) { 
                    dpDeleteIconFunction($(this));
                });
                // delete all products in a row 
                $( ".dp-row-delete-icon" ).click(function(e) { 
                    dpRawDeleteIconFunction($(this));
                });
                // product update eventlistener
                $( "input" ).keypress(function(e) {
                    inputKeypressUpdateProduct(e,$(this))
                });
            }
        });
    }

    // --------------- START ADD PRODUCT ------------------- // 

    // Add Product modal (popup) eventlistener
    $('.addProduct').on('click',function(){
        var productarray = $(this).data('productarray');
        var routeid = $(this).data('routeid');
        var place = $(this).data('place');
        var from = $(this).data('from');
        var to = $(this).data('to');
        var seasonname = $(this).data('seasonname');
        var seasonnameid = $(this).data('seasonnameid');
        var repeaterrawid = $(this).data('repeaterrawid');
        var subfieldkey = $(this).data('subfieldkey');
        var repeatername = $(this).data('repeatername');
        $('.dp_add_product_container').show();
        ajaxAddProductOptionsModal(productarray,routeid,place,from,to,seasonname,seasonnameid,repeaterrawid,subfieldkey,repeatername)
    });
    $('.dp_add_product_container_close').on('click',function(){
        $('.dp_add_product_container').hide();
    });

    // Add product options ajax for add product modal
    function ajaxAddProductOptionsModal(productarray,routeid,place,from,to,seasonname,seasonnameid,repeaterrawid,subfieldkey,repeatername){
        var data = {
            'action': 'oc_ajax_product_options_add_modal',
            'productarray':  productarray,
            'routeid':  routeid,
            'place':  place,
            'from':  from,
            'to':  to,
            'seasonname':  seasonname,
            'seasonnameid':  seasonnameid,
            'repeaterrawid':  repeaterrawid,
            'subfieldkey':  subfieldkey,
            'repeatername':  repeatername,
        };
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type : 'post',
            data: data,
            beforeSend: function(){
                jQuery(".dp_add_product_body").html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success : function( response ) {
                objs = JSON.parse(response);
                var output = objs['output']
                if (objs['response'] == 'true') {
                    jQuery(".dp_add_product_body").html(output);
                    
                    // Create product variants modal (popup)
                    $(".createProductbtn").on('click',function(){
                        var products = {};
                        var seasonname = $(".dpseasonnamemodal").attr("id");
                        if (!hasValue("input.dpproductattributename") && !hasValue("input.dpproductsoptionsmodal[name='adult']")) {
                            alert('I campi Nome categoria e il prezzo della camera doppia sono obbligatori!')
                            $(".dpproductsoptionsmodal[name='adult']").addClass('error');
                            $(".dpproductattributename").addClass('error');
                        } else {
                            $(".dpproductsoptionsmodal[name='adult']").removeClass('error');
                            $(".dpproductattributename").removeClass('error');
                            $("input.dpproductsoptionsmodal").each(function(e){	
                                var productid = $(this).attr("id");
                                var price = $(this).val();
                                if(price) {
                                    products[productid]= price;
                                }
                            });
                        }
                        if (!$.isEmptyObject(products)) {
                            ajaxCreateProductWithOptions(products,productarray,routeid,seasonname,seasonnameid,repeaterrawid,subfieldkey,repeatername);
                        }
                    })

                } else {
                    jQuery(".dp_add_product_body").html('Sorry something went wrong! Call Pedram');
                }
            }
        });
    }

    // product variation Create ajax modal (popup)
    function ajaxCreateProductWithOptions(products,productarray,routeid,seasonname,seasonnameid,repeaterrawid,subfieldkey,repeatername){
        var data = {
            'action': 'oc_ajax_product_create_modal',
            'products':  products,
            'productarray':  productarray,
            'routeid':  routeid,
            'seasonname':  seasonname,
            'seasonnameid':  seasonnameid,
            'repeaterrawid':  repeaterrawid,
            'subfieldkey':  subfieldkey,
            'repeatername':  repeatername,
        };
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type : 'post',
            data: data,
            beforeSend: function(){
                jQuery(".dp_loader_modal").html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success : function( response ) {
                objs = JSON.parse(response);
                var output = objs['output'];
                if (objs['response'] == 'true') {
                    if (location.href == output) {
                        location.reload()
                    } else {
                        location.href = output;
                    }
                }
            }
        });
    }
    // --------------- END ADD PRODUCT ------------------- //     


    // --------------- START DELETE PRODUCT ------------------- // 
    // complete product elimination eventlistner
    $( ".dp-category-delete-icon" ).click(function(e) { 
        dpProductDeleteIconFunction($(this));
    });
    function dpProductDeleteIconFunction(e) {
        var id = e.data("id");
        var seasonnameid = e.data("seasonnameid");
        var routeid = e.data("routeid");
        var wrapper = e.closest(".dp-delete-icon-wrapper");
        var repeaterrawid = e.data('repeaterrawid');
        var subfieldkey = e.data('subfieldkey');
        var repeatername = e.data('repeatername');
        var confirmdelrow = window.confirm('Questa categoria verrà eliminata, sei sicuro di voler procedere?'); 
        if (confirmdelrow) {
            ajaxDeleteCompleteProduct(id,seasonnameid,routeid,wrapper,repeaterrawid,subfieldkey,repeatername)
        } else {
        }
    }
    // product elimination ajax
    function ajaxDeleteCompleteProduct(id,seasonnameid,routeid,wrapper,repeaterrawid,subfieldkey,repeatername){
        var data = {
            'action': 'oc_ajax_product_delete',
            'productid':  id,
            'seasonnameid':  seasonnameid,
            'routeid':  routeid,
            'repeaterrawid':  repeaterrawid,
            'subfieldkey':  subfieldkey,
            'repeatername':  repeatername,
        };
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type : 'post',
            data: data,
            beforeSend: function(){
                wrapper.append('<i class="fas fa-spinner fa-spin"></i>');
            },
            success : function( response ) {
                objs = JSON.parse(response);
                var output = objs['output'];
                if (objs['response'] == 'true') {
                    if (location.href == output) {
                        location.reload()
                    } else {
                        location.href = output;
                    }
                }
            }
        });
    }



    // --------------- END DELETE PRODUCT ------------------- // 
    function hasValue(elem) {
        var result = false;
        $(elem).each(function(e){
            var price = $(this).val();	
            if (price && $.isNumeric(price)) {
                result = true;
            }
        })
        return result;
    }
     
    window.addEventListener('click', dp_outsideClick);
    // Close If Outside Click
    function dp_outsideClick(e) {
        if (e.target.id == 'dp_add_variation-modal') {
            $('.dp_add_variation_container').hide();
        }
        if (e.target.id == 'dp_add_product-modal') {
            $('.dp_add_product_container').hide();
        }
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

    // if get parameters exists open date price modal
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
    
            if (sParameterName[0] === sParam) {
                return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };
    var dateprices = getUrlParameter('dateprices');
    if (!!dateprices) {
        setTimeout(function(){ $("#popup-show-prices").click(); }, 500);
    }
});

jQuery(function(){
    window.et_pb_smooth_scroll = () => {};
});