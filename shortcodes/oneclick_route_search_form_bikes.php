<?php 

add_shortcode( 'oneclick_route_search_form_bikes', 'oneclick_route_search_form_bikes' );
  
function oneclick_route_search_form_bikes($atts) {
    extract( shortcode_atts( array(
        'route' => '',
        'has_bike' => '',
        'has_ebike' => ''
    ), $atts ) );
    ob_start();


    ?>
    <?php if ($has_bike || $has_ebike): ?>
        <?php if ($route): ?>
                <?php if ($has_bike): ?>
                <div class="ocm-bikes-body">
                    <div class="regular-label"><?php echo __('Regular','wm-child-cyclando'); ?></div>
                    <button  class="modal-btn oc-substract-btn" name="regular-bikes"><i class="fal fa-minus"></i></button>
                    <div id="regular-bikes" class="oc-number-input">0</div>
                    <button  class="modal-btn oc-add-btn" name="regular-bikes"><i class="fal fa-plus"></i></button>
                </div>
                <?php endif; ?>
                <?php if ($has_ebike): ?>
                <div class="ocm-bikes-body">
                    <div class="ebike-label"><?php echo __('Ebike','wm-child-cyclando'); ?></div>
                    <button  class="modal-btn oc-substract-btn" name="electric-bikes"><i class="fal fa-minus"></i></button>
                    <div id="electric-bikes" class="oc-number-input">0</div>
                    <button class="modal-btn oc-add-btn" name="electric-bikes"><i class="fal fa-plus"></i></button>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="ocm-bikes-body">
                    <div class="regular-label"><?php echo __('Regular','wm-child-cyclando'); ?></div>
                    <button  class="modal-btn oc-substract-btn" name="regular-bikes"><i class="fal fa-minus"></i></button>
                    <div id="regular-bikes" class="oc-number-input">0</div>
                    <button  class="modal-btn oc-add-btn" name="regular-bikes"><i class="fal fa-plus"></i></button>
                </div>
                <div class="ocm-bikes-body">
                    <div class="ebike-label"><?php echo __('Ebike','wm-child-cyclando'); ?></div>
                    <button  class="modal-btn oc-substract-btn" name="electric-bikes"><i class="fal fa-minus"></i></button>
                    <div id="electric-bikes" class="oc-number-input">0</div>
                    <button class="modal-btn oc-add-btn" name="electric-bikes"><i class="fal fa-plus"></i></button>
                </div>
            <?php endif; ?>
        <div id="ocm-warning-bikes-container" class="ocm-warning-bike-container"></div>
    <?php endif; ?>
    <?php if (!$has_bike && !$has_ebike): ?>
        <div class=""><?= __('Bikes included','wm-child-cyclando'); ?></div>
    <?php endif; ?>

    <script>
    (function ($) {
        $(document).ready(function () {
            var savedCookie = ocmCheckCookie();
            <?php if ($route) { ?>
                // checks if the ebikes are not availible and if they are previously selected, adds their value to bikes
                var has_ebike = <?php echo json_encode($has_ebike )?>;
                if (has_ebike == 0) {
                    if (savedCookie['electric']) {
                        savedCookie['regular'] += parseInt(savedCookie['electric']);
                        delete savedCookie['electric'];
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        $("#ocm-warning-bikes-container").append(
                                    '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('Ebikes are not available in this route. Their number is added to bikes','wm-child-cyclando'); ?></div>'
                                );
                    }
                }
            <?php } ?>
            if (parseInt(savedCookie['electric'])>0) {
                $('#electric-bikes').text(parseInt(savedCookie['electric']));
            }
            if (parseInt(savedCookie['regular'])>0) {
                $('#regular-bikes').text(parseInt(savedCookie['regular']));
            }

            //Add button
            $( ".oc-add-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'regular-bikes' || $(e.target).attr('name') == 'electric-bikes') {
                        var counter = $('#'+$(e.target).attr('name'));
                        var count = parseInt(counter.text());
                        num = count + 1;
                    }
                    if ($(e.target).attr('name') == 'regular-bikes') {
                        savedCookie['regular'] = num;
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        updatePriceOnEachBikeSelect(counter,num,'regular');
                    }
                    if ($(e.target).attr('name') == 'electric-bikes') {
                        savedCookie['electric'] = num;
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        updatePriceOnEachBikeSelect(counter,num,'electric');
                    }
                });
            });
            //Substract button
            $( ".oc-substract-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'regular-bikes' || $(e.target).attr('name') == 'electric-bikes') {
                        $("#ocm-warning-container").empty();
                        var counter = $('#'+$(e.target).attr('name'));
                        var count = parseInt(counter.text());
                        count = count - 1;
                    }
                    if ($(e.target).attr('name') == 'regular-bikes') {
                        if (count > 0 ) {
                            savedCookie['regular'] = count;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } else {
                            delete savedCookie['regular'];
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        }
                        updatePriceOnEachBikeSelect(counter,count,'regular');
                    }
                    if ($(e.target).attr('name') == 'electric-bikes') {
                        if (count > 0 ) {
                            savedCookie['electric'] = count;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } else {
                            delete savedCookie['electric'];
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        }
                        updatePriceOnEachBikeSelect(counter,count,'electric');
                    }
                });
            });

            function updatePriceOnEachBikeSelect(counter,num,type){
                savedCookie = ocmCheckCookie(); 
                parseInt(savedCookie['regular']) ? r = parseInt(savedCookie['regular']) : r = 0;
                parseInt(savedCookie['electric']) ? e = parseInt(savedCookie['electric']) : e = 0;
                if (e || r ){
                    var sum = e + r +' ';
                } else {
                    var sum = '';
                }
                var sums = cal_sum_cookies(savedCookie);
                if (sums['participants'] < sum) {
                    $("#ocm-warning-bikes-container").empty();
                    $("#ocm-warning-bikes-container").append(
                        '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('Bikes number can not be more than participants number','wm-child-cyclando'); ?></div>'
                    );
                    $('.oc-add-btn[name="regular-bikes"]').addClass('disable');
                    $('.oc-add-btn[name="electric-bikes"]').addClass('disable');
                    savedCookie[type] = num - 1;
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                }
                else {
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    $("#ocm-warning-bikes-container").empty();
                    if (sums['participants'] <= sum) { 
                        $('.oc-add-btn[name="regular-bikes"]').addClass('disable');
                        $('.oc-add-btn[name="electric-bikes"]').addClass('disable');
                    } else {
                        $('.oc-add-btn[name="regular-bikes"]').removeClass('disable');
                        $('.oc-add-btn[name="electric-bikes"]').removeClass('disable');
                    }
                    $('#oc-acquista-route .cy-btn-contact').bind('click', ocProceedToExtraHandler);
                    $('#oc-acquista-route .cy-btn-contact').bind('click', ocProceedToReservationHandler);
                    $('#oc-acquista-route .cy-btn-contact').removeClass('noporoceed');
                    num < 0 ? counter.text(0) : counter.text(num);
                    ajaxUpdatePrice();
                }
            };
        });
        

    })(jQuery);
    </script>
    <!-- END HTML modal for bikes btn-->
    <?php


    echo ob_get_clean();
}

