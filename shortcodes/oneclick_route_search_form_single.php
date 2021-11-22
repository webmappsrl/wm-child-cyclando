<?php 

add_shortcode( 'oneclick_route_search_form_single', 'oneclick_route_search_form_single' );
  
function oneclick_route_search_form_single($atts) {
    extract( shortcode_atts( array(
        'route' => '',
        'has_single' => '',
    ), $atts ) );
    ob_start();


    ?>
    <?php if ($route): ?>
        <div class="ocm-single-body">
            <div class="single-label"><?php echo __('Single','wm-child-cyclando'); ?></div>
            <button  class="modal-btn oc-substract-btn" name="single_room_paid"><i class="fal fa-minus"></i></button>
            <div id="single_room_paid" class="oc-number-input">0</div>
            <button  class="modal-btn oc-add-btn" name="single_room_paid"><i class="fal fa-plus"></i></button>
        </div>
    <?php endif; ?>

    <script>
    (function ($) {
        $(document).ready(function () {
            var savedCookie = ocmCheckCookie();
            
            if ( !savedCookie[post_id]) {
                savedCookie[post_id] = {};
                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
            }
            if ( !savedCookie[post_id]['extra']) {
                savedCookie[post_id]['extra'] = {};
                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
            }
            if ( !savedCookie[post_id]['supplement']) {
                savedCookie[post_id]['supplement'] = {};
                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
            }

            if ( !!savedCookie[post_id]['extra']['single_room_paid'] || parseInt(savedCookie[post_id]['extra']['single_room_paid'])>0) {
                $('#single_room_paid').text(parseInt(savedCookie[post_id]['extra']['single_room_paid']));
            }

            if (savedCookie['kids'] && savedCookie['kids'] > 0) { 
                disableSinglebtn();
            } else {
                enableSinglebtn();
            }
            // $('#oc-single').on('click',()=>{
            //     var savedCookie = ocmCheckCookie(); 
            //     if (!savedCookie['kids']) { 
            //         $('.ocm-single-container').show();
            //         rooms = [];
            //         rooms = calculateSingleRoomNum(savedCookie['adults']);
            //     }
            // });

            $('.ocm-single-close').on('click',function(){
                $('.ocm-single-container').hide();
            });
            

            //Add button
            $( ".oc-add-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'single_room_paid') {
                        var counter = $('#'+$(e.target).attr('name'));
                        console.log(counter);
                        var count = counter.text();
                        console.log('count ' + count);
                        var nextIndex = $.inArray(count, rooms) + 1;
                        console.log('nextIndex ' + nextIndex);
                        console.log('rooms.length ' + rooms.length);
                        if (nextIndex < rooms.length) {
                            val = rooms[nextIndex];
                            console.log('val ' + val);
                            counter.text(val);
                            num = parseInt(val);
                        }
                        else {
                        }
                        console.log('più' + num);
                        

                        if (num != 0 ) {
                            savedCookie[post_id]['extra']['single_room_paid'] = num;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } else {
                            delete savedCookie[post_id]['extra']['single_room_paid'];
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        }
                        updatePriceOnSingleChange();
                    }
                });
            });
            //Substract button
            $( ".oc-substract-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'single_room_paid') {
                        console.log(rooms);
                        var counter = $('#'+$(e.target).attr('name'));
                        var count = counter.text();

                        var prevIndex = $.inArray(count, rooms) - 1;
                        if (prevIndex >= 0) {
                            val = rooms[prevIndex];
                            counter.text(val);
                            num = parseInt(val);
                        }
                        else {
                        }
                        console.log('meno' + num);
                        if (num != 0 ) {
                            savedCookie[post_id]['extra']['single_room_paid'] = num;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } else {
                            delete savedCookie[post_id]['extra']['single_room_paid'];
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        }
                        updatePriceOnSingleChange();
                    }
                });
            });
            

            function updatePriceOnSingleChange(){
                savedCookie = ocmCheckCookie(); 
                parseInt(savedCookie[post_id]['extra']['single_room_paid']) ? r = parseInt(savedCookie[post_id]['extra']['single_room_paid']) : r = 0;
                if ( r > 0){
                    var sum = r +' ';
                } else {
                    var sum = '';
                }
                var sums = cal_sum_cookies(savedCookie);
                if  (savedCookie) {
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    $("#ocm-warning-single-container").empty();
                    <?php if ($route) { ?>
                    ajaxUpdatePrice();
                    <?php } ?>
                } else {
                    $("#ocm-warning-single-container").empty();
                }
                if (sum) {
                    $("#oc-single").addClass('selected');
                } else {
                    $("#oc-single").removeClass('selected');
                }
            };
        });
        

    })(jQuery);
    </script>
    <!-- END HTML modal for single btn-->
    <?php


    echo ob_get_clean();
}

