
<?php

add_shortcode( 'vn_calendar_all', 'calendar_departures_all_shortcode' );
function calendar_departures_all_shortcode( $atts ) {
   
   ob_start();
   $calendar_json = "http://vnpreprod.webmapp.it/calendar_departures_all.json";
   $c = json_decode(file_get_contents($calendar_json),TRUE);
   $routes = $c['features'];
   ?>
   <div id="tabs" class="vn-tab-departures">
        <ul class="tabs-departures-tab">
            <li class="tabmonth 1"><a href="#tabs-1" ><?php echo __( 'Jan' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 2"><a href="#tabs-2" ><?php echo __( 'Feb' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 3"><a href="#tabs-3" ><?php echo __( 'Mar' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 4"><a href="#tabs-4" ><?php echo __( 'Apr' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 5"><a href="#tabs-5" ><?php echo __( 'May' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 6"><a href="#tabs-6" ><?php echo __( 'Jun' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 7"><a href="#tabs-7" ><?php echo __( 'Jul' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 8"><a href="#tabs-8" ><?php echo __( 'Aug' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 9"><a href="#tabs-9" ><?php echo __( 'Sep' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 10"><a href="#tabs-10" ><?php echo __( 'Oct' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 11"><a href="#tabs-11" ><?php echo __( 'Nov' , 'wm-child-verdenatura' ); ?></a></li>
            <li class="tabmonth 12"><a href="#tabs-12" ><?php echo __( 'Dec' , 'wm-child-verdenatura' ); ?></a></li>
        </ul>
    
        <div id="tabs-1" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            // setlocale(LC_TIME, 'ita' ,'it_IT.utf8');
            // date_default_timezone_set('Europe/Rome');
            // $monthName = utf8_encode(strftime ("%b", $next_departure_month[1]));
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 1 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 01 -->
        <div id="tabs-2" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 2 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 02 -->
        <div id="tabs-3" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 3 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 03 -->
        <div id="tabs-4" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 4 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 04 -->
        <div id="tabs-5" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 5 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 05 -->
        <div id="tabs-6" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 6 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 06 -->
        <div id="tabs-7" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 7 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 07 -->
        <div id="tabs-8" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 8 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 08 -->
        <div id="tabs-9" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 9 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 09 -->
        <div id="tabs-10" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 10 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 10 -->
        <div id="tabs-11" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 11 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 11 -->
        <div id="tabs-12" class="bici-content">
        <?php
        foreach ($routes as $r) {
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
            $url = $r['properties']['url'];
            $image = $r['properties']['image'];
            $departures = $r['properties']['all_dates'];
            $duration = $r['properties']['duration'];
            $price = $r['properties']['price'];
            $activities = $r['properties']['wm_route_tax_activity_id'];
            $activity_name = '';
            $tax_term_id = '';
            foreach ($activities as $activity ) {
                $activity_name = $activity['name'];
                $tax_term_id = $activity['term_id'];
            }
            $term = 'term_'.$tax_term_id;
            $iconimage = get_field('wm_taxonomy_featured_icon',$term);
            $iconimageurl = $iconimage['url'];
            $next_departure = $departures[0];
            $next_departure_month = explode('-',$next_departure);
            $dayNumber = $next_departure_month[2];
            if ( $next_departure_month[1] == 12 ) {
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
                                    <?php echo __( 'Duration' , 'wm-child-verdenatura' ) .$duration.' '.__( 'days' , 'wm-child-verdenatura' ); ?>
                                </p>
                                <p>
                                    <?php echo __( 'From' , 'wm-child-verdenatura' ).' '.$price.'€'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="webmapp_post_date">
                                <div>
                                    <img src="<?php echo $iconimageurl;?>" style="width:40px;">
                                </div>  
                                <p>
                                    <span class="d-date"><?php echo $dayNumber.'/'.$next_departure_month[1]; ?></span>
                                    <span class="m-date"></span>
                                </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
        </div> <!-- tab 12 -->
    </div>
    <script>
        ( function($) {
            var date = new Date();
            month = date.getMonth();
            console.log(month);
            // $('.tabs-departures-tab li:eq('+month+')').prop('aria-selected', true);
            // $('.tabs-departures-tab li:eq('+month+')').prop('aria-expanded', true);
            $('#tabs').tabs({
                active: month
            });

            $( "#tabs" ).tabs({
                activate: function( event, ui ) {
                    ui.newPanel.find('.webmapp_post_image').each(function(i,e){
                        force_aspect_ratio($(e));
                    } );
                }
            });
        } )(jQuery);

        jQuery(function(){
            window.et_pb_smooth_scroll = () => {};
        });
    </script>
    <?php 
    
   return ob_get_clean(); 

}