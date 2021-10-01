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
    $('.facetwp-dropdown').on('change', function() {
      if ($(this).val()) {
        return $(this).css('color', 'black');
      } else {
        return $(this).css('color', '#888');
      }
    });

    // fix for search page on facet dropdown change that did not refreshed the URL 
    jQuery('.facetwp-dropdown').on('change', function()
    {
        setGetParam('_quando_vuoi_partire',this.value);
    });
    function setGetParam(key,value) {
      if (history.pushState) {
        var params = new URLSearchParams(window.location.search);
        params.set(key, value);
        var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + params.toString();
        window.history.pushState({path:newUrl},'',newUrl);
      }
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
  
  if (document.documentElement.lang == "en-US") {
    // Dove vui andare?
    jQuery('#cy-search-element-container > div:nth-child(1) > div > input.facetwp-autocomplete.fcomplete-enabled').attr("placeholder","Choose a location");
    jQuery('#searchpage-form-oneclick-mobile > div > div.searchpage-form-oneclick-body > div:nth-child(1) > div > input.facetwp-autocomplete.fcomplete-enabled').attr("placeholder","Choose a location");
    jQuery('#page-content > section.l-section.wpb_row.height_small.general-cerca-facetwp-container > div > div > div.vc_col-sm-12.wpb_column.vc_column_container.cerca-facets-container > div > div > div:nth-child(1) > div > div > div > div:nth-child(2) > div > div > div > div > input').attr("placeholder","Choose a location");
  } 
});

jQuery(document).ready(function () {
  var main_url;
  var filter;
  // var pathname = window.location.href;
  // var url = new URL(pathname);
  // var lang = url.searchParams.get("lang");
  var lang = document.documentElement.lang;
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
  if (lang == "en-US") {
    jQuery("<div class='covidbanner'><div class='covidbanner-container'><span> NOTICE FOR TRAVELERS: <a href='https://cyclando.com/assicurazione-covid/'>find out more about COVID-19</a></span> <span class='cy-close-covidbanner'>&times;</span></div></div>").insertBefore(".l-canvas.type_wide");
    // Dove vui andare?
    jQuery('#cy-search-element-container > div:nth-child(1) > div > input.facetwp-autocomplete.fcomplete-enabled').attr("placeholder","Choose a location");
    jQuery('#searchpage-form-oneclick-mobile > div > div.searchpage-form-oneclick-body > div:nth-child(1) > div > input.facetwp-autocomplete.fcomplete-enabled').attr("placeholder","Choose a location");
    jQuery('#page-content > section.l-section.wpb_row.height_small.general-cerca-facetwp-container > div > div > div.vc_col-sm-12.wpb_column.vc_column_container.cerca-facets-container > div > div > div:nth-child(1) > div > div > div > div:nth-child(2) > div > div > div > div > input').attr("placeholder","Choose a location");
  } else {
    jQuery("<div class='covidbanner'><div class='covidbanner-container'><span> AVVISO PER CHI VIAGGIA: <a href='https://cyclando.com/assicurazione-covid/'>scopri di più sul COVID-19</a></span> <span class='cy-close-covidbanner'>&times;</span></div></div>").insertBefore(".l-canvas.type_wide");
  }
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
    if (lang == "en-US") {
      // Dove vui andare?
      jQuery('#cy-search-element-container > div:nth-child(1) > div > input.facetwp-autocomplete.fcomplete-enabled').attr("placeholder","Choose a location");
      jQuery('#searchpage-form-oneclick-mobile > div > div.searchpage-form-oneclick-body > div:nth-child(1) > div > input.facetwp-autocomplete.fcomplete-enabled').attr("placeholder","Choose a location");
      jQuery('#page-content > section.l-section.wpb_row.height_small.general-cerca-facetwp-container > div > div > div.vc_col-sm-12.wpb_column.vc_column_container.cerca-facets-container > div > div > div:nth-child(1) > div > div > div > div:nth-child(2) > div > div > div > div > input').attr("placeholder","Choose a location");
      // In quale mese?
      jQuery('#cy-search-element-container > div:nth-child(2) > div > select > option:nth-child(1)').text("Select month");
      jQuery('.facetwp-facet-quando_vuoi_partire > select > option:nth-child(1)').text("Select month");
      jQuery('#page-content > section.l-section.wpb_row.height_small.general-cerca-facetwp-container > div > div > div.vc_col-sm-12.wpb_column.vc_column_container.cerca-facets-container > div > div > div:nth-child(1) > div > div > div > div:nth-child(3) > div > div > div > div > select > option:nth-child(1)').text("Select month");
      // CERCA
      jQuery(".cy-facetwp-cerca-quando").append(jQuery(".cy-facetwp-cerca-where input.facetwp-autocomplete-update"));
      jQuery('#page-content > section.l-section.wpb_row.height_small.general-cerca-facetwp-container > div > div > div.vc_col-sm-12.wpb_column.vc_column_container.cerca-facets-container > div > div > div:nth-child(1) > div > div > div > div:nth-child(3) > div > div > input').html("Apply");
      jQuery('#page-content > section.l-section.wpb_row.height_small.general-cerca-facetwp-container > div > div > div.vc_col-sm-12.wpb_column.vc_column_container.cerca-facets-container > div > div > div:nth-child(1) > div > div > div > div:nth-child(3) > div > div > input').val("Apply");
      jQuery('#page-content > section.l-section.wpb_row.height_small.general-cerca-facetwp-container > div > div > div.vc_col-sm-12.wpb_column.vc_column_container.cerca-facets-container > div > div > div:nth-child(1) > div > div > div > div:nth-child(3) > div > div > input').attr("value","Apply");
    } else {
      jQuery(".cy-facetwp-cerca-quando").append(jQuery(".cy-facetwp-cerca-where input.facetwp-autocomplete-update"));
      jQuery(".cy-facetwp-cerca-quando input.facetwp-autocomplete-update").html("CERCA");
      jQuery(".cy-facetwp-cerca-quando input.facetwp-autocomplete-update").val("CERCA");
    }
    jQuery(".facetwp-facet-quando_vuoi_partire .facetwp-dropdown").on('change',function(e){
      var val = this.value;
      FWP.parse_facets();
      FWP.set_hash();
      savedCookie = ocmCheckCookie(); 
      var split = val.split('-');
      savedCookie['departureMonth'] = split[0];
      Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
    });
  });

  //mobile and desktop button management on first loading
  if (jQuery(window).width() >= 900) {
    jQuery("#buttonFilterSearch").hide();
    jQuery("#orderSearch").hide();
  } else {
    jQuery("#buttonFilterSearch").show();
    jQuery("#orderSearch").show();
    //dropdown filtra
    jQuery("#buttonFilterSearch").click(function (event) {
      jQuery(".cerca-facets-container").addClass("cerca-facets-container-modal");
      jQuery("#filterSearchDropdown").show();
      jQuery(".cerca-facets-container #filterSearchDropdown > div > div:first-child > .wpb_wrapper").prepend(jQuery("#cerca-facets-container-modal-header"));
      jQuery(".cerca-facets-container #filterSearchDropdown > div").append(jQuery(".filterSearchDropdownBtn"));
      jQuery("#cerca-facets-container-modal-header").show();

      // adds box shadow to the apply button on filter mofal facetwp in searchpage
      jQuery('.filterSearchDropdownBtn').addClass('filterSearchDropdownBtn-shadow');
      jQuery('.cerca-facets-container-modal').on('scroll', function() {
        if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
            jQuery('.filterSearchDropdownBtn').removeClass('filterSearchDropdownBtn-shadow');
          } else {
            jQuery('.filterSearchDropdownBtn').addClass('filterSearchDropdownBtn-shadow');
        }
      })
    });
    jQuery("#cerca-facets-container-modal-header").hide();
    jQuery("#filterSearchDropdown").hide();
  }

  //mobile and desktop button management after window change
  jQuery(window).on("resize", function () {
    if (jQuery(window).width() >= 900) {
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
    ".facetwp-facet.facetwp-facet-dove_vuoi_andare.facetwp-type-autocomplete"
  ).click(function () {
    // var $filter = jQuery(".fs-search input");
    var $filter = jQuery(".facetwp-facet.facetwp-facet-dove_vuoi_andare.facetwp-type-autocomplete input[type=text]");
    //#cy-search-lente

    $filter.keyup(function () {
      // Retrieve the input field text
      filter = jQuery(this).val();
      jQuery(this).val(jQuery(this).val().replace(/[’]/g, "'"));
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

  
  if (lang == 'en-US') {
    main_url =
      window.location.protocol +
      "//" +
      window.location.host +
      "/" +
      "en/tours/";
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

  // // upon click on menu search icon lente
  // jQuery("#vn-search-bar-header .facetwp-btn").click(function (event) {
  //   // event.preventDefault();
  //   location.href = main_url + "?_dove_vuoi_andare=" + filter;
  // });

  // jQuery("#vn-menu-search-map").click(function () {
  //   location.href = main_url + "?_dove_vuoi_andare=" + filter + "&fwp_map=1";
  // });

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

  jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();

    if (scroll >= 100) {
        jQuery("#page-header").addClass("sticky");
    } else {
        jQuery("#page-header").removeClass("sticky");
    }
  });

});

  function cal_sum_cookies(savedCookie) {
    parseInt(savedCookie['kids']) ? k = parseInt(savedCookie['kids']) : k = 0;
    parseInt(savedCookie['adults']) ? a = parseInt(savedCookie['adults']) : a = 0;
    if (a || k ){
        var psum = a + k;
    } else {
        var psum = null;
    }
    parseInt(savedCookie['regular']) ? r = parseInt(savedCookie['regular']) : r = 0;
    parseInt(savedCookie['electric']) ? e = parseInt(savedCookie['electric']) : e = 0;
    if (e || r ){
        var bsum = e + r;
    } else {
        var bsum = null;
    }
    var sums = {};
    sums['participants'] = psum;
    sums['bikes'] = bsum;
    return sums;
  }

  function ocmCheckCookie(){
    if (Cookies.get('oc_participants_cookie')){
        return JSON.parse(Cookies.get('oc_participants_cookie'));
    } else {
        return {}
    }
  }

  function calculateDepartureDate(start_arrayYmd){
    var savedCookie = ocmCheckCookie();
    var departureMonth = '';
    if (savedCookie['departureMonth']) {
        departureMonth = savedCookie['departureMonth'];
    } else {
        var thisday = new Date();
        var thismonth = thisday.toLocaleString(document.documentElement.lang, { month: 'long' });
        departureMonth = thismonth;
    }
    if (departureMonth) {

        var finalDate = '';
        var sevenDaysFromToday;
        var monthNames = {gennaio:0,febbraio:1,marzo:2,aprile:3,maggio:4,giugno:5,luglio:6,august:7,settembre:8,ottobre:9,novembre:10,dicembre:11}
        var selectedMonthNumber = monthNames[departureMonth.toLowerCase()];
        var d = new Date();
        var currentMonth = monthNames[d.getMonth()];

        // Set the first day of month
        var monthStartDay = '01';

        // Calculate the last day of the current month
        d.setDate(1)
        d.setMonth(d.getMonth() + 1)
        d.setDate(d.getDate() - 1)
        var monthLastDay = d.getDate();

        // Calculate the current date + 7 days
        d = new Date();
        d.setDate(d.getDate() + 7);
        var dayTodayPlusSevenDays = d.getDate();
        var monthTodayPlusSevenDays = d.getMonth();
        var yearTodayPlusSevenDays = d.getFullYear();

        if (monthTodayPlusSevenDays == selectedMonthNumber  ) {
            monthTodayPlusSevenDays++;
            dayTodayPlusSevenDays = '0'+dayTodayPlusSevenDays;
            dayTodayPlusSevenDays = dayTodayPlusSevenDays.slice(-2);
            sevenDaysFromToday = yearTodayPlusSevenDays+'-'+monthTodayPlusSevenDays+'-'+dayTodayPlusSevenDays;

            if (typeof start_arrayYmd !== 'undefined' &&  start_arrayYmd.indexOf(sevenDaysFromToday) > -1) {
                finalDate = sevenDaysFromToday;
            } else {
                var index = 0;
                if (typeof start_arrayYmd !== 'undefined' ) {
                  while (index < start_arrayYmd.length && !finalDate) {
                      if (sevenDaysFromToday < start_arrayYmd[index]) { 
                          finalDate =  start_arrayYmd[index];
                      } else {
                          index++;
                      }
                  }
                } else {
                  finalDate = sevenDaysFromToday;
                }
            }
        } else {
            var index = 0;
            selectedMonthNumber++;
            sevenDaysFromToday = yearTodayPlusSevenDays+'-'+selectedMonthNumber+'-'+monthStartDay;
            if (typeof start_arrayYmd !== 'undefined' ) {
              while (index < start_arrayYmd.length && !finalDate) {
                  if (!finalDate) {
                      if (sevenDaysFromToday <= start_arrayYmd[index]) { 
                          finalDate =  start_arrayYmd[index];
                      } else {
                          index++;
                      }
                  }
              }
            } else {
              finalDate = sevenDaysFromToday;
            }
        }
        if (finalDate) {
            finalDate = finalDate.split('-');
            finaleDay = finalDate[2];
            finaleMonth = finalDate[1];
            // var monthNames = {'1':'Gennaio','2':'Febbraio','3':'Marzo','4':'Aprile','5':'Maggio','6':'Giugno','7':'Luglio','8':'Agosto','9':'Settembre','1':'Ottobre','11':'Novembre','12':'Dicembre'};
            var monthNames = {'1':'01','2':'02','3':'03','4':'04','5':'05','6':'06','7':'07','8':'08','9':'09','10':'10','11':'11','12':'12'};

            finaleYear = finalDate[0];
            general_first_departure_date_ajax = finaleDay + '-' + monthNames[finaleMonth] + '-' + finaleYear;
            savedCookie['departureDate'] = general_first_departure_date_ajax;
            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
        } 
        
    }
  }
