<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

/**
 * The template for displaying the 404 page
 *
 * Do not overload this file directly. Instead have a look at templates/404.php file in us-core plugin folder:
 * you should find all the needed hooks there. 404
 */

$where = $_GET['_dove_vuoi_andare'];
$when = $_GET['_quando_vuoi_partire'];

($where) ? $where_txt = str_replace("%20"," ",$where) : $where_txt = __('Select your destination','wm-child-cyclando');
if ($when) {
    $when_txt = explode('-',str_replace("%20"," ",$when));
    if ($when_txt == 'august') {
        $when_txt = 'agosto';
    }
}
($when) ? $when_txt = $when_txt[0] : $when_txt = __('When','wm-child-cyclando');


get_header();
?>
<main id="page-content" class="l-main">

   <section class="l-section height_medium">
      <div class="l-section-h i-cf">
         <div class="pagina404">

            <h1 class="ops">OPS!</h1>


            <?php


            echo '<h1>' . __('The page you are looking for is not found...', 'wm-child-cyclando') . '</h1>';
            echo '<h2>' . __('(We suspect it is on <b> vacation</b> somewhere!)', 'wm-child-cyclando') . '</h2>';

            ?>
         </div>

         <div class="descrizione404">
            <img class="img404" src="<?= get_stylesheet_directory_uri() ?>/assets/images/biker.gif">
            <div class="lista404">

               <?php echo '<h3>' . __('In the meantime you can: ', 'wm-child-cyclando') . '</h3>'; ?>

               <ul>



                  <?php


                  echo  '<li>' . __('Looking for a <a href="/en/tours"> trip </a> ;', 'wm-child-cyclando');

                  ?>

                  <section class="l-section wpb_row height_small">
                      <div class="l-section-h i-cf">
                          <div class="g-cols vc_row type_default valign_top">
                              <div class="vc_col-sm-12 wpb_column vc_column_container oc-searchpage-info-container-mobile">
                                  <div class="vc_column-inner">
                                      <div class="wpb_wrapper">
                                          <div id="searchpage-form-holder-mobile" class="searchpage-form-holder-mobile" style="position: inherit;margin: auto;">
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
                              <?= do_shortcode("[facetwp facet='dove_vuoi_andare'][facetwp facet='quando_vuoi_partire']")?><?= do_shortcode("[oneclick_search_form_participants_bikes]")?>
                              <div id="cy-search-lente"><i class="cy-icons icon-search1"></i><?= __('Find a trip','wm-child-cyclando')?></div>
                          </div>
                      </div>
                  </div>

                  <?php

                  echo  '<li>' . __('Go to <a href="/en">Homepage</a> to search for your ideal trip ;', 'wm-child-cyclando') . '</li>';

                  echo  '<li>' . __('<a href="/en/contacts"> Contact us </a> for more information', 'wm-child-cyclando') . '</li>';

                  ?>
               </ul>
            </div>
         </div>

      </div>
   </section>
</main>
<div id="cy-search-template-container"><?= do_shortcode("[facetwp template='home_dove_vuoi_andare']")?></div>
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
               $("#filterSearchDropdown").hide();
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
<?php
get_footer();
