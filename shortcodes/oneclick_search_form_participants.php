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
            <div id="oc-age-text-container" class="oc-age-text-container"></div>
            <div id="oc-kid-age-container" class="oc-kid-age-container"></div>
            <div id="oc-participants-done-btn" class="oc-participants-done-btn"><?= __('Done','wm-child-cyclando')?></div>
        </div>
    </div>

    <script>
    (function ($) {
        var pCookie = {};
        //Add button
        $( ".oc-add-btn" ).each(function(index,element) {
            $(element).click( function(e){
                var counter = $('#'+$(e.target).attr('name'));
                var count = parseInt(counter.text());
                counter.text(count +1);
                num = count + 1;
                if ($(e.target).attr('name') == 'adult-participants') {
                    pCookie['adults'] = num;
                }
                if ($(e.target).attr('name') == 'kid-participants') {
                    $("#oc-kid-age-container").append(
                        '<div class="oc-kid-age-input-wrapper col-4"><select id="oc-kid-age-select-'+num+'" class="oc-kid-age-select"></select></div>'
                    );
                    if ($("#oc-age-text-container").children().length == 0) {
                        $("#oc-age-text-container").append(
                            '<div class="oc-age-text-wrapper"><?php echo __('Age of the children on the day of departure','wm-child-cyclando'); ?></div>'
                        );
                    }
                    var select = '';
                    for (i=1;i<=17;i++){
                        select += '<option value=' + i + '>' + i + ' <?php echo __('years','wm-child-cyclando'); ?> </option>';
                    }
                    $('#oc-kid-age-select-'+num).html(select);
                    pCookie['kids'] = num;
                    if (!pCookie['ages']) {
                        pCookie['ages'] = {}; 
                    }
                    pCookie['ages'][num] = 1;
                    //Age Select
                    $('#oc-kid-age-select-'+num).on('change', function(e) {
                        console.log(e.target.id)
                        pCookie['ages'][e.target.id.split('-').pop()] = parseInt(this.value);
                    });
                }
            });
        });
        //Substract button
        $( ".oc-substract-btn" ).each(function(index,element) {
            $(element).click( function(e){
                var counter = $('#'+$(e.target).attr('name'));
                var count = parseInt(counter.text());
                count = count - 1;
                count < 0 ? counter.text(0) : counter.text(count);
                console.log(count);
                if ($(e.target).attr('name') == 'adult-participants') {
                    if (count > 0 ) {
                        pCookie['adults'] = count;
                    } else {
                        delete pCookie['adults'];
                    }
                }
                if ($(e.target).attr('name') == 'kid-participants') {
                    if (count > 0 ) {
                        pCookie['kids'] = count;
                    } else {
                        delete pCookie['kids'];
                    }
                }
                if ($(e.target).attr('name') == 'kid-participants') {
                    $(".oc-kid-age-input-wrapper").last().remove();
                    delete pCookie['ages'][Object.keys(pCookie['ages']).pop()];
                    if ($(".oc-kid-age-input-wrapper").children().length == 0) {
                        $("#oc-age-text-container").empty();
                    }
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
            parseInt(pCookie['kids']) ? k = parseInt(pCookie['kids']) : k = 0;
            parseInt(pCookie['adults']) ? a = parseInt(pCookie['adults']) : a = 0;
            if (a || k ){
                var sum = a + k +' ';
            } else {
                var sum = '';
            }
            if (!("adults" in pCookie)) {
                alert('Ci deve essere almeno un adulto');
                $('#ocm-partecipants-number').text(sum);
            } else if (parseInt(pCookie['kids'])/3 > parseInt(pCookie['adults'])) {
                alert('Ci devono essere più adulti');
                $('#ocm-partecipants-number').text(sum);
            } else if (Cookies.get('oc_participants_cookie')) {
                var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie')); 
                $.extend(true,savedCookie,pCookie);
                Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
            } else if (pCookie) {
                Cookies.set('oc_participants_cookie', JSON.stringify(pCookie), { expires: 7, path: '/' });
            } else {
                alert('Scegli i partecipanti');
            }
            $('.ocm-participants-container').hide();
            $('#ocm-partecipants-number').text(sum);
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
    })(jQuery);
    </script>
    <!-- END HTML modal for participants btn-->
    <?php


    echo ob_get_clean();
}

