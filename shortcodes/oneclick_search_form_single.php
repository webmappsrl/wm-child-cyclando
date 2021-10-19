<?php 

add_shortcode( 'oneclick_search_form_single', 'oneclick_search_form_single' );
  
function oneclick_search_form_single($atts) {
    extract( shortcode_atts( array(
        'route' => '',
        'has_single' => '',
    ), $atts ) );
    ob_start();


    ?>
    <div id="oc-single" class="oc-input-btn"><span id="ocm-single-number"></span><?= __('Single','wm-child-cyclando'); ?></div>

    <!-- HTML modal for single room btn One Click Modal OCM -->
    <div id="oc-single-modal" class="ocm-single-container">
        <div class="ocm-single-content">
            <div class="ocm-single-header">
                <div class="">
                    <h2><?php echo __('Single rooms','wm-child-cyclando'); ?></h2>
                </div>
                <div class="ocm-close-button-container"><span class="ocm-single-close">&times;</span></div>
            </div>
            <?php if ($route): ?>
                <div class="ocm-single-body">
                    <div class="single-label"><?php echo __('Single','wm-child-cyclando'); ?></div>
                    <button  class="modal-btn oc-substract-btn" name="single_room_paid"><i class="fas fa-minus"></i></button>
                    <div id="single_room_paid" class="oc-number-input">0</div>
                    <button  class="modal-btn oc-add-btn" name="single_room_paid"><i class="fas fa-plus"></i></button>
                </div>
            <?php endif; ?>
            <div id="ocm-warning-single-container" class="ocm-warning-single-container"></div>
            <div id="oc-single-done-btn" class="oc-single-done-btn"><?= __('Done','wm-child-cyclando')?></div>
        </div>
    </div>

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
            $('#oc-single').on('click',()=>{
                var savedCookie = ocmCheckCookie(); 
                if (!savedCookie['kids']) { 
                    $('.ocm-single-container').show();
                    rooms = [];
                    rooms = calculateSingleRoomNum(savedCookie['adults']);
                }
            });

            $('.ocm-single-close').on('click',function(){
                $('.ocm-single-container').hide();
            });
            

            //Add button
            $( ".oc-add-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'single_room_paid') {
                        var counter = $('#'+$(e.target).attr('name'));
                        var count = counter.text();
                        var nextIndex = $.inArray(count, rooms) + 1;
                        if (nextIndex < rooms.length) {
                            val = rooms[nextIndex];
                            counter.text(val);
                            num = parseInt(val);
                        }
                        else {
                            console.log("No More Item Exist add");
                        }
                        

                        if (num != 0 ) {
                            savedCookie[post_id]['extra']['single_room_paid'] = num;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } else {
                            console.warn('Sono in Add button');
                            delete savedCookie[post_id]['extra']['single_room_paid'];
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        }
                    }
                });
            });
            //Substract button
            $( ".oc-substract-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'single_room_paid') {
                        var counter = $('#'+$(e.target).attr('name'));
                        var count = counter.text();

                        var prevIndex = $.inArray(count, rooms) - 1;
                        if (prevIndex >= 0) {
                            val = rooms[prevIndex];
                            counter.text(val);
                            num = parseInt(val);
                        }
                        else {
                            console.log("No More Item Exist Sub");
                        }
                        


                        if (num != 0 ) {
                            savedCookie[post_id]['extra']['single_room_paid'] = num;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } else {
                            console.warn('Sono in SUB button');
                            delete savedCookie[post_id]['extra']['single_room_paid'];
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        }
                    }
                });
            });
            

            $('.oc-single-done-btn').click( function(){
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
                    $('.ocm-single-container').hide();
                    $('#ocm-single-number').text(sum);
                    <?php if ($route) { ?>
                    ajaxUpdatePrice();
                    <?php } ?>
                } else {
                    $("#ocm-warning-single-container").empty();
                    $('.ocm-single-container').hide();
                    $('#ocm-single-number').text(sum);
                }
                if (sum) {
                    $("#oc-single").addClass('selected');
                } else {
                    $("#oc-single").removeClass('selected');
                }
            });
        });
        

    })(jQuery);
    </script>
    <!-- END HTML modal for single btn-->
    <?php


    echo ob_get_clean();
}

