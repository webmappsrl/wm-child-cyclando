<?php 

add_shortcode( 'oneclick_route_form_purchase', 'oneclick_route_form_purchase' );
  
function oneclick_route_form_purchase($atts) {
    extract( shortcode_atts( array(
        'route' => '',
        'has_extra' => '',
        'first_departure' => '',
        'hotel_product_items' => ''
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
    $has_hotel_category = route_has_hotel_category($post_id,$first_departure);
    $has_single = route_has_single($has_hotel_category);

    ob_start();

    ?>
    <div class="cyc-single-route-cta-buttons oc-acquista-route">
    <?php 
    // This line is substitued with next line to hide Supplement section until it becomes calculable
    // if ($hotel_product_items || $has_extra || $has_single): 
    ?>
    <?php if ($has_extra): ?>
        <div id="oc-acquista-route" class="">
            <div class="cy-btn-contact">
                <p><?php echo __('Proceed', 'wm-child-cyclando'); ?></p>
            </div>
        </div>
    <?php else : ?>
        <div id="oc-proceed-done-btn" class="oc-proceed-done-btn"><?= __('Proceed','wm-child-cyclando')?></div>
    <?php endif; ?>
    </div>


    <!-- HTML modal for extras, proceed btn -->
    <div id="oc-extras-modal" class="ocm-proceed-container">
        <div class="ocm-proceed-content">
            <?= do_shortcode('[onclick_route_mobile_calculator_header back_btn="back-calculator" scheda_name="extras"]')?>
            <?php if ($has_extra) { ?>
            <h4 class="form-lable ocm-extras-nocond-title"><?php echo __('Do you want to add more?', 'wm-child-cyclando'); ?></h4>
            <div class="ocm-extras-proceed-detail-container">

            </div>
            <?php } ?>
            
            <?php 
            // This line is substitued with next line to hide Supplement section until it becomes calculable
            // if ($has_single || $hotel_product_items) { 
            ?>
            <?php if ( false) { ?>
            <h4 class="form-lable ocm-extras-nocond-title"><?php echo __('Supplements', 'wm-child-cyclando'); ?></h4>
            <div class="ocm-extras-cond-disclaimer"><?php echo __('Subject to availability check. We will send you an email with the confirmation of availability and the price of the supplement', 'wm-child-cyclando'); ?></div>
            <div class="ocm-hotel-proceed-detail-container">
                <?php if ($has_single) {
                  echo do_shortcode("[oneclick_route_form_single_room]");
                } ?>
            </div>
            <?php } ?>
            <div class="ocm-extras-proceed-summary-container">
                <div class="oc-route-mobile-plan-price-container">
                    <div class="cifraajax-title"><?= __('Extra Total', 'wm-child-cyclando') ?></div><div class="cifraajaxextra"></div>
                </div>
                <div class="oc-route-mobile-plan-exclusive-online">
                    <div class="exclusive-online-title"><?= __('Online Exclusive!', 'wm-child-cyclando') ?></div>
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
                    $( ".facetwp-checkbox" ).each(function(index,element) {
                        var savedCookie = ocmCheckCookie();
                        if ( !!savedCookie[post_id] && !!savedCookie[post_id]['extra'] && savedCookie[post_id]['extra'][$(this).attr('name')] > 0) {
                            var sums = cal_sum_cookies(savedCookie);
                            if (savedCookie[post_id]['extra'][$(this).attr('name')] < sums['participants']) {
                                $('.oc-modal-button-container-'+$(this).attr('name')+' .oc-extra-add-btn').prop("disabled", false);
                            }
                        }
                    });
                });
                $('.oc-proceed-done-btn').on('click',function(){
                    // populate extra section in your reservation if any extra is selected
                    var savedCookie = ocmCheckCookie();
                    try{
                        if (!!savedCookie[post_id]['extra']['single_room_paid']) {
                        jQuery('.oc-route-your-reservation-singleroompaid-title').show();
                        jQuery('.oc-route-your-reservation-singleroompaid-info').show();
                        jQuery('.oc-route-your-reservation-singleroompaid-info p').html(savedCookie[post_id]['extra']['single_room_paid']);
                    }
                    } catch(e) {
                        jQuery('.oc-route-your-reservation-singleroompaid-title').hide();
                        jQuery('.oc-route-your-reservation-singleroompaid-info').hide();
                        jQuery('.oc-route-your-reservation-singleroompaid-info p').html('');
                    }
                    // if (!!savedCookie[post_id]['extra']['single_room_paid']) {
                    //     jQuery('.oc-route-your-reservation-singleroompaid-title').show();
                    //     jQuery('.oc-route-your-reservation-singleroompaid-info').show();
                    //     jQuery('.oc-route-your-reservation-singleroompaid-info p').html(savedCookie[post_id]['extra']['single_room_paid']);
                    // } else {
                    //     jQuery('.oc-route-your-reservation-singleroompaid-title').hide();
                    //     jQuery('.oc-route-your-reservation-singleroompaid-info').hide();
                    //     jQuery('.oc-route-your-reservation-singleroompaid-info p').html('');
                    // }
                    if ( !!savedCookie[post_id] && !!savedCookie[post_id]['extra'] && !!savedCookie[post_id]['supplement']) {
                        $('.oc-route-extra-row.oc-route-extra-details').empty();
                        if (!!savedCookie[post_id]['extra']) {
                            updateYourReservationExtraSummaryTxt(savedCookie,has_extra);
                        }
                        if (!!savedCookie[post_id]['supplement']) {
                            updateYourReservationHotelSummaryTxt(savedCookie,hotel_product_items);
                        }
                    } else {
                        $('.oc-route-extra-row.oc-route-extra-header').removeClass("display-flex");
                        $('.oc-route-extra-row.oc-route-extra-details').removeClass("display-flex");
                        $('.oc-route-extra-row.oc-route-extra-details').empty();
                    }
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
                if (has_extra) {
                    $.each(has_extra,function(index,value){
                        var defaulnum = 0;
                        var savedCookie = ocmCheckCookie();
                        if (!savedCookie[post_id] || !savedCookie[post_id]['extra']) {
                            savedCookie[post_id] = savedCookie[post_id] || {};
                            savedCookie[post_id]['extra'] = {};
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } 
                        if (savedCookie[post_id] && savedCookie[post_id]['extra']) {
                            if (savedCookie[post_id]['extra'][index] > 0) {
                                defaulnum = savedCookie[post_id]['extra'][index];
                            }
                        }
                        $(".ocm-extras-proceed-detail-container").append(
                        '<div class="ocm-proceed-extras-body "><div class="facetwp-checkbox facetwp-checkbox-'+index+'" name="'+index+'"><div class="label">'+value.label+' (<strong>'+value.price+'€</strong>)</div></div><div class="oc-modal-button-container oc-modal-button-container-'+index+'"><button class="modal-btn oc-extra-substract-btn" name="'+index+'"><i class="fal fa-minus"></i></button><div id="'+index+'" class="oc-number-input">'+defaulnum+'</div><button class="modal-btn oc-extra-add-btn" name="'+index+'"><i class="fal fa-plus"></i></button></div>'
                        );
                    });
                }
                

                // Populate the extra conditional hotel products from var hotel_product_items
                $.each(hotel_product_items,function(index,value){ 
                    var defaulnum = 0;
                    var savedCookie = ocmCheckCookie();
                    if (!savedCookie[post_id] || !savedCookie[post_id]['supplement']) {
                        savedCookie[post_id] = savedCookie[post_id] || {};
                        savedCookie[post_id]['supplement'] = {};
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    }
                    if (savedCookie[post_id]['supplement']) {
                        if (savedCookie[post_id]['supplement'][index] > 0) {
                            defaulnum = savedCookie[post_id]['supplement'][index];
                        }
                    }
                    $(".ocm-hotel-proceed-detail-container").append(
                    '<div class="ocm-proceed-extras-body "><div class="facetwp-checkbox facetwp-checkbox-'+index+'" name="'+index+'" conditional="true" ><div class="label">'+value.label+' (<strong>'+value.price+'€</strong>)</div></div><div class="oc-modal-button-container oc-modal-button-container-'+index+'"><button class="modal-btn oc-extra-substract-btn" name="'+index+'" conditional="true"><i class="fal fa-minus"></i></button><div id="'+index+'" class="oc-number-input">'+defaulnum+'</div><button class="modal-btn oc-extra-add-btn" name="'+index+'" conditional="true"><i class="fal fa-plus"></i></button></div>'
                    );
                });
                
                //checkbox interactions
                $( ".facetwp-checkbox" ).each(function(index,element) {
                    var savedCookie = ocmCheckCookie();
                    if ( !savedCookie[post_id]) {
                        savedCookie[post_id] = {};
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    }
                    if (!savedCookie[post_id]['extra']) {
                        savedCookie[post_id]['extra'] = {};
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    } 
                    if (!savedCookie[post_id]['supplement']) {
                        savedCookie[post_id]['supplement'] = {};
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    }
                    if (!!savedCookie[post_id]['extra'] && savedCookie[post_id]['extra'][$(this).attr('name')] > 0) {
                        $( this ).toggleClass( "checked" );
                        $('.oc-modal-button-container-'+$(this).attr('name')).toggleClass("display-grid");
                        $('#'+$(this).attr('name')).text(savedCookie[post_id]['extra'][$(this).attr('name')]);
                        var sums = cal_sum_cookies(savedCookie);
                        if (savedCookie[post_id]['extra'][$(this).attr('name')] == sums['participants']) {
                            $('.oc-modal-button-container-'+$(this).attr('name')+' .oc-extra-add-btn').prop("disabled", true);
                        }
                    }
                    if (!!savedCookie[post_id]['supplement'] && savedCookie[post_id]['supplement'][$(this).attr('name')] > 0) {
                        $( this ).toggleClass( "checked" );
                        $('.oc-modal-button-container-'+$(this).attr('name')).toggleClass("display-grid");
                        $('#'+$(this).attr('name')).text(savedCookie[post_id]['supplement'][$(this).attr('name')]);
                        var sums = cal_sum_cookies(savedCookie);
                        if (savedCookie[post_id]['supplement'][$(this).attr('name')] == sums['participants']) {
                            $('.oc-modal-button-container-'+$(this).attr('name')+' .oc-extra-add-btn').prop("disabled", true);
                        }
                    }
                    $(element).click( function(e){
                        var savedCookie = ocmCheckCookie();
                        $( this ).toggleClass( "checked" );
                        var container = $('.oc-modal-button-container-'+$(this).attr('name'));
                        container.toggleClass("display-grid");
                        var counter = $('#'+$(this).attr('name'));
                        if (!$(this).attr('conditional')){
                            if ($(this).hasClass("checked")) {
                            counter.text(1);
                            savedCookie[post_id]['extra'][$(this).attr('name')] = 1;
                            } else {
                                counter.text(0);
                                delete savedCookie[post_id]['extra'][$(this).attr('name')];
                            }
                        } else {
                            if ($(this).hasClass("checked")) {
                            counter.text(1);
                            savedCookie[post_id]['supplement'][$(this).attr('name')] = 1;
                            } else {
                                counter.text(0);
                                delete savedCookie[post_id]['supplement'][$(this).attr('name')];
                            }
                        }
                        
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        if (!$(this).attr('conditional')){
                            ajaxUpdatePrice();
                        }
                    });
                });
                //Add button
                $( ".oc-extra-add-btn" ).each(function(index,element) {
                    $(element).click( function(e){
                        var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie'));
                        var counter = $('#'+$(this).attr('name'));
                        var count = parseInt(counter.text());
                        num = count + 1;
                        var sums = cal_sum_cookies(savedCookie);
                        if (num == sums['participants']) {
                            $(this).prop("disabled", true);
                        }
                        counter.text(num);
                        if (!$(this).attr('conditional')){
                            savedCookie[post_id]['extra'][$(this).attr('name')] = num;
                        } else {
                            savedCookie[post_id]['supplement'][$(this).attr('name')] = num;
                        }
                        var countplus = parseInt(counter.text());
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        if (!$(this).attr('conditional')){
                            ajaxUpdatePrice();
                        }
                    });
                });
                //Substract button
                $( ".oc-extra-substract-btn" ).each(function(index,element) {
                    $(element).click( function(e){
                        var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie'));
                        $('.oc-modal-button-container-'+$(this).attr('name')+' .oc-extra-add-btn').prop("disabled", false);
                        var counter = $('#'+$(this).attr('name'));
                        var count = parseInt(counter.text());
                        num = count - 1;
                        if (num < 1) {
                            counter.text(0);
                            if (!$(this).attr('conditional')){
                                delete savedCookie[post_id]['extra'][$(this).attr('name')];
                            } else {
                                delete savedCookie[post_id]['supplement'][$(this).attr('name')];
                            }
                            
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                            if (!$(this).attr('conditional')){
                                ajaxUpdatePrice();
                            }
                            $('.oc-modal-button-container-'+$(this).attr('name')).toggleClass("display-grid");
                            $( ".facetwp-checkbox-"+$(this).attr('name')).toggleClass('checked');
                        } else { 
                            counter.text(num);
                            if (!$(this).attr('conditional')){
                            savedCookie[post_id]['extra'][$(this).attr('name')] = num;
                            } else {
                                savedCookie[post_id]['supplement'][$(this).attr('name')] = num;
                            }
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                            if (!$(this).attr('conditional')){
                                ajaxUpdatePrice();
                            }
                        }
                    });
                });
                function updateYourReservationExtraSummaryTxt (savedCookie,has_extra) {
                    $('.oc-route-extra-row.oc-route-extra-header').addClass("display-flex");
                    $('.oc-route-extra-row.oc-route-extra-details').addClass("display-flex");
                    $.each(savedCookie[post_id]['extra'],function(index,value){
                        var extra = has_extra[index];
                        if (extra) {
                            var label = extra.label;
                            $('.oc-route-extra-row.oc-route-extra-details').append(
                                '<div class="oc-route-your-reservation-column-title"><p>'+label+'</p></div><div class="oc-route-your-reservation-column-info"><p>'+value+'</p></div>'
                            )
                        } else {
                            if ( index !== 'single_room_paid') {
                                delete savedCookie[post_id]['extra'][index];
                                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                            }
                        }
                    });
                }
                function updateYourReservationHotelSummaryTxt (savedCookie,has_extra) {
                    $('.oc-route-extra-row.oc-route-extra-header').addClass("display-flex");
                    $('.oc-route-extra-row.oc-route-extra-details').addClass("display-flex");
                    var has_single = '<?= $has_single ?>';
                    $.each(savedCookie[post_id]['supplement'],function(index,value){
                        var extra = has_extra[index];
                        if (index == 'single_room' && has_single) {
                            var label = '<?php echo __('Single room', 'wm-child-cyclando'); ?>'
                            $('.oc-route-extra-row.oc-route-extra-details').append(
                            '<div class="oc-route-your-reservation-column-title"><p>'+label+'</p></div><div class="oc-route-your-reservation-column-info"><p>'+value+'</p></div>')
                        } else if (extra) {
                            var label = extra.label;
                            $('.oc-route-extra-row.oc-route-extra-details').append(
                            '<div class="oc-route-your-reservation-column-title"><p>'+label+'</p></div><div class="oc-route-your-reservation-column-info"><p>'+value+'</p></div>')
                        } else {
                            delete savedCookie[post_id]['supplement'][index];
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        }
                    });
                }
            });
        })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

