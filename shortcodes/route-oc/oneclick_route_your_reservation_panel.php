<?php

add_shortcode('oneclick_route_your_reservation_panel', 'oneclick_route_your_reservation_panel');

function oneclick_route_your_reservation_panel($atts)
{
    extract(shortcode_atts(array(
        'route' => '',
    ), $atts));


    ob_start();

?>
    <div class="oc-route-your-reservation-row oc-route-your-reservation-header">
        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-title">
            <h4><?php echo __('Your reservation', 'wm-child-cyclando'); ?></h4>
        </div>
        <div class="oc-route-your-reservation-column-info oc-route-your-reservation-modify">
            <p id="oc-route-your-reservation-modify"><span><?php echo __('Modify', 'wm-child-cyclando'); ?></span></p>
        </div>
    </div>
    <div class="oc-route-your-reservation-row oc-route-your-reservation-details">

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-departure-title">
            <p><?php echo __('Departure', 'wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info">
            <p id="oc-route-your-reservation-departure"></p>
        </div>

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-participant-title">
            <p><?php echo __('Participants', 'wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info">
            <p id="oc-route-your-reservation-participants"></p>
        </div>

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-bikes-title">
            <p><?php echo __('Bicycles', 'wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info oc-route-your-reservation-bikes-info">
            <p id="oc-route-your-reservation-bikes"></p>
        </div>

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-category-title">
            <p><?php echo __('Category', 'wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info oc-route-your-reservation-category-info">
            <p id="oc-route-your-reservation-category"></p>
        </div>
    </div>

    <div class="oc-route-mobile-plan-summary-container">
        <h4><?= __('Best price for', 'wm-child-cyclando') ?></h4>
        <div class="oc-route-mobile-plan-summary"></div>
    </div>
    <div class="oc-route-mobile-plan-price-container">
        <div class="cifraajax-title"><?= __('Total', 'wm-child-cyclando') ?></div>
        <div class="cifraajax"></div>
    </div>
    

    <div class="oc-route-your-reservation-purchase-form-container">
        <h3 class="oc-route-your-reservation-purchase-form-title"><?php echo __('Purchase', 'wm-child-cyclando'); ?></h3>
        <form action="/quote-wc?add-to-cart=96810:2,120:2,201:2" method="get" id="yourReservationPurchaseFrom">
            <input type="text" name="name" class="form-input oc-form-name" placeholder="<?php echo __('Name', 'wm-child-cyclando'); ?>">
            <input type="text" name="surname" class="form-input oc-form-surname" placeholder="<?php echo __('Surname', 'wm-child-cyclando'); ?>">
            <input type="text" name="email" class="form-input oc-form-email" placeholder="<?php echo __('Email', 'wm-child-cyclando'); ?>">
            <div class="purchase-form-checkbox">
                <input type="checkbox" class="checkbox" id="newsletter" name="newsletter">
                <p class="purchase-form-checkbox-info purchase-form-checkbox-newsletter"><?= __('I would like to subscribe to the newsletter', 'wm-child-cyclando') ?></p>
            </div>
            <div class="purchase-form-checkbox">
                <input type="checkbox" class="checkbox" id="privacy" name="privacy">
                <p class="purchase-form-checkbox-info purchase-form-checkbox-privacy"><?= __("I have read and accept the terms of the <a href='/privacy'>privacy policy on data processing</a>", 'wm-child-cyclando') ?></p>
            </div>
            <div class="purchase-form-checkbox">
                <input type="checkbox" class="checkbox" id="conditions" name="conditions">
                <p class="purchase-form-checkbox-info purchase-form-checkbox-conditions"><?= __("I have read and accept the <a href='/privacy'>terms and conditions</a>", 'wm-child-cyclando') ?></p>
            </div>
            <div class="error" style="">
                <span></span>
            </div>
            <input type="submit" value="Paga" class="form-submit">
        </form>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                // Selezione form e definizione dei metodi di validazione
                $("#yourReservationPurchaseFrom").validate({
                    invalidHandler: function (e, validator) {
                    var errors = validator.numberOfInvalids();
                    if (errors) {
                        var message = errors == 1 ? '<?php echo __('You missed 1 field.', 'wm-child-cyclando'); ?>' : '<?php echo __('You missed', 'wm-child-cyclando'); ?> ' + errors + ' <?php echo __('field.', 'wm-child-cyclando'); ?>';
                        $("div.error span").html(message);
                        $("div.error").show();
                    } else {
                        $("div.error").hide();
                    }
                    },
                    // Definiamo le nostre regole di validazione
                    rules: {
                        // login - nome del campo di input da validare
                        name: {
                            // Definiamo il campo login come obbligatorio
                            required: true,
                            minlength: 3
                        },
                        surname: {
                            // Definiamo il campo login come obbligatorio
                            required: true,
                            minlength: 3,
                        },
                        email: {
                            required: true,
                            // Definiamo il campo email come un campo di tipo email
                            email: true
                        },
                        privacy: {
                            required: true
                        },
                        conditions: {
                            required: true,
                        }
                    },
                    // Personalizzimao i mesasggi di errore
                    messages: {
                        name: "Inserisci il nome",
                        surname: "Inserisci il cognome",
                        email: "Inserisci la tua email",
                        privacy: "Accetta la privacy e policy",
                        conditions: "Accetta i termini e condizioni",
                    },
                    // Settiamo il submit handler per la form
                    submitHandler: function(form) {
                        var savedCookies = ocmCheckCookie();
                        savedCookies['billingname'] = $( ".oc-form-name" ).val();
                        savedCookies['billingsurname'] = $( ".oc-form-surname" ).val();
                        savedCookies['billingemail'] = $( ".oc-form-email" ).val();
                        savedCookies['billingnewsletter'] = $( ".purchase-form-checkbox-newsletter" ).val();
                        savedCookies['billingprivacy'] = $( ".purchase-form-checkbox-privacy" ).val();
                        savedCookies['billingconditions'] = $( ".purchase-form-checkbox-conditions" ).val();
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookies), { expires: 7, path: '/' });
                        alert('submited');
                        form.submit();
                    }
                });
                // hide and show the plan Tab
                $('#oc-route-your-reservation-modify').on('click', function() {
                    $('.oc-route-mobile-your-reservation-container').hide();
                    $('.oc-route-mobile-search-form-container').show();
                });
            });
        })(jQuery);
    </script>
<?php


    echo ob_get_clean();
}
