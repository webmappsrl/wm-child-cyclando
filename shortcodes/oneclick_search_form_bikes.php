<?php 

add_shortcode( 'oneclick_search_form_bikes', 'oneclick_search_form_bikes' );
  
function oneclick_search_form_bikes() {

    ob_start();


    ?>
   <div id="oc-bikes" class="oc-input-btn"><span id="ocm-bikes-number"></span><?= __('Bikes','wm-child-cyclando'); ?></div>
	


    <!-- HTML modal for bikes btn One Click Modal OCM -->
    <div id="oc-bikes-modal" class="ocm-bikes-container">
        <div class="ocm-bikes-content">
            <div class="ocm-bikes-header">
                <div class="">
                    <h2><?php echo __('Bicycles','wm-child-cyclando'); ?></h2>
                </div>
                <div class="ocm-close-button-container"><span class="ocm-bikes-close">&times;</span></div>
            </div>
            <div class="ocm-bikes-body">
                <div class="regular-label"><i class="wm-icon-cyc_bici"></i><?php echo __('Regular','wm-child-cyclando'); ?></div>
                <button  class="modal-btn oc-substract-btn" name="regular-bikes"><i class="fas fa-minus"></i></button>
                <div id="regular-bikes" class="oc-number-input">0</div>
                <button  class="modal-btn oc-add-btn" name="regular-bikes"><i class="fas fa-plus"></i></button>
            </div>
            <div class="ocm-bikes-body">
                <div class="ebike-label"><i class="wm-icon-cyc_e-bike"></i><?php echo __('Ebike','wm-child-cyclando'); ?></div>
                <button  class="modal-btn oc-substract-btn" name="electric-bikes"><i class="fas fa-minus"></i></button>
                <div id="electric-bikes" class="oc-number-input">0</div>
                <button class="modal-btn oc-add-btn" name="electric-bikes"><i class="fas fa-plus"></i></button>
            </div>
            <div id="ocm-warning-bikes-container" class="ocm-warning-bike-container"></div>
            <div id="oc-bikes-done-btn" class="oc-bikes-done-btn"><?= __('Done','wm-child-cyclando')?></div>
        </div>
    </div>

    <script>
    (function ($) {
        $(document).ready(function () {
            if (Cookies.get('oc_participants_cookie')) {
                var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie')); 
                if (parseInt(savedCookie['electric'])>0) {
                    $('#electric-bikes').text(parseInt(savedCookie['electric']));
                }
                if (parseInt(savedCookie['regular'])>0) {
                    $('#regular-bikes').text(parseInt(savedCookie['regular']));
                }
            } else {
                var savedCookie = {};
            }

            //Add button
            $( ".oc-add-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'regular-bikes' || $(e.target).attr('name') == 'electric-bikes') {
                        var counter = $('#'+$(e.target).attr('name'));
                        var count = parseInt(counter.text());
                        counter.text(count +1);
                        num = count + 1;
                    }
                    if ($(e.target).attr('name') == 'regular-bikes') {
                        savedCookie['regular'] = num;
                    }
                    if ($(e.target).attr('name') == 'electric-bikes') {
                        savedCookie['electric'] = num;
                    }
                });
            });
            //Substract button
            $( ".oc-substract-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    if ($(e.target).attr('name') == 'regular-bikes' || $(e.target).attr('name') == 'electric-bikes') {
                        var counter = $('#'+$(e.target).attr('name'));
                        var count = parseInt(counter.text());
                        count = count - 1;
                        count < 0 ? counter.text(0) : counter.text(count);
                        console.log(count);
                    }
                    if ($(e.target).attr('name') == 'regular-bikes') {
                        if (count > 0 ) {
                            savedCookie['regular'] = count;
                        } else {
                            delete savedCookie['regular'];
                        }
                    }
                    if ($(e.target).attr('name') == 'electric-bikes') {
                        if (count > 0 ) {
                            savedCookie['electric'] = count;
                        } else {
                            delete savedCookie['electric'];
                        }
                    }
                });
            });
            

            $('#oc-bikes').on('click',function(){
                $('.ocm-bikes-container').show();
            });
            $('.ocm-bikes-close').on('click',function(){
                $('.ocm-bikes-container').hide();
            });

            $('.oc-bikes-done-btn').click( function(){
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
                    console.log('savedCookie'+JSON.stringify(savedCookie));
                }
                else if  (savedCookie) {
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    $("#ocm-warning-bikes-container").empty();
                    $('.ocm-bikes-container').hide();
                    $('#ocm-bikes-number').text(sum);
                    console.log('savedCookie'+JSON.stringify(savedCookie));
                } else {
                    Cookies.set('oc_participants_cookie', JSON.stringify(bCookie), { expires: 7, path: '/' });
                    $("#ocm-warning-bikes-container").empty();
                    $('.ocm-bikes-container').hide();
                    $('#ocm-bikes-number').text(sum);
                }
            });
        });
        

    })(jQuery);
    </script>
    <!-- END HTML modal for bikes btn-->
    <?php


    echo ob_get_clean();
}

