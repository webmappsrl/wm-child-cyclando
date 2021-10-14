<?php 

add_shortcode( 'oneclick_search_form_participants', 'oneclick_search_form_participants' );
  
function oneclick_search_form_participants($atts) {
    extract( shortcode_atts( array(
        'route' => '',
        'has_kids' => '',
        'min_kid_age' => ''
    ), $atts ) );
    ob_start();
    
    if ($route) {
        ?>
        <div id="oc-participants-adult" class="oc-participants-btn oc-input-btn"><span id="ocm-partecipants-adult-number"></span><?= __('Adults','wm-child-cyclando'); ?></div>
        <?php if ($has_kids) { ?>
        <div id="oc-participants-kid" class="oc-participants-btn oc-input-btn"><span id="ocm-partecipants-kid-number"></span><?= __('Kids','wm-child-cyclando'); ?></div>
        <?php } ?>
        <?php
    } else {
        ?>
        <div id="oc-participants" class="oc-participants-btn oc-input-btn"><span id="ocm-partecipants-number"></span><?= __('Participants','wm-child-cyclando'); ?></div>
        <?php
    }
    ?>

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
            <?php if ($route): ?>
                <?php if ($has_kids ) { ?>
                <div class="ocm-participants-body">
                    <div class="kid-label"><?php echo __('Kids','wm-child-cyclando'); ?></div>
                    <button  class="modal-btn oc-substract-btn" name="kid-participants"><i class="fas fa-minus"></i></button>
                    <div id="kid-participants" class="oc-number-input">0</div>
                    <button class="modal-btn oc-add-btn" name="kid-participants"><i class="fas fa-plus"></i></button>
                </div>
                <?php } ?>
            <?php else: ?>
                <div class="ocm-participants-body">
                    <div class="kid-label"><?php echo __('Kids','wm-child-cyclando'); ?></div>
                    <button  class="modal-btn oc-substract-btn" name="kid-participants"><i class="fas fa-minus"></i></button>
                    <div id="kid-participants" class="oc-number-input">0</div>
                    <button class="modal-btn oc-add-btn" name="kid-participants"><i class="fas fa-plus"></i></button>
                </div>
            <?php endif; ?>
            <div id="ocm-warning-container" class="ocm-warning-container"></div>
            <div id="oc-age-text-container" class="oc-age-text-container"></div>
            <?php if ($route): ?>
                <?php if ($has_kids ) { ?>
                    <div id="oc-kid-age-container" class="oc-kid-age-container"></div>
                <?php } ?>
            <?php else: ?>
                    <div id="oc-kid-age-container" class="oc-kid-age-container"></div>
            <?php endif; ?>
            <div id="oc-participants-done-btn" class="oc-participants-done-btn"><?= __('Done','wm-child-cyclando')?></div>
        </div>
    </div>

    <script>
    (function ($) {
        $(document).ready(function () {
            <?php if ($route) { ?>
            // checks if the kids are not availible and if they are previously selected, adds their value to adults
            var has_kids = <?php echo json_encode($has_kids )?>;
            if (has_kids == 0) {
                var savedCookie = ocmCheckCookie();
                if (savedCookie) {
                    if (savedCookie['kids']) {
                        savedCookie['adults'] += parseInt(savedCookie['kids']);
                    }
                    delete savedCookie['ages'];
                    delete savedCookie['kids'];
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    $("#ocm-warning-container").append(
                                '<div class="oc-age-text-wrapper" style="color:red;"><?php echo __('Kids participation is not available for this route. Their number is added to adults','wm-child-cyclando'); ?></div>'
                            );
                }
            }
            <?php } ?>
            var savedCookie = ocmCheckCookie();
            if (savedCookie) {
                if (parseInt(savedCookie['adults']) > 0) {
                    $('#adult-participants').text(parseInt(savedCookie['adults']));
                    $('#ocm-partecipants-adult-number').text(parseInt(savedCookie['adults']) + ' ');
                    $("#oc-participants-adult").addClass('selected');
                } else {
                    savedCookie['adults'] = 2;
                    $('#adult-participants').text(2);
                    $('#ocm-partecipants-adult-number').text(2 + ' ');
                    $("#oc-participants-adult").addClass('selected');
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                }
                if (parseInt(savedCookie['kids']) > 0) {
                    $('#kid-participants').text(parseInt(savedCookie['kids']));
                    $('#ocm-partecipants-kid-number').text(parseInt(savedCookie['kids']) + ' ');
                    $("#oc-participants-kid").addClass('selected');
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
            } else {
                savedCookie = {};
                savedCookie['adults'] = 2;
                savedCookie['category'] = 0;
                $('#adult-participants').text(2);
                $('#ocm-partecipants-number').html(2 + ' ');
                $("#oc-participants").addClass('selected');
                $('#ocm-partecipants-adult-number').text(2 + ' ');
                $("#ocm-partecipants-adult-number").addClass('selected');
                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
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
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
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
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                            //Age Select
                            $('#oc-kid-age-select-'+num).on('change', function(e) {
                                savedCookie = ocmCheckCookie(); 
                                savedCookie['ages'][e.target.id.split('-').pop()] = parseInt(this.value);
                                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
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
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                        } else { 
                            counter.text(count);
                            savedCookie['adults'] = count;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
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
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
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

            //On Age Select change
            $( ".oc-kid-age-select" ).each(function(index,element) {
                $(element).on('change', function(e){
                    savedCookie = ocmCheckCookie(); 
                    savedCookie['ages'][e.target.id.split('-').pop()] = parseInt(this.value);
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                });
            });


            $('.oc-participants-btn').on('click',function(){
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
                // console.log('sums'+JSON.stringify(sums));
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
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    console.log('savedcookie'+JSON.stringify(savedCookie));
                    $('.ocm-participants-container').hide();
                    $('#ocm-partecipants-number').text(sum);
                    $('#ocm-partecipants-adult-number').text(parseInt(savedCookie['adults']) + ' ');
                    $("#oc-participants-adult").addClass('selected');
                    if (savedCookie['kids'] > 0) {
                        $('#ocm-partecipants-kid-number').text(parseInt(savedCookie['kids']) + ' ');
                        $("#oc-participants-kid").addClass('selected');
                    } else {
                        $('#ocm-partecipants-kid-number').text("");
                        $("#oc-participants-kid").removeClass('selected');
                    }
                    $("#ocm-warning-container").empty();
                    <?php if ($route) { ?>
                    ajaxUpdatePrice();
                    <?php } ?>
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
            var midKidAgeholder = 0;
            var midKidAge = <?php echo json_encode($min_kid_age )?>;
            
            if (midKidAge) {
                midKidAgeholder = midKidAge;
            } else {
                midKidAgeholder = 1;
            }
            for (i=midKidAgeholder;i<=17;i++){
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

