<?php 

add_shortcode( 'oneclick_route_form_purchase', 'oneclick_route_form_purchase' );
  
function oneclick_route_form_purchase($atts) {
    extract( shortcode_atts( array(
        'route' => '',
        'has_extra' => ''
    ), $atts ) );

    $post_id = get_the_ID();
    $wm_post_id = wm_get_original_post_it($post_id);
    $wm_post_id = $wm_post_id['id'];
    // get the extra fields for extra popup 
    $has_extra = route_has_extra_category($wm_post_id);
    if ($has_extra['bike']) {
        unset($has_extra['bike']);
    }
    if ($has_extra['ebike']) {
        unset($has_extra['ebike']);
    }
    ob_start();

    ?>
    <div class="cyc-single-route-cta-buttons oc-acquista-route">
        <div id="oc-acquista-route" class="">
            <div class="cy-btn-contact">
                <p><?php echo __('Purchase', 'wm-child-cyclando'); ?></p>
            </div>
        </div>
    </div>


    <!-- HTML modal for extras, proceed btn -->
    <div id="oc-extras-modal" class="ocm-proceed-container">
        <div class="ocm-proceed-content">
            <?php if ($has_extra) { ?>
            <div class="ocm-extras-nocond-title"><?php echo __('Do you want to add more?', 'wm-child-cyclando'); ?></div>
            <div class="ocm-extras-proceed-detail-container">


            </div>
            <?php } ?>
            <div class="ocm-extras-proceed-summary-container">
                <div class="oc-route-mobile-plan-price-container">
                    <div class="cifraajax-title"><?= __('Total', 'wm-child-cyclando') ?></div><div class="cifraajax"></div>
                </div>
                <div id="oc-proceed-done-btn" class="oc-proceed-done-btn"><?= __('Proceed','wm-child-cyclando')?></div>
            </div>
        </div>
    </div>
    <script>
        (function ($) {
            $(document).ready(function () {
                $('#oc-acquista-route .cy-btn-contact').on('click',function(){
                    $('.ocm-proceed-container').show();
                });
                $('.oc-proceed-done-btn').on('click',function(){
                    $('.ocm-proceed-container').hide();
                    $('.oc-route-mobile-search-form-container').hide();
                    $('.oc-route-mobile-your-reservation-container').show();
                });
                window.addEventListener('click', outsideClick);
                // Close If Outside Click
                function outsideClick(e) {
                    if (e.target.id == 'oc-extras-modal') {
                        $('.ocm-proceed-container').hide();
                    }
                }
                // Populate the extras from var has_extra
                var savedCookie = ocmCheckCookie();
                if (savedCookie['extra']) {

                } else {
                    savedCookie['extra'] = {};
                }
                $.each(has_extra,function(index,value){
                    var defaulnum = 0;
                    if (savedCookie['extra']) {
                        if (savedCookie['extra'][index] > 0) {
                            defaulnum = savedCookie['extra'][index];
                        }
                    }
                    $(".ocm-extras-proceed-detail-container").append(
                    '<div class="ocm-proceed-extras-body "><div class="facetwp-checkbox facetwp-checkbox-'+index+'" name="'+index+'"><div class="label">'+value.label+' (<strong>'+value.price+'€</strong>)</div></div><div class="oc-modal-button-container oc-modal-button-container-'+index+'"><button class="modal-btn oc-extra-substract-btn" name="'+index+'"><i class="fas fa-minus"></i></button><div id="'+index+'" class="oc-number-input">'+defaulnum+'</div><button class="modal-btn oc-extra-add-btn" name="'+index+'"><i class="fas fa-plus"></i></button></div>'
                    );
                });
                //checkbox interactions
                $( ".facetwp-checkbox" ).each(function(index,element) {
                    if (savedCookie['extra'][$(this).attr('name')] > 0) {
                        $( this ).toggleClass( "checked" );
                        $('.oc-modal-button-container-'+$(this).attr('name')).toggleClass("display-grid");
                        $('#'+$(this).attr('name')).text(savedCookie['extra'][$(this).attr('name')]);
                    }
                    $(element).click( function(e){
                        $( this ).toggleClass( "checked" );
                        var container = $('.oc-modal-button-container-'+$(this).attr('name'));
                        container.toggleClass("display-grid");
                        var counter = $('#'+$(this).attr('name'));
                        if ($(this).hasClass("checked")) {
                            counter.text(1);
                            savedCookie['extra'][$(this).attr('name')] = 1;
                        } else {
                            counter.text(0);
                            savedCookie['extra'][$(this).attr('name')] = 0;
                        }
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                        ajaxUpdatePrice();
                    });
                });
                //Add button
                $( ".oc-extra-add-btn" ).each(function(index,element) {
                    $(element).click( function(e){
                        var savedCookie = ocmCheckCookie();
                        console.log('ammato');
                        console.log(savedCookie);
                        var counter = $('#'+$(this).attr('name'));
                        var count = parseInt(counter.text());
                        num = count + 1;
                        if (num + 1 == savedCookie['adults']) {
                            $(this).prop("disabled", true);
                        } else {
                            counter.text(num);
                            savedCookie['extra'][$(this).attr('name')] = num;
                            var countplus = parseInt(counter.text());
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                            ajaxUpdatePrice();
                        }
                    });
                });
                //Substract button
                $( ".oc-extra-substract-btn" ).each(function(index,element) {
                    $(element).click( function(e){
                        $('.oc-modal-button-container-'+$(this).attr('name')+' .oc-extra-add-btn').prop("disabled", false);
                        var counter = $('#'+$(this).attr('name'));
                        var count = parseInt(counter.text());
                        num = count - 1;
                        if (num < 1) {
                            counter.text(0);
                            savedCookie['extra'][$(this).attr('name')] = 0;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                            ajaxUpdatePrice();
                            $('.oc-modal-button-container-'+$(this).attr('name')).toggleClass("display-grid");
                            $( ".facetwp-checkbox-"+$(this).attr('name')).toggleClass('checked');
                        } else { 
                            counter.text(num);
                            savedCookie['extra'][$(this).attr('name')] = num;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                            ajaxUpdatePrice();
                        }
                    });
                });
            });
        })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

