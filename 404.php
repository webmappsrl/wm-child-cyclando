<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

/**
 * The template for displaying the 404 page
 *
 * Do not overload this file directly. Instead have a look at templates/404.php file in us-core plugin folder:
 * you should find all the needed hooks there.
 */


get_header();
?>
<main id="page-content" class="l-main">

   <section class="l-section height_medium">
      <div class="l-section-h i-cf">
         <div class="svg">
            <svg width="140mm" height="97mm" viewBox="0 0 180.00001 97" version="1.1" id="svg5" inkscape:version="1.1.1 (3bf5ae0d25, 2021-09-20)" sodipodi:docname="drawing.svg" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg">
               <sodipodi:namedview id="namedview7" pagecolor="#505050" bordercolor="#eeeeee" borderopacity="1" inkscape:pageshadow="0" inkscape:pageopacity="0" inkscape:pagecheckerboard="0" inkscape:document-units="mm" showgrid="false" inkscape:zoom="0.40201492" inkscape:cx="461.42566" inkscape:cy="227.60349" inkscape:window-width="1366" inkscape:window-height="745" inkscape:window-x="-8" inkscape:window-y="-8" inkscape:window-maximized="1" inkscape:current-layer="layer1" width="296mm" scale-x="0.99999997" />
               <defs id="defs2" />
               <g inkscape:label="Layer 1" inkscape:groupmode="layer" id="layer1">
                  <path id="path7466" style="fill:none;stroke:#ffffff;stroke-width:2.96774;stroke-miterlimit:4;stroke-dasharray:none" d="m 92.095297,5.2876321 c -15.03956,3.58e-4 -27.231333,13.5570709 -27.23129,30.2799619 0.01755,6.946535 2.182648,13.675133 6.132146,19.057437 v 0 c 7.698027,11.590334 15.741342,23.015407 19.179476,35.161036 1.741949,2.811336 3.449153,1.873796 4.698133,0 C 97.30881,77.282936 106.28026,65.673901 113.76735,53.899928 h -0.005 c 3.60856,-5.269444 5.56381,-11.709123 5.56612,-18.332334 3e-5,-16.723392 -12.19243,-30.2803079 -27.23243,-30.2799619 z m 0.290477,18.8984219 c 5.780835,-6.57e-4 10.467456,5.191445 10.467466,11.596528 -1e-5,6.405072 -4.686631,11.597209 -10.467466,11.596529 C 86.605381,47.3791 81.91945,42.187153 81.91944,35.782582 81.91945,29.378 86.605381,24.186066 92.385774,24.186054 Z" sodipodi:nodetypes="sccccccccscscsc" />
                  <g aria-label="404" id="text1353" style="font-size:46.4716px;line-height:1.25;fill:#ffffff;stroke-width:1.16179" transform="matrix(2.221763,0,0,2.461677,-116.74685,-279.25723)">
                     <path d="m 73.030523,150.46387 h -5.740876 v -7.03428 H 53.538773 v -4.08442 l 13.750874,-20.49016 h 5.740876 v 19.80943 h 4.651698 v 4.76515 h -4.651698 z m -5.332434,-25.77722 -8.781499,13.97779 h 8.373057 v -8.55459 q 0,-1.36147 0.136147,-2.45065 z" style="font-family:'Franklin Gothic Medium';-inkscape-font-specification:'Franklin Gothic Medium, ';fill:#ffffff" id="path6751" />
                     <path d="m 127.53481,150.46387 h -5.74088 v -7.03428 h -13.75087 v -4.08442 l 13.75087,-20.49016 h 5.74088 v 19.80943 h 4.6517 v 4.76515 h -4.6517 z m -5.33243,-25.77722 -8.7815,13.97779 h 8.37305 v -8.55459 q 0,-1.36147 0.13615,-2.45065 z" style="font-family:'Franklin Gothic Medium';-inkscape-font-specification:'Franklin Gothic Medium, ';fill:#ffffff" id="path6755" />
                  </g>
               </g>
            </svg>
         </div>
         <div class="pagina404">

            <?php
            $the_content = '<h1>' . us_translate('Page not found') . '</h1>';
            $the_content .= '<h4>' . __('The link you followed may be broken, or the page may have been removed.', 'us') . '</h4>';
            echo apply_filters('us_404_content', $the_content);
            ?>
         </div>
         <div class="bottone">
         <a href="https://cyclando.com/" button  class="button">
      
    Torna alla Homepage

    <span class="button__inner">
      <span class="button__blobs">
        <span class="button__blob"></span>
        <span class="button__blob"></span>
        <span class="button__blob"></span>
        <span class="button__blob"></span>
      </span>
    </span>
            </a>
  </button>
  
  </div>
      </div>
   </section>
</main>
<?php
get_footer();
