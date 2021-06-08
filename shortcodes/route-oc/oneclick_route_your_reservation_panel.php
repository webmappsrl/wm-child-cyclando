<?php

add_shortcode('oneclick_route_your_reservation_panel', 'oneclick_route_your_reservation_panel');

function oneclick_route_your_reservation_panel($atts)
{
    extract(shortcode_atts(array(
        'route' => '',
        'hotel_product_items' => ''
    ), $atts));

    $post_id = get_the_ID();
    $wm_post_id = wm_get_original_post_it($post_id);
    $wm_post_id = $wm_post_id['id'];
    // get the extra fields for extra popup 
    $has_extra = route_has_extra_category($wm_post_id);
    if ($has_extra['bike']) {
        unset($has_extra['bike']);
    }
    if ($has_extra['ebike']) {
        unset($has_extra['ebike']);
    }
    if (defined('ICL_LANGUAGE_CODE')) {
        $language = ICL_LANGUAGE_CODE;
    } else {
        $language = 'it';
    }
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
    
    <?php if ($hotel_product_items || $has_extra) : ?>
        <div class="oc-route-extra-row oc-route-extra-header">
            <div class="oc-route-your-reservation-column-title oc-route-your-reservation-title">
                <h4><?php echo __('Extra', 'wm-child-cyclando'); ?></h4>
            </div>
            <div class="oc-route-extra-column-info oc-route-your-reservation-modify">
                <p id="oc-route-extra-modify"><span><?php echo __('Modify', 'wm-child-cyclando'); ?></span></p>
            </div>
        </div>
    <?php endif; ?>
    <div class="oc-route-extra-row oc-route-extra-details">
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
        <form action="/quote-wc" method="get" id="yourReservationPurchaseFrom">
            <input type="text" name="quotewcname" class="form-input oc-form-name" placeholder="<?php echo __('Name', 'wm-child-cyclando'); ?>">
            <input type="text" name="quotewcsurname" class="form-input oc-form-surname" placeholder="<?php echo __('Surname', 'wm-child-cyclando'); ?>">
            <input type="text" name="quotewcemail" class="form-input oc-form-email" placeholder="<?php echo __('Email', 'wm-child-cyclando'); ?>">
            <div class="purchase-form-checkbox">
                <input type="checkbox" class="checkbox" id="quotewcnewsletter" name="quotewcnewsletter">
                <p class="purchase-form-checkbox-info purchase-form-checkbox-newsletter"><?= __('I would like to subscribe to the newsletter', 'wm-child-cyclando') ?></p>
            </div>
            <div class="purchase-form-checkbox">
                <input type="checkbox" class="checkbox" id="quotewcprivacy" name="quotewcprivacy">
                <p class="purchase-form-checkbox-info purchase-form-checkbox-privacy"><?= __("I have read and accept the terms of the <a href='/privacy'>privacy policy on data processing</a>", 'wm-child-cyclando') ?> <abbr class="required" title="obbligatorio">*</abbr></p>
            </div>
            <div class="purchase-form-checkbox">
                <input type="checkbox" class="checkbox" id="quotewcconditions" name="quotewcconditions">
                <p class="purchase-form-checkbox-info purchase-form-checkbox-conditions"><?= __("I have read and accept the <a href='/privacy'>terms and conditions</a>", 'wm-child-cyclando') ?> <abbr class="required" title="obbligatorio">*</abbr></p>
            </div>
            <input id="quotewclanguage" name="lang" type="hidden" value="<?= $language;?>">
            <div class="error">
                <span></span>
            </div>
            <input type="submit" value="<?= __("Pay", 'wm-child-cyclando') ?>" class="form-submit">
        </form>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                // autocomplete form if exists
                var savedCookies = ocmCheckCookie();
                if (savedCookies['billingname']) {
                    jQuery('.oc-form-name').val(savedCookies['billingname']);
                }
                if (savedCookies['billingsurname']) {
                    jQuery('.oc-form-surname').val(savedCookies['billingsurname']);
                }
                if (savedCookies['billingemail']) {
                    jQuery('.oc-form-email').val(savedCookies['billingemail']);
                }			
				setTimeout(function() {
					if (savedCookies['billingprivacy'] && savedCookies['billingprivacy'] == 'on' && jQuery('#quotewcprivacy').prop("checked") == false) {
						// jQuery('#privacy_policy').trigger( "click" );
						jQuery('#quotewcprivacy').prop('checked', true);
					}
					if (savedCookies['billingconditions'] && savedCookies['billingconditions'] == 'on' && jQuery('#quotewcconditions').prop("checked") == false) {
						// jQuery('#terms_conditions').trigger( "click" );
						jQuery('#quotewcconditions').prop('checked', true);
					}
					if (savedCookies['billingnewsletter'] && savedCookies['billingnewsletter'] == 'on' && jQuery('#quotewcnewsletter').prop("checked") == false) {
						// jQuery('#newsletter_acceptance').trigger( "click" );
						jQuery('#quotewcnewsletter').prop('checked', true);
					}
            	}, 5000);


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
                        quotewcname: {
                            // Definiamo il campo login come obbligatorio
                            required: true,
                            minlength: 3
                        },
                        quotewcsurname: {
                            // Definiamo il campo login come obbligatorio
                            required: true,
                            minlength: 3,
                        },
                        quotewcemail: {
                            required: true,
                            // Definiamo il campo email come un campo di tipo email
                            email: true
                        },
                        quotewcprivacy: {
                            required: true
                        },
                        quotewcconditions: {
                            required: true,
                        }
                    },
                    // Personalizzimao i mesasggi di errore
                    messages: {
                        quotewcname: "Inserisci il nome",
                        quotewcsurname: "Inserisci il cognome",
                        quotewcemail: "Inserisci la tua email",
                        quotewcprivacy: "Accetta la privacy e policy",
                        quotewcconditions: "Accetta i termini e condizioni",
                    },
                    // Settiamo il submit handler per la form
                    submitHandler: function(form) {
                        var savedCookies = ocmCheckCookie();
                        savedCookies['billingname'] = $( ".oc-form-name" ).val();
                        savedCookies['billingsurname'] = $( ".oc-form-surname" ).val();
                        savedCookies['billingemail'] = $( ".oc-form-email" ).val();
                        if ($("#quotewcnewsletter").is(':checked')) {
                            savedCookies['billingnewsletter'] = 'on';
                        }
                        if ($("#quotewcprivacy").is(':checked')) {
                            savedCookies['billingprivacy'] = 'on';
                        }
                        if ($("#quotewcconditions").is(':checked')) {
                            savedCookies['billingconditions'] = 'on';
                        }
                        savedCookies['routePermalink'] = window.location.href;
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookies), { expires: 7, path: '/' });
                        ajaxCreatHubspotDeal(form);
                    }
                });
                // hide and show the plan Tab
                $('#oc-route-your-reservation-modify').on('click', function() {
                    $('.oc-route-mobile-your-reservation-container').hide();
                    $('.oc-route-mobile-search-form-container').show();
                });
                // hide and show extra popup
                $('#oc-route-extra-modify').on('click', function() {
                    $('.oc-route-mobile-your-reservation-container').hide();
                    $('.oc-route-mobile-search-form-container').show();
                    $('.ocm-proceed-container').show();
                });
            });
        })(jQuery);
    </script>
<?php


    echo ob_get_clean();
}
