<?php


$where = $_GET['_dove_vuoi_andare'];
$when = $_GET['_quando_vuoi_partire'];

//[facetwp facet='quando_vuoi_partire']

($where) ? $where_txt = str_replace("%20"," ",$where) : $where_txt = __('Select your destination','wm-child-cyclando');
if ($when) {
    $when_txt = explode('-',str_replace("%20"," ",$when));
    if ($when_txt == 'august') {
        $when_txt = 'agosto';
    }
}
($when) ? $when_txt = $when_txt[0] : $when_txt = __('When','wm-child-cyclando');

echo do_shortcode('[vc_row height="large" us_bg_image_source="featured" us_bg_repeat="no-repeat" us_bg_overlay_color="rgba(0,0,0,0.30)" el_class="pagina-cerca-descrizione-container search-page-description-mobile" css="%7B%22default%22%3A%7B%22color%22%3A%22%23ffffff%22%7D%7D"][vc_column][/vc_column][vc_column][vc_column_text]<h3>'.__('Plan your bike itinerary and leave immediately for your next vacation', 'wm-child-cyclando').'</h3><p>'.__('With Cyclando, calculating the itinerary of your cycling holiday is very simple. Our cycle itinerary planner will allow you to find the trip that suits you best, based on your choices on:', 'wm-child-cyclando').'</p>[/vc_column_text][/vc_column][us_separator][/vc_row]');

?>

<section class="l-section wpb_row height_small">
    <div class="l-section-h i-cf">
        <div class="g-cols vc_row type_default valign_top">
            <div class="vc_col-sm-12 wpb_column vc_column_container oc-searchpage-info-container-mobile">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper">
                        <div id="searchpage-form-holder-mobile" class="searchpage-form-holder-mobile">
                            <div class="search-form-holder-mobile-where"><i class="fal fa-map-marker-alt"></i><div class="searchpage-form-mobile-where-text"><?= $where_txt ?></div></div>
                            <div class="search-form-holder-mobile-when"><i class="wm-icon-cyc_weekend"></i><div class="searchpage-form-mobile-when-text"><?= $when_txt ?></div></div>
                            <div class="search-form-holder-mobile-participants"><i class="fal fa-user-friends"></i><div class="searchpage-form-mobile-participants-text"></div></div>
                            <div class="search-form-holder-mobile-bikes"><i class="wm-icon-cyc_bici"></i><div class="searchpage-form-mobile-bikes-text"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form popup oneclick -->
<div id="searchpage-form-oneclick-mobile" class="searchpage-form-oneclick-mobile">
    <div class="searchpage-form-oneclick-mobile-container">
        <div class="searchpage-form-oneclick-header">
            <div class="">
                <h2><?php echo __('Edit search','wm-child-cyclando'); ?></h2>
            </div>
            <div class="searchpage-form-close-button-container"><span class="searchpage-form-close">&times;</span></div>
        </div>
        <div class="searchpage-form-oneclick-body">
            <?= do_shortcode("[facetwp facet='dove_vuoi_andare']")?><?= do_shortcode("[oneclick_search_form_participants_bikes]")?>
            <div id="cy-search-lente"><i class="cy-icons icon-search1"></i><?= __('Find a trip','wm-child-cyclando')?></div>
        </div>
    </div>
</div>
<script>
(function($) {
    $(document).ready(function () {
        $('#searchpage-form-holder-mobile').on('click', function() {
            $('#searchpage-form-oneclick-mobile').show();
        });
        $('.searchpage-form-close').on('click', function() {
            $('#searchpage-form-oneclick-mobile').hide();
            $(".cerca-facets-container").removeClass("cerca-facets-container-modal");
            $("#cerca-facets-container-modal-header").show();
        });
        if ($(window).width() < 768) {
            $('#searchpage-facets-filter-btn').on('click', function() {
                $('#searchpage-form-oneclick-mobile').hide();
                $(".cerca-facets-container").removeClass("cerca-facets-container-modal");
                $("#cerca-facets-container-modal-header").show();
                $("#filterSearchDropdown").hide();
            });
        } else {
            $("#cerca-facets-container-modal-header").hide();
        }

        window.addEventListener('click', outsideClick);
        // Close If Outside Click
        function outsideClick(e) {
            if (e.target.id == 'searchpage-form-oneclick-mobile') {
                $('#searchpage-form-oneclick-mobile').hide();
            }
            if (e.target.id == 'cerca-facets-container') {
                $(".cerca-facets-container").removeClass("cerca-facets-container-modal");
                $("#cerca-facets-container-modal-header").hide();
                $("#filterSearchDropdown").hide();
            }
        }

        if (Cookies.get('oc_participants_cookie')) {
            var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie'));
            var sums = cal_sum_cookies(savedCookie);
            if (sums['participants'] !== null) {
                $('.searchpage-form-mobile-participants-text').html(sums['participants'] + ' ' + '<?= __('participants', 'wm-child-cyclando') ?>');
            } else {
                $('.searchpage-form-mobile-participants-text').html('<?= __('participants', 'wm-child-cyclando') ?>');
            }
            if (sums['bikes'] !== null) {
                $('.searchpage-form-mobile-bikes-text').html(sums['bikes'] + ' ' + '<?= __('bikes', 'wm-child-cyclando') ?>');
            } else {
                $('.searchpage-form-mobile-bikes-text').html('<?= __('bikes', 'wm-child-cyclando') ?>');
            }
        } else {
            $('.searchpage-form-mobile-participants-text').html('<?= __('participants', 'wm-child-cyclando') ?>');
            $('.searchpage-form-mobile-bikes-text').html('<?= __('bikes', 'wm-child-cyclando') ?>');
        }
    });
})(jQuery);
</script>
<!-- END HTML modal for bikes btn-->