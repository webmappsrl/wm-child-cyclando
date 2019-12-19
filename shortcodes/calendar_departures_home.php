<?php

add_shortcode( 'caldepartures', 'calendar_departures_home_shortcode' );
function calendar_departures_home_shortcode( $atts ) {
   
   ob_start();
   //$actual_link = "{$_SERVER['REQUEST_URI']}";
   $calendar_json = "http://vnpreprod.webmapp.it/calendar_departures_all.json";
   $c = json_decode(file_get_contents($calendar_json),TRUE);
   $routes = $c['features'];
   $i=0;
   foreach ($routes as $r) {
       if ( $i < 10 ) {
        if (isset($_GET['lang'])){
            $lang = $_GET['lang'];
            if (array_key_exists("translations",$r['properties']) && array_key_exists($lang,$r['properties']['translations'])  ) {
                $title = $r['properties']['translations'][$lang]['name'];
            } else {
                $title = $r['properties']['name'];
            }
        } else {
            $title = $r['properties']['name'];
        }
            // $title = $r['properties']['name'];
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $next_departure = $departures[0];
        ?>

        <div class="col-sm-12 col-md-12 shortcode_caldepartures post_type_route">
            <div class="calendar-single-post-wm">
                <div class="webmapp_post-featured-img">
                    <?php
                    echo "<a href='$url' title='$title'>";
                    ?>
                    <figure class="webmapp_post_image" style="background-image: url('<?php echo $image;?>')"></figure>
                    <?php
                    echo "</a>";
                    ?>
                </div>
                <div class="webmapp_post_meta">
                    <div class="webmapp_post-title">
                        <?php
                        echo "<a href='$url' title='$title' class='departure-title'>";
                        echo $title;
                        echo "</a>";
                        ?>
                        <p>
                            <?php echo $next_departure; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
      }
      $i++;
   }
   return ob_get_clean(); 

}