<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

/**
 * The template for displaying the 404 page
 *
 * Do not overload this file directly. Instead have a look at templates/404.php file in us-core plugin folder:
 * you should find all the needed hooks there. 404
 */


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

                  echo  '<li>' . __('Go to <a href="/en">Homepage</a> to search for your ideal trip ;', 'wm-child-cyclando') . '</li>';

                  echo  '<li>' . __('<a href="/en/contacts"> Contact us </a> for more information', 'wm-child-cyclando') . '</li>';

                  ?>
               </ul>
            </div>
         </div>

      </div>
   </section>
</main>
<?php
get_footer();
