	jQuery(document).ready(function () {
		var main_url;
		var filter;
		var pathname = window.location.href;
		var url = new URL(pathname);
		var lang = url.searchParams.get("lang");
		jQuery(".facetwp-facet.facetwp-facet-search_route.facetwp-type-fselect").click(function () {
			var $filter = jQuery(".fs-search input");

			$filter.keyup(function () {
				// Retrieve the input field text
				filter = jQuery(this).val();
			});

		});

		if (lang) {
			main_url = window.location.protocol + "//" + window.location.host + "/" + "cerca/?lang=en&";
		} else {
			main_url = window.location.protocol + "//" + window.location.host + "/" + "cerca?";
		}
		jQuery('#cy-search-lente').click(function () {
			location.href = main_url + "?_dove_vuoi_andare=" + filter;
		});

		jQuery('#vn-menu-search-map').click(function () {
			location.href = main_url + "?_dove_vuoi_andare=" + filter + "&fwp_map=1";
		});



		jQuery(".facetwp-facet.facetwp-facet-search_route.facetwp-type-fselect").click(function () {
			jQuery('input').keydown(function (event) {
				var keycode = (event.key ? event.key : event.which);
				if (keycode == 'Enter') {
					FWP.parse_facets();
					FWP.set_hash();
					jQuery("#cy-search-lente").trigger("click");
				}
			});
		});

		jQuery("a.fixed-ancor-menu").click(function (event) {
			event.preventDefault();
			jQuery("html, body").animate({
				scrollTop: jQuery(jQuery(this).attr("href")).offset().top - 200
			}, 300);
		});

		jQuery("a#proposte").click(function (event) {
			event.preventDefault();
			jQuery("html, body").animate({
				scrollTop: jQuery(jQuery(this).attr("href")).offset().top + 10
			}, 300);
		});

		// Get contact elements
		const generalContactModal = document.querySelector('#cy-general-contact');
		const generalContactModalBtn = document.querySelector('.generalContactForm');
		const generalCloseContactBtn = document.querySelector('#cy-close-contact');
		// Events contactModal
		if (generalContactModalBtn) {
			generalContactModalBtn.addEventListener('click', openGeneralContactModal);
		}
		// generalCloseContactBtn.addEventListener('click', closeGeneralContactModal);
		window.addEventListener('click', outsideClick);


		// Open contact modal
		function openGeneralContactModal() {
			generalContactModal.style.display = 'block';
		}
		// Close contact modal
		function closeGeneralContactModal() {
			generalContactModal.style.display = 'none';
		}
		// Close If Outside Click
		function outsideClick(e) {
			if (e.target == generalContactModal) {
				generalContactModal.style.display = 'none';
			}

		}

		jQuery("#cy-prices-modal").on("scroll", function () {

			if (jQuery("#cy-prices-modal").scrollTop() > 100) {
				jQuery("#cy-prices-modal .cy-modal-header").addClass("price-header-sticky");
			} else {
				jQuery("#cy-prices-modal .cy-modal-header").removeClass("price-header-sticky");
			}

		});


		if (jQuery(window).width() <= 1024 ) {
			// on document load shrinks the social share bar to the left side of the screen
			var mobile_div = jQuery( '.et_social_mobile' );
			mobile_div.fadeToggle( 600 );
			jQuery( '.et_social_mobile_button' ).addClass( 'et_social_active_button' );

			if ( mobile_div.hasClass( 'et_social_opened' ) ) {
				jQuery( '.et_social_mobile_overlay' ).fadeToggle( 600 );
				mobile_div.removeClass( 'et_social_opened' );
				mobile_div.find( '.et_social_networks' ).fadeToggle( 600 );
			}
		}

		jQuery(document).on("scroll", function () {
			var page_content = document.getElementById("page-content");
			var recaptchaBadge = document.querySelector('.grecaptcha-badge');

			if (jQuery(document).scrollTop() >= 200 ) {
				jQuery(".l-subheader.at_bottom.width_full").addClass("l-subheader-sticky-mobile");
			} else {
				jQuery(".l-subheader.at_bottom.width_full").removeClass("l-subheader-sticky-mobile");
			}
			if (jQuery(window).width() <= 900 ) {
				
				if (jQuery(document).scrollTop() >= 1100) {
					jQuery("#webmapp-layer-container").addClass("container-sticky");
					page_content.classList.add("with_padding");
				} else {
					jQuery("#webmapp-layer-container").removeClass("container-sticky");
					page_content.classList.remove("with_padding");
				}

				if (jQuery(document).scrollTop() >= 500) {
					recaptchaBadge.style.display = 'none';
					jQuery('.et_social_mobile_button').addClass('move');

				} else {
					recaptchaBadge.style.display = 'block';
					jQuery('.et_social_mobile_button').removeClass('move');

				}

			} else {
				jQuery('.et_social_mobile_button').removeClass('move');
				if (jQuery(document).scrollTop() >= 680) {
					jQuery("#webmapp-layer-container").addClass("container-sticky");
					// page_content.classList.add("with_padding");
				} else {
					jQuery("#webmapp-layer-container").removeClass("container-sticky");
					// page_content.classList.remove("with_padding");
				
				}
			}
			
		});

		//Studio samo add class to Contact form success email message
		document.addEventListener( 'wpcf7mailsent', function( event ) {
			jQuery('.wpcf7-response-output').attr('id', 'richiesta-informazioni');
		}, false );

		// window.onscroll = function() {myFunction()};

		// 	// var header = document.getElementById("webmapp-layer-1");
		// 	// var header2 = document.getElementById("webmapp-layer-2");
		// 	var sticky = header.offsetTop;

		// 	function myFunction() {
		// 	if (window.pageYOffset > sticky) {
		// 		// header.classList.add("sticky");
		// 		// header2.classList.add("sticky2");
		// 	} else {
		// 		// header.classList.remove("sticky");
		// 		// header2.classList.remove("sticky2");
		// 	}
		// }
	});