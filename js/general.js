//removes autocomplete dropdown dove_vuoi_andare facet
(function ($) {
  $(document).on("facetwp-loaded", function () {
    const $el = $('.facetwp-facet-dove_vuoi_andare input[type="text"]');
    $el.on("blur keydown input change keyup focus", () => {
      $(".autocomplete-suggestions").remove();
	});
	var facetwpPaged2 = document.querySelectorAll('.facetwp-page');
	if (FWP.loaded) { 
		facetwpPagedScrollTop(facetwpPaged2);
	}
  });
})(jQuery);

function facetwpPagedScrollTop (facetwpPaged){
	facetwpPaged.forEach((button) => {
		button.addEventListener('click', wmScrollTop);
	});
  }
function wmScrollTop() {
	jQuery('html, body').animate({
		scrollTop: jQuery('#page-content').offset().top-40
	}, 1000);
  }

jQuery(window).on('load', function() {
	var facetwpPaged = document.querySelectorAll('.facetwp-page');
	facetwpPagedScrollTop(facetwpPaged);
});

jQuery(document).ready(function () {
  var main_url;
  var filter;
  var pathname = window.location.href;
  var url = new URL(pathname);
  var lang = url.searchParams.get("lang");
  var lenteBanner;
  var input;
  var form;
  lenteBanner = jQuery("#cy-search-lente");
  input = jQuery("#searchform input");
  form = jQuery("#searchform");
  input.attr("tabindex", -1);
  var count = 0;
  zSearch = 0;
  
  

  // Covid banner
  jQuery("<div class='covidbanner'><div class='covidbanner-container'><span> AVVISO PER CHI VIAGGIA: <a href='https://cyclando.com/assicurazione-covid/'>scopri di più sul COVID-19</a></span> <span class='cy-close-covidbanner'>&times;</span></div></div>").insertBefore(".l-canvas.type_wide");
  const covidBanner = document.querySelector('.covidbanner');
  const closeCovidBtn = document.querySelector('.covidbanner .cy-close-covidbanner');
  const wpAdminBar = document.querySelector('#wpadminbar');
  
  if (wpAdminBar) {
	jQuery('html').attr('style','margin-top:70px!important');
	covidBanner.classList.add("with-wp-admin-bar");
  } else {
	jQuery('html').attr('style','margin-top:38px!important');
	covidBanner.classList.add("without-wp-admin-bar");

  }
  closeCovidBtn.addEventListener('click', closeCovidBanner);
  function closeCovidBanner() {
	Cookies.set('wm_covid_banner_cookie', 'wm_covid_banner_visited', { expires: 30, path: '/' });
	clearCookieStyle();
  }
  function clearCookieStyle() {
	covidBanner.style.display = 'none';
	covidBanner.classList.remove("without-wp-admin-bar");
	covidBanner.classList.remove("without-wp-admin-bar");
	jQuery('html').removeAttr('style');
  }
  covidCookieValue = Cookies.get("wm_covid_banner_cookie");
  if (covidCookieValue == 'wm_covid_banner_visited') {
	clearCookieStyle();
  }


  // End covid banner

  
  jQuery(document).one("facetwp-loaded", function () {
    jQuery(".cy-facetwp-cerca-quando").append(jQuery(".cy-facetwp-cerca-where input.facetwp-autocomplete-update"));
    jQuery(".cy-facetwp-cerca-quando input.facetwp-autocomplete-update").html("CERCA");
    jQuery(".cy-facetwp-cerca-quando input.facetwp-autocomplete-update").val("CERCA");
  });

  //mobile and desktop button management on first loading
  if (jQuery(window).width() >= 700) {
    jQuery("#buttonFilterSearch").hide();
    jQuery("#orderSearch").hide();
  } else {
    jQuery("#buttonFilterSearch").show();
    jQuery("#orderSearch").show();
    //dropdown filtra
    jQuery("#buttonFilterSearch").click(function (event) {
      jQuery("#filterSearchDropdown").toggle();
    });
    jQuery("#filterSearchDropdown").hide();
  }

  //mobile and desktop button management after window change
  jQuery(window).on("resize", function () {
    if (jQuery(window).width() >= 700) {
      jQuery("#buttonFilterSearch").hide();
      jQuery("#orderSearch").hide();
      jQuery("#filterSearchDropdown").show();
    } else {
      jQuery("#buttonFilterSearch").show();
      //dropdown filtra
      /*	jQuery('#buttonFilterSearch').click( function(event){
				jQuery('#filterSearchDropdown').toggle();   
				});*/
      jQuery("#filterSearchDropdown").hide();
    }
  });

  // configuration for the search in banner homepahe
  // jQuery(".facetwp-facet.facetwp-facet-search_route.facetwp-type-fselect").click(function () {
  jQuery(
    "#cy-search-element-container .facetwp-facet.facetwp-facet-dove_vuoi_andare.facetwp-type-autocomplete"
  ).click(function () {
    // var $filter = jQuery(".fs-search input");
    var $filter = jQuery("#cy-search-element-container input");
    //#cy-search-lente

    $filter.keyup(function () {
      // Retrieve the input field text
      filter = jQuery(this).val();
      FWP.parse_facets();
      FWP.set_hash();
    });

    jQuery("input").keydown(function (event) {
      var keycode = event.key ? event.key : event.which;
      if (keycode == "Enter") {
        FWP.parse_facets();
        FWP.set_hash();
        lenteBanner.trigger("click");
      }
    });
  });

  if (lang) {
    main_url =
      window.location.protocol +
      "//" +
      window.location.host +
      "/" +
      "cerca/?lang=en&";
  } else {
    main_url =
      window.location.protocol + "//" + window.location.host + "/" + "cerca/";
  }
  lenteBanner.click(function () {
    str = location.search;

    if (location.search === undefined) {
      location.href = main_url;
    } else {
      location.href = main_url + location.search;
    }
  });
  // upon click on menu search icon lente
  jQuery("#vn-search-bar-header .facetwp-btn").click(function (event) {
    // event.preventDefault();
    location.href = main_url + "?_dove_vuoi_andare=" + filter;
  });

  jQuery("#vn-menu-search-map").click(function () {
    location.href = main_url + "?_dove_vuoi_andare=" + filter + "&fwp_map=1";
  });

  form.hover(
    function () {
      jQuery("#searchform").addClass("menu-searchbox-expandable");
      input.addClass("menu-input-expandable");
    },
    function () {
      if (!input.is(":focus")) {
        jQuery("#searchform").removeClass("menu-searchbox-expandable");
        form.removeClass("menu-searchbox-expandable");
        input.removeClass("menu-input-expandable");
      }
    }
  );
  input.focusin(function () {
    jQuery("#searchform").addClass("menu-searchbox-expandable");
    input.addClass("menu-input-expandable");
  });
  input.focusout(function () {
    jQuery("#searchform").removeClass("menu-searchbox-expandable");
    input.removeClass("menu-input-expandable");
  });

  jQuery("a.fixed-ancor-menu").click(function (event) {
    event.preventDefault();
    jQuery("html, body").animate(
      {
        scrollTop: jQuery(jQuery(this).attr("href")).offset().top - 200,
      },
      300
    );
  });

  jQuery("a#proposte").click(function (event) {
    event.preventDefault();
    jQuery("html, body").animate(
      {
        scrollTop: jQuery(jQuery(this).attr("href")).offset().top + 10,
      },
      300
    );
  });

  // Get contact elements
  const generalContactModal = document.querySelector("#cy-general-contact");
  const generalContactModalBtn = document.querySelector(".generalContactForm");
  const generalCloseContactBtn = document.querySelector("#cy-close-contact");
  // Events contactModal
  if (generalContactModalBtn) {
    generalContactModalBtn.addEventListener("click", openGeneralContactModal);
  }
  // generalCloseContactBtn.addEventListener('click', closeGeneralContactModal);
  window.addEventListener("click", outsideClick);

  // Open contact modal
  function openGeneralContactModal() {
    generalContactModal.style.display = "block";
  }
  // Close contact modal
  function closeGeneralContactModal() {
    generalContactModal.style.display = "none";
  }
  // Close If Outside Click
  function outsideClick(e) {
    if (e.target == generalContactModal) {
      generalContactModal.style.display = "none";
    }
  }

  jQuery("#cy-prices-modal").on("scroll", function () {
    if (jQuery("#cy-prices-modal").scrollTop() > 100) {
      jQuery("#cy-prices-modal .cy-modal-header").addClass(
        "price-header-sticky"
      );
    } else {
      jQuery("#cy-prices-modal .cy-modal-header").removeClass(
        "price-header-sticky"
      );
    }
  });

  jQuery("#cy-route-program").on("scroll", function () {
    if (jQuery("#cy-route-program").scrollTop() > 100) {
      jQuery("#cy-route-program .cy-modal-header").addClass(
        "price-header-sticky"
      );
    } else {
      jQuery("#cy-route-program .cy-modal-header").removeClass(
        "price-header-sticky"
      );
    }
  });

  jQuery("#cy-route-contact").on("scroll", function () {
    if (jQuery("#cy-route-contact").scrollTop() > 100) {
      jQuery("#cy-route-contact .cy-modal-header").addClass(
        "price-header-sticky"
      );
    } else {
      jQuery("#cy-route-contact .cy-modal-header").removeClass(
        "price-header-sticky"
      );
    }
  });

  if (jQuery(window).width() <= 1024) {
    // on document load shrinks the social share bar to the left side of the screen
    var mobile_div = jQuery(".et_social_mobile");
    mobile_div.fadeToggle(600);
    jQuery(".et_social_mobile_button").addClass("et_social_active_button");

    if (mobile_div.hasClass("et_social_opened")) {
      jQuery(".et_social_mobile_overlay").fadeToggle(600);
      mobile_div.removeClass("et_social_opened");
      mobile_div.find(".et_social_networks").fadeToggle(600);
    }
  }

  // jQuery(document).on("scroll", function () {
    // var page_content = document.getElementById("page-content");
    // var recaptchaBadge = document.querySelector('.grecaptcha-badge');

    // if (jQuery(document).scrollTop() >= 200) {
    //   jQuery(".l-subheader.at_bottom.width_full").addClass(
    //     "l-subheader-sticky-mobile"
    //   );
    // } else {
    //   jQuery(".l-subheader.at_bottom.width_full").removeClass(
    //     "l-subheader-sticky-mobile"
    //   );
    // }
    // if (jQuery(window).width() <= 900) {
    //   if (jQuery(document).scrollTop() >= 1100) {
    //     jQuery("#webmapp-layer-container").addClass("container-sticky");
    //     page_content.classList.add("with_padding");
    //   } else {
    //     jQuery("#webmapp-layer-container").removeClass("container-sticky");
    //     page_content.classList.remove("with_padding");
    //   }

      // if (jQuery(document).scrollTop() >= 500) {
      // 	recaptchaBadge.style.display = 'none';
      // 	jQuery('.et_social_mobile_button').addClass('move');

      // } else {
      // 	recaptchaBadge.style.display = 'block';
      // 	jQuery('.et_social_mobile_button').removeClass('move');

      // }
    // } else {
    //   jQuery(".et_social_mobile_button").removeClass("move");
    //   if (jQuery(document).scrollTop() >= 680) {
    //     jQuery("#webmapp-layer-container").addClass("container-sticky");
    //     // page_content.classList.add("with_padding");
    //   } else {
    //     jQuery("#webmapp-layer-container").removeClass("container-sticky");
    //     // page_content.classList.remove("with_padding");
    //   }
    // }
  // });

  //Studio samo add class to Contact form success email message
  document.addEventListener(
    "wpcf7mailsent",
    function (event) {
      jQuery(".wpcf7-response-output").attr("id", "richiesta-informazioni");
    },
    false
  );

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
