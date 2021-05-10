<?php 

add_shortcode( 'oneclick_route_form_datepicker', 'oneclick_route_form_datepicker' );
  
function oneclick_route_form_datepicker() {

    ob_start();

    ?>
    <div class="datepicker-holder"><input type="text" id="datepicker" ></div> 
    <script>
    (function ($) {
        $(document).ready(function () {
            $( function() {
                var savedCookie = ocmCheckCookie();
                if (savedCookie['departureDate']) {
                    var Cookiedate = savedCookie['departureDate'].split('-');
                    var monthNames = {'01':'Gennaio','02':'Febbraio','03':'Marzo','04':'Aprile','05':'Maggio','06':'Giugno','07':'Luglio','08':'Agosto','09':'Settembre','10':'Ottobre','11':'Novembre','12':'Dicembre'}
                    $( "#datepicker" ).val(Cookiedate[0]+" "+monthNames[Cookiedate[1]]+" "+Cookiedate[2]);
                    $('#oc-route-your-reservation-departure').html(Cookiedate[0]+" "+monthNames[Cookiedate[1]]+" "+Cookiedate[2]);
                } else {
                    $( "#datepicker" ).val(first_departure_date_ajax);
                    console.log('#datepicker first_departure_date_ajax ' + first_departure_date_ajax);
                    $('#oc-route-your-reservation-departure').html(first_departure_date_ajax);
                }

                function availableDepartures(date) {
                    dmy = ('0' +date.getDate()).slice(-2) + "-" + ('0' +(date.getMonth()+1)).slice(-2) + "-" + date.getFullYear();
                    if ($.inArray(dmy, departureArrays) != -1) {
                        return [true, "","Available"];
                    } else {
                        return [false,"","unAvailable"];
                    }
                }
                function showOverLay(input){
                    $("#ui-datepicker-div").wrap("<div class='datepicker-div-wrapper'><div class='datepicker-content-wrapper'></div></div>");
                    $(".datepicker-div-wrapper").css("position", "fixed"); 
                    setTimeout(function() {
                        $( ".datepicker-div-wrapper .datepicker-content-wrapper" ).prepend( 
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
                    $(".datepicker-content-wrapper").unwrap();
                    $("#ui-datepicker-div").unwrap();
                    $(".datepicker-div-wrapper").css("position", "relative"); 
                    $( ".datepicker-div-wrapper .datepicker-content-wrapper" ).remove( ".datepicker-header-wrapper" );
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
                        var savedCookie = ocmCheckCookie();
                        console.log(savedCookie);
                        savedCookie['departureDate'] = $("#datepicker").datepicker("option", "dateFormat", "dd-mm-yy" ).val();
                        console.log(savedCookie['departureDate']);
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                        $("#datepicker").datepicker("option", "dateFormat", "d MM yy" );
                        $('#oc-route-your-reservation-departure').html(dateText);
                        ajaxUpdatePrice();
                    },
                    beforeShow: showOverLay,
                    onClose: removeOverLay,
                }).attr('readonly','readonly');


                // set the initial valueof datepicker input to the selected date
                var savedCookie = ocmCheckCookie();
                if (!savedCookie['departureDate']) {
                    savedCookie['departureDate'] = $("#datepicker").datepicker("option", "dateFormat", "dd-mm-yy" ).val();
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    $("#datepicker").datepicker("option", "dateFormat", "d MM yy" );
                }
                ajaxUpdatePrice();
            });
        });
        

    })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

