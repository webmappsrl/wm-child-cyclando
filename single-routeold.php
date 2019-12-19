<?php /* Template Name: Single-route */ ?>
<script src="http://code.jquery.com/jquery-latest.js"></script>

<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');

$language = ICL_LANGUAGE_CODE;
?>


<div id="single-post-route-main-content">
    <div id="content-area" class="clearfix webmapp-grid-system">
        <?php while ( have_posts() ): the_post();
        $post_id = get_the_ID();

        ?>


            <!-- musthead -->

            <div class="page-title">
                <h1 class="txt-white"><?php the_title();?></h1>
                <img class="single-route-tree-spring" alt="tree-spring" src="/wp-content/themes/wm-child-verdenatura/images/tree_spring.png">
            </div> <!--chiudo .page-title-->


                               <!--griglia-->
            <div class="webmapp-container-fluid">
                <div class="row single-post-route-row">


                    <!--Colonna destra-->

                    <div class="col-sm-12 col-md-8">
                        <div class="gallery-fdn">
                            <?php
                            $vn_formula_fdn = get_field('wm_fdn');
                            if( $vn_formula_fdn )
                            {
                                echo '<img src="/wp-content/themes/wm-child-verdenatura/images/logo-omino.jpg" class="fdn-card">';
                            }

                            $dog_friendly = get_field ('vn_meta_dog');
                            if ( $dog_friendly)
                                echo "<img src='/wp-content/themes/wm-child-verdenatura/images/dog-friendly.jpg' class='df-card' alt='dog-friendly'>";


                            $new = get_field( 'vn_new' );
                            if( $new )
                                echo "<img src='/wp-content/themes/wm-child-verdenatura/images/new.png' class='card' alt='Novità'>";

                            ?>


                        </div>
                        <div class="top-gallery-single-post-route">
                            <?php
                            $vn_gallery_route = get_field('wm_route_gallery');//n7webmap_track_media_gallery//wm_route_gallery
                            //print_r($vn_gallery_route);
                            if (is_array($vn_gallery_route) && ! empty($vn_gallery_route)) {
                                $vn_id_gallery_route=  array_map(function ($i) {
                                  return $i['ID'];
                                },$vn_gallery_route);
                                $stringa_id_gallery= implode (',', $vn_id_gallery_route);
                                //print_r($vn_id_gallery_route);
                                echo do_shortcode ("[wm_gallery media_ids='$stringa_id_gallery']");
                            }
                            ?>

                        </div> <!-- chiudo .top gallery-->

                        <div class="scheda-preventivo">
                            <?php
                            //echo do_shortcode('[caldepartures name="Bob"]');
                            echo do_shortcode('[vn_route_tabs]');
                            ?>
                        </div> <!--chiudo .scheda-preventivo-->


                        <?php 
                        $products = get_field('product');
                        $departure_periods = get_field('departures_periods');
                        $departure_dates = get_field('departure_dates');
                        $not_salable = get_field('wm_route_not_salable');
                        
                        if ( $not_salable === true ){
                            $hide_button = "hide-button";
                        }  else {
                                $hide_popup_contact = "hide-button";
                            }
                        ?>
                        <div class="container-button-preventivo">
                            <div class="button-preventivo <?php echo $hide_button;?>"><a target='_blank' href="http://vnquote.webmapp.it/#/<?php echo $post_id.'?lang='.$language;?>">
                                        <?php //
                                        echo __('MAKE A QUOTE' ,'wm-child-verdenatura');
                                        ?>
                                    </a>
                            </div> <!--chiudo .button-preventivo -->
                            <div class="button-preventivo <?php echo $hide_popup_contact;?>"><a class="fancybox" href="#contact_form_pop">
                                        <?php //
                                        echo __('REQUEST INFORMATION' ,'wm-child-verdenatura');
                                        ?>
                                    </a>
                            </div> <!--chiudo .button-richiedi informazione -->

                        </div>
                        
                        <div class="fancybox-hidden" style="display: none;">
                            <div id="contact_form_pop"><?php echo do_shortcode('[contact-form-7 id="45093" title="Contact form 1"]');?></div>
                        </div>

                        <div class="scheda-commenti">
                            <h3><?php
                                echo __('Your experiences' ,'wm-child-verdenatura');
                                ?></h3>
                            <p class="p-ex"><?php
                                echo __('Share your experience with us or read the stories of those who traveled with us.' ,'wm-child-verdenatura');
                                ?></p>
                            <button class="button-esperienze"><?php
                                echo __('SHARE YOUR EXPERIENCE' ,'wm-child-verdenatura');
                                ?></button>

                                <script>
                                $(document).ready(function() {
                                 //Faccio sparire il pulsante "Mostra"
                                 $("#respond").hide();
                                 $(".button-esperienze").click(function(){
                                 $("#respond").toggle();
                                 });


                               })
                                </script>


                            <?php

                            if ( ( comments_open() || get_comments_number() ) && 'on' === et_get_option( 'divi_show_postcomments', 'on' ) )
                            {
                                comments_template( '', true );
                            }
                            ?>

                            <br>
                            <hr>

                        </div> <!--chiudo .scheda-commenti -->


                    </div> <!-- chiudo .col-12 .col-sm-6 .col-md-8 -->

                    <!--Colonna sinistra-->

                    <div class="col-sm-12 col-md-4">
                        <div class="box-preventivo-aside">
                            <h2 class="title-preventivo" style="color: #0f7a68;"><?php the_title();?></h2>
                            <p class="sottotitolo-preventivo"><?php
                                echo __('Reference code: ' ,'wm-child-verdenatura');
                                $n7webmapp_route_cod=get_field('n7webmapp_route_cod');
                                If ($n7webmapp_route_cod)
                                echo ($n7webmapp_route_cod);
                            ?>
                            </p>


                            <p class="main-content-preventivo">

                            <?php
                                $vn_desc_min = get_field('vn_desc_min');
                                if ( $vn_desc_min )
                                echo $vn_desc_min;
                            ?>
                            </p>

                            <div class="durata-preventivo">
                                <?php
                                $days = get_field('vn_durata');
                                if ( $days )
                                {
                                    $nights = $days - 1;
                                    ?>
                                     <?php
                                    echo __( 'Duration' , 'wm-child-verdenatura' ) . "<span class='dur-txt'>" .  " $days" . __( 'days' , 'wm-child-verdenatura' ) . "/$nights" . __( 'nights' , 'wm-child-verdenatura' ) ;
                                ?>
                                    </span>

                                    <?php

                                     $vn_note_dur = get_field( 'vn_note_dur' );
                                    if ( $vn_note_dur )
                                        echo "<span class='webmapp_route_duration_notes'> ($vn_note_dur)</span>";
                                }
                                ?>
                            </div>
                            <br>


                            <div class="departure-preventivo-aside"> <!------------ Departure / Partenze -->
                                <span class='durata-txt'>
                                    <p class="tab-section">
                                        <?php
                                        if( have_rows('departures_periods') ){
                                        echo __('Departures:' ,'wm-child-verdenatura');}?>
                                    </p>
                                </span>
                                
                                <?php
                                    if( have_rows('departures_periods') ): ?>
                                    <p class="part-e-pre"></p>
                                    
                                    <div class="departure_name">
                                    </div>
                                    <div class="grid-container-period-aside">
                                    
                                        <?php while( have_rows('departures_periods') ): the_row(); 
                                
                                        // vars
                                        $name = get_sub_field('name');
                                        $start = get_sub_field('start');
                                        $stop = get_sub_field('stop');
                                        $week_days = get_sub_field('week_days');
                                        $dateformatstring = "l";
                                
                                        ?>
                                
                                        <div class="departure_start">
                                            <?php if( $start ): ?>
                                                <p><?php echo __('From:' ,'wm-child-verdenatura').' '.$start; ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="departure_stop">
                                            <?php if( $stop ): ?>
                                                <p><?php echo __('To:' ,'wm-child-verdenatura').' '.$stop; ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="departure_week_days">
                                            <?php if( $week_days ): ?>
                                                <ul>
                                                    <?php if (count($week_days) == 7) { ?>
                                                        <li style="display: inline;" ><?php echo __('Every day' ,'wm-child-verdenatura'); ?></li>
                                                        <?php }else { ?>
                                                            <span><?php echo __('Only' ,'wm-child-verdenatura').' '; ?></span>
                                                            <?php 
                                                            $i = 0;
                                                            $len = count($week_days);
                                                            foreach( $week_days as $week_day ): 
                                                                if ($i == 0){ ?>
                                                                    <li style="display: inline;" ><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
                                                                <?php } elseif ($i == $len -1){ ?>
                                                                    <?php echo __('and' ,'wm-child-verdenatura').' '; ?><li style="display: inline;" ><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
                                                                <?php } else { ?>
                                                                <span><?php echo __(',' ,'wm-child-verdenatura').' '; ?></span><li style="display: inline;" ><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
                                                                <?php } $i++ ;?>
                                                    <?php endforeach; } ?>
                                                </ul>
                                            <?php endif; ?>
                                    </div>
                                
                                    <?php endwhile; ?>
                                
                                    </div>
                                
                                    <?php endif; ?>
                                    
                                    <?php // ---------- single departures ----------------//
                                    if( have_rows('departure_dates') ): ?>
                                    <div class="single-departure">
                                            <p class="tab-section"><?php echo __('Single departures' ,'wm-child-verdenatura');?></p>
                                            <p class="part-e-pre"></p>
                                    </div>
                                    <div class="grid-container-single">
                                    
                                    <?php while( have_rows('departure_dates') ): the_row(); 
                                
                                        // vars
                                        $date = get_sub_field('date');            
                                        ?>
                                
                                        <div class="departure_name">
                                            <?php if( $date ): ?>
                                                <p><?php echo $date; ?></p>
                                            <?php endif; ?>
                                        </div>
                                
                                    <?php endwhile; ?>
                                
                                    </div>
                                
                                    <?php endif; ?> <!-- End ---------- single departures -->
                            </div>


                            <p class="vn-note">

                            <?php
                            $vn_note = get_field('vn_note');
                            if ( $vn_note)
                                echo $vn_note;
                            ?>
                            </p>

                            <div class="specifiche-viaggio">
                                <div class="nazione">
                                    <?php
                                        the_term_image_with_name( $post_id , 'where' ) ; ?>

                                </div> <!--.nazione-->


                                    <?php


                                    $vn_self_guided = get_field('wm_self_guided');
                                    if( $vn_self_guided )
                                    {
                                        echo "<div class=\"vn-target vn-meta-align ind-sp\">";
                                        echo '<img src="/wp-content/themes/wm-child-verdenatura/images/logo-individuale.png">';
                                        echo __('Self guided' , 'wm-child-verdenatura' );
                                        echo "</div> <!--.vn-target-->";
                                    }


                                    $vn_guided = get_field('wm_guided');
                                    if( $vn_guided )
                                    {
                                        echo "<div class=\"vn-target vn-meta-align guid-sp\">";
                                        echo '<img src="/wp-content/themes/wm-child-verdenatura/images/logo-guida.png">';
                                        echo __('Guided' , 'wm-child-verdenatura' );
                                        echo "</div> <!--.vn-target-->";
                                    }

                                    ?>



                                <?php
                                $numero = get_field('vn_diff');
                                if ( $numero )
                                {
                                    ?>
                                <div class="livello vn-meta-align">
                                    <a class="fancybox" href="#difficulty_icon_popup">
                                        <img src="<?php the_calcola_url( $numero ) ?>">
                                    </a>
                                    <p> <?php echo __('Level' ,'wm-child-verdenatura');?></p>
                                </div> <!--.livello-->
                                    <?php
                                }

                                ?>
<div class="act-tar">
                                <div class="attività-route vn-meta-align">
                                    <?php
                                    the_term_image_with_name( $post_id , 'activity' );
                                    ?>
                                </div>

                                <div class="targets-route vn-meta-align">
                                    <?php
                                    the_term_image_with_name( $post_id , 'who' );
                                    ?>
                                </div>

</div> <!--.act-tar-->
                            </div> <!--- .specifiche-viaggio -->

                            <div class="prezzo">
                                <?php
                                echo __('From', 'wm-child-verdenatura');
                                ?>

                                <span class="cifra"><?php
                                $vn_prezzo = get_field('wm_route_price');
                                if ($vn_prezzo)
                                echo $vn_prezzo;
                                ?>
                                € </span>
                            </div> <!--.prezzo -->

                            <div class="button-preventivo <?php echo $hide_button;?>">
                                <a href="http://vnquote.webmapp.it/#/<?php echo $post_id.'?lang='.$language;?>">
                                    <?php //
                                    echo __('MAKE A QUOTE' ,'wm-child-verdenatura');
                                    ?>
                                </a>
                            </div>
                            <div class="button-preventivo <?php echo $hide_popup_contact;?>"><a class="fancybox" href="#contact_form_pop">
                                        <?php //
                                        echo __('REQUEST INFORMATION' ,'wm-child-verdenatura');
                                        ?>
                                    </a>
                            </div> <!--chiudo .button-richiedi informazione -->


                        </div> <!-- chiudo .box-preventivo-aside -->


                        <div class="map-material-placeholder">
                            <?php
                            $vn_immagine_mappa = get_field('vn_immagine_mappa');
                            if ( $vn_immagine_mappa )
                            {
                                $img_id = $vn_immagine_mappa['ID'];
                                echo wp_get_attachment_image( $img_id , 'large');
                            }
                            ?>
                        </div> <!--chiudo .map-material-placeholder -->




                        <div class="interessi">
                            <h2><?php
                                echo __('You might be interested in...' ,'wm-child-verdenatura');
                                ?></h2>

                            <div class="vn-post-interessi">
                                <?php
                                echo do_shortcode('[webmapp_anypost post_type="route" term_id="84" template="vn_route_sb" posts_count=3 rows=3 posts_per_page=3 orderby="rand"]');
                                ?>


                            </div><!-- chiudo .vn-post-interessi-->
                        </div>  <!--chiudo .interessi -->
                    </div> <!-- chiudo .col -->
                </div> <!-- chiudo .row -->


            </div> <!-- .webmapp-container-fluid -->

        <?php endwhile; ?>
    </div> <!--chiudo #single-post-route-main-content -->
</div> <!-- chiudo #content-area --->

<?php


get_footer();

?>
