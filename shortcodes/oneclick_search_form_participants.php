<?php 

add_shortcode( 'oneclick_search_form_participants', 'oneclick_search_form_participants' );
  
function oneclick_search_form_participants() {

    ob_start();


    ?>
    
    <div id="oc-participants" class="oc-input-btn"><span id="ocm-partecipants-number"></span><?= __('Participants','wm-child-cyclando'); ?></div>


    <!-- HTML modal for participants btn One Click Modal OCM -->
    <div id="oc-participants-modal" class="ocm-participants-container">
        <div class="ocm-participants-content">
            <div class="ocm-participants-header">
                <div class="">
                    <h2><?php echo __('Participants','wm-child-cyclando'); ?></h2>
                </div>
                <div class="ocm-close-button-container"><span class="ocm-participants-close">&times;</span></div>
            </div>
            <div class="ocm-participants-body">
                <div class="adult-label"><?php echo __('Adults','wm-child-cyclando'); ?></div>
                <button  class="modal-btn oc-substract-btn" name="adult-participants"><i class="fas fa-minus"></i></button>
                <div id="adult-participants" class="oc-number-input">0</div>
                <button  class="modal-btn oc-add-btn" name="adult-participants"><i class="fas fa-plus"></i></button>
            </div>
            <div class="ocm-participants-body">
                <div class="kid-label"><?php echo __('Kids','wm-child-cyclando'); ?></div>
                <button  class="modal-btn oc-substract-btn" name="kid-participants"><i class="fas fa-minus"></i></button>
                <div id="kid-participants" class="oc-number-input">0</div>
                <button class="modal-btn oc-add-btn" name="kid-participants"><i class="fas fa-plus"></i></button>
            </div>
            <div id="ocm-warning-container" class="ocm-warning-container"></div>
            <div id="oc-age-text-container" class="oc-age-text-container"></div>
            <div id="oc-kid-age-container" class="oc-kid-age-container"></div>
            <div id="oc-participants-done-btn" class="oc-participants-done-btn"><?= __('Done','wm-child-cyclando')?></div>
        </div>
    </div>

    <script>
    (function ($) {
        $(document).ready(function () {
            if (Cookies.get('oc_participants_cookie')) {
                var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie'));
                if (parseInt(savedCookie['adults'])>0) {
                    $('#adult-participants').text(parseInt(savedCookie['adults']));
                } else {
                    savedCookie['adults'] = 2;
                    $('#adult-participants').text(2);
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                }
                if (parseInt(savedCookie['kids'])>0) {
                    $('#kid-participants').text(parseInt(savedCookie['kids']));
                    $.each(savedCookie['ages'],function(index,value){
                        $("#oc-kid-age-container").append(
                        '<div class="oc-kid-age-input-wrapper col-4"><select id="oc-kid-age-select-'+index+'" class="oc-kid-age-select"></select></div>'
                        );
                        ocmSetAgeSelectOptions(index);
                        $('#oc-kid-age-select-'+index).val(value);
                    });
                }
                var sums = cal_sum_cookies(savedCookie);
                if (sums['participants'] !== null) {
                    $('#ocm-partecipants-number').html(sums['participants'] + ' ');
                    $("#oc-participants").addClass('selected');
                } 
                if (sums['bikes'] !== null) {
                    $('#ocm-bikes-number').html(sums['bikes'] + ' ');
                    $("#oc-bikes").addClass('selected');
                }
            }
            //Add button
            $( ".oc-add-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    var counter = $('#'+$(e.target).attr('name'));
                    var count = parseInt(counter.text());
                    if ($(e.target).attr('name') == 'adult-participants') {
                        num = count + 1;
                        counter.text(count +1);
                        savedCookie['adults'] = num;
                        $("#ocm-warning-container").empty();
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    }
                    if ($(e.target).attr('name') == 'kid-participants') {
                        if (count + 1 <= parseInt($('#adult-participants').text()) * 3) {
                            num = count + 1;
                            counter.text(count +1);
                            $("#oc-kid-age-container").append(
                                '<div class="oc-kid-age-input-wrapper col-4"><select id="oc-kid-age-select-'+num+'" class="oc-kid-age-select"></select></div>'
                            );
                            if ($("#oc-age-text-container").children().length == 0) {
                                $("#oc-age-text-container").append(
                                    '<div class="oc-age-text-wrapper"><?php echo __('Age of the children on the day of departure','wm-child-cyclando'); ?></div>'
                                );
                            }
                            ocmSetAgeSelectOptions(num);
                            savedCookie['kids'] = num;
                            if (!savedCookie['ages']) {
                                savedCookie['ages'] = {}; 
                            }
                            savedCookie['ages'][num] = 1;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                            //Age Select
                            $('#oc-kid-age-select-'+num).on('change', function(e) {
                                savedCookie = ocmCheckCookie(); 
                                console.log(e.target.id)
                                savedCookie['ages'][e.target.id.split('-').pop()] = parseInt(this.value);
                                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                            });
                            $("#ocm-warning-container").empty();
                        } else {
                            $("#ocm-warning-container").empty();
                            $("#ocm-warning-container").append(
                                '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('Kids number can not be more than 3 times of adults','wm-child-cyclando'); ?></div>'
                            );
                        }
                    }
                });
            });
            
            //Substract button
            $( ".oc-substract-btn" ).each(function(index,element) {
                $(element).click( function(e){
                    savedCookie = ocmCheckCookie(); 
                    var counter = $('#'+$(e.target).attr('name'));
                    var count = parseInt(counter.text());
                    count = count - 1;
                    if ($(e.target).attr('name') == 'adult-participants') {
                        if (count < 1) {
                            counter.text(1);
                            savedCookie['adults'] = 1;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                        } else { 
                            counter.text(count);
                            savedCookie['adults'] = count;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                        }
                        $("#ocm-warning-container").empty();
                    }
                    if ($(e.target).attr('name') == 'kid-participants') {
                        if (count <= parseInt($('#adult-participants').text()) * 3) {
                            count < 0 ? counter.text(0) : counter.text(count);
                            savedCookie['kids'] = count;
                            $(".oc-kid-age-input-wrapper").last().remove();
                            delete savedCookie['ages'][Object.keys(savedCookie['ages']).pop()];
                            if ($(".oc-kid-age-input-wrapper").children().length == 0) {
                                $("#oc-age-text-container").empty();
                            }
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                            $("#ocm-warning-container").empty();
                        } else {
                            $("#ocm-warning-container").empty();
                            $("#ocm-warning-container").append(
                                '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('Kids number can not be more than 3 times of adults','wm-child-cyclando'); ?></div>'
                            );
                        }
                    }
                    if (count < 0 ) {
                        delete savedCookie['kids'];
                    }
                });
            });
            
    
            $('#oc-participants').on('click',function(){
                $('.ocm-participants-container').show();
            });
            $('.ocm-participants-close').on('click',function(){
                $('.ocm-participants-container').hide();
            });
    
            $('.oc-participants-done-btn').click( function(){
                // console.log(pCookie);
                // var string = JSON.stringify(pCookie); 
                // console.log(parseInt(pCookie['kids']));
                // console.log(parseInt(pCookie['adults']));
                // var string = JSON.stringify(pCookie); 
                // console.log(string);
                savedCookie = ocmCheckCookie(); 
                parseInt(savedCookie['kids']) ? k = parseInt(savedCookie['kids']) : k = 0;
                parseInt(savedCookie['adults']) ? a = parseInt(savedCookie['adults']) : a = 0;
                if (a || k ){
                    var sum = a + k +' ';
                } else {
                    var sum = '';
                }
                var sums = cal_sum_cookies(savedCookie);
                console.log('sums'+JSON.stringify(sums));
                if (!("adults" in savedCookie)) {
                    $("#ocm-warning-container").append(
                        '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('There should be at least one adult','wm-child-cyclando'); ?></div>'
                    );
                } else if (parseInt(savedCookie['kids'])/3 > parseInt(savedCookie['adults'])) {
                    $("#ocm-warning-container").append(
                        '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('Kids number can not be more than 3 times of adults','wm-child-cyclando'); ?></div>'
                    );
                } else if (parseInt(sums['bikes']) > parseInt(sums['participants'])) {
                    $("#ocm-warning-container").append(
                        '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('Bikes number can not be more than participants','wm-child-cyclando'); ?></div>'
                    );
                } else if (savedCookie) {
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    console.log('savedcookie'+JSON.stringify(savedCookie));
                    $('.ocm-participants-container').hide();
                    $('#ocm-partecipants-number').text(sum);
                    $("#ocm-warning-container").empty();
                } else {
                    alert('Scegli i partecipanti');
                }
                if (sum) {
                    $("#oc-participants").addClass('selected');
                } else {
                    $("#oc-participants").removeClass('selected');
                }
            });
        });


        window.addEventListener('click', outsideClick);
        // Close If Outside Click
        function outsideClick(e) {
            if (e.target.id == 'oc-participants-modal') {
                $('.ocm-participants-container').hide();
            }
            if (e.target.id == 'oc-bikes-modal') {
                $('.ocm-bikes-container').hide();
            }
        }

        function ocmSetAgeSelectOptions(num){
            var select = '';
            for (i=1;i<=17;i++){
                select += '<option value=' + i + '>' + i + ' <?php echo __('years','wm-child-cyclando'); ?> </option>';
            }
            $('#oc-kid-age-select-'+num).html(select);
        }

        
    })(jQuery);
    </script>
    <!-- END HTML modal for participants btn-->
    <?php


    echo ob_get_clean();
}

