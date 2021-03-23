<?php 

add_shortcode( 'onclick_route_form_datepicker', 'onclick_route_form_datepicker' );
  
function onclick_route_form_datepicker() {

    ob_start();

    ?>
    <div class="datepicker-holder"><input type="text" id="datepicker" ></div> 
    <script>
    (function ($) {
        $(document).ready(function () {
            $( function() {
                
                // TODO: set the initial valueof datepicker input to the selected date
                $( "#datepicker" ).val("29 Marzo 2021");
                function availableDepartures(date) {
                    dmy = ('0' +date.getDate()).slice(-2) + "-" + ('0' +(date.getMonth()+1)).slice(-2) + "-" + date.getFullYear();
                    if ($.inArray(dmy, departureArrays) != -1) {
                        return [true, "","Available"];
                    } else {
                        return [false,"","unAvailable"];
                    }
                }
                function showOverLay(input){
                    $("#ui-datepicker-div").wrap("<div class='datepicker-div-wrapper'></div>");
                    // $("#ui-datepicker-div").prepend($(".datepicker-header-wrapper"));
                    $(".datepicker-div-wrapper").css("position", "fixed"); 
                    console.log(input);
                    setTimeout(function() {
                        var headerPane = $( input ).datepicker( "widget" ).find( ".ui-datepicker-header" );
                        $( headerPane ).before( 
                            `<div class="ocm-participants-header datepicker-header-wrapper">
                                <div>
                                    <h2><?php echo __('Choose you departure date', 'wm-child-cyclando'); ?></h2>
                                </div>
                                <div class="ocm-close-button-container"><span class="ocm-participants-close">×</span></div>
                            </div>`
                        );
                    }, 1 );
                }
                function removeOverLay(){
                    $("#ui-datepicker-div").unwrap();
                    $(".datepicker-div-wrapper").css("position", "relative"); 
                }
                $.datepicker.regional['it'] = {
                    closeText: 'Chiudi', // set a close button text
                    monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'], // set month names
                    monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'], // set short month names
                    dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'], // set days names
                    dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'], // set short day names
                    dayNamesMin: ['D','L','M','M','G','V','S'], // set more short days names
                };

                $.datepicker.setDefaults($.datepicker.regional['it']);
                $( "#datepicker" ).datepicker({
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    showButtonPanel: false,
                    dateFormat: 'd MM yy',
                    minDate: 7, // 7 days after the current date, current date included
                    //maxDate: "+1Y +10D", // TODO the last date of period
                    beforeShowDay: availableDepartures, // TODO customize the availible dates
                    defaultDate: +7,
                    onSelect: function(dateText, inst) { 
                        var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie')); 
                        savedCookie['departureDate'] = $("#datepicker").datepicker("option", "dateFormat", "dd-mm-yy" ).val();
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                        $("#datepicker").datepicker("option", "dateFormat", "d MM yy" );
                        ajaxUpdatePrice();
                        // TODO: add function to recalculate prices
                    },
                    beforeShow: showOverLay,
                    onClose: removeOverLay,
                });
            });
        });
        

    })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

