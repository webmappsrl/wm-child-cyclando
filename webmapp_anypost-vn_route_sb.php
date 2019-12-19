<?php


global $wm_anypost_bootstrap_col_type,
       $wm_anypost_global_taxonomies,
       $wm_anypost_template,
       $wm_anypost_post_type;

$title_link = get_the_permalink();
$current_post_type = get_post_type();
$get_the_post_thumbanil = '';
if(get_the_post_thumbnail_url()) {
    $get_the_post_thumbanil = get_the_post_thumbnail_url(get_the_ID() , 'medium_large');
} else {
    $verde_natura_image = wp_get_attachment_image_src(40702,'medium_large');
    $get_the_post_thumbanil = $verde_natura_image[0];
}

?>

<div class="col-vn-route webmapp_shortcode_any_post post_type_<?php echo $wm_anypost_post_type?>">

    <div class="webmapp_post-title">
        <h2>
            <?php the_title() ?>
        </h2>
    </div>

    <div class="webmapp_route_duration">
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

    <div class="webmapp_post-featured-img">
        <?php
        echo "<a href='$title_link' title=\"".get_the_title()."\">";
        ?>

        <figure class="webmapp_post_image" style="background-image: url('<?php echo $get_the_post_thumbanil;?>')">
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
        </figure>



        <?php
        echo "</a>";
        ?>
    </div>
    <div class="webmapp_post_meta">

        <div class="nazione">
            <?php
           the_term_image_with_name( $post_id , 'where' );
            ?>
        </div> <!--.nazione-->

        <div class="vn-target">
            <?php
            the_term_image_with_name( $post_id , 'who' );
            ?>
        </div> <!--.vn-target-->


            <?php
            $numero = get_field('vn_diff');

            if ( $numero )
            {
                ?>
        <div class="livello">
            <a class="fancybox" href="#difficulty_icon_popup">
                <img src="<?php the_calcola_url( $numero ) ?>">
            </a>
            <p>Livello</p>
        </div> <!--.livello-->
            <?php
            }
            ?>

        <div class="attività-route">
            <?php
            the_term_image_with_name( $post_id , 'activity' );
            ?>
        </div>

    </div>



</div>
