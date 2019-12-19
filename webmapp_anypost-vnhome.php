<?php


global $wm_anypost_bootstrap_col_type,
       $wm_anypost_global_taxonomies,
       $wm_anypost_template,
       $wm_anypost_post_type;

$title_link = get_the_permalink();
$current_post_type = get_post_type();
// $post_id = get_the_ID();
// $post_term = get_the_terms( $post_id , array('activity','who') );
// print_r( $post_term );
// echo $post_term->slug;

$get_the_post_thumbanil = '';
if(get_the_post_thumbnail_url()) {
    $get_the_post_thumbanil = get_the_post_thumbnail_url(get_the_ID() , 'medium_large');
} else {
    $verde_natura_image = wp_get_attachment_image_src(40702,array(300,201));
    $get_the_post_thumbanil = $verde_natura_image[0];
}

?>

<div class="col-sm-12 col-md-<?php echo $wm_anypost_bootstrap_col_type?> webmapp_shortcode_any_post post_type_<?php echo $wm_anypost_post_type?>">


    <div class="single-post-wm">
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

        <div class="webmapp_post-title">
            <h2 >
                <?php the_title() ?>
            </h2>
        </div>

        <?php
        echo "</a>";
        ?>
    </div>
    <div class="webmapp_post_meta">
        <?php
        $desc_br = get_field('vn_desc_min');
        if ( $desc_br )
            echo $desc_br;



        $price = get_field( 'wm_route_price' );
        if ( $price )
        {
            //$price = number_format( $price, 0, ',', '.');
            $price = (float)$price;
            $sale_price_p = '';
            $sale_price = get_field( 'vn_prezzo_sc' );
            if ( $sale_price > 0 )
                $sale_price_p = number_format( $sale_price, 0, ',', '.') . ' € ';


            echo "<p class='prezzo-tab' >" . __('Prices from' , 'wm-child-verdenatura') . " <span class='vn-sale-price cifra'>$sale_price_p</span><span class='cifra'>$price €</span></p>";
        }


        ?>
    </div>

    </div>

</div>
