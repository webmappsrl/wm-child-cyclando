<?php



add_shortcode( 'wm_gallery', 'wm_render_gallery' );
/**
 * @param $atts
 * @return string
 */
function wm_render_gallery ($atts ) {

    extract( shortcode_atts( array(
        'media_ids' => ''
    ), $atts ) );

    $images= explode (',', $media_ids);
 ob_start ();

    ?>

    <div class="slider-for">
            <?php foreach ($images as $id) {
                echo '<div style="height: auto;">';
                echo wp_get_attachment_image( $id, 'large');
                echo '</div>';
            }
            ?>
    </div>

    <div class="slider-nav">
        <?php foreach ($images as $id) { echo '<div>';
            echo wp_get_attachment_image( $id, 'thumbnail');
            echo '</div>';
        }
        ?>
    </div>

    <script>
        (function ($) {
            $('.slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                adaptiveHeight: true,
                asNavFor: '.slider-nav'
            })
                /**
                .on('setPosition', function (event, slick) {
                    slick.$slides.find('img').css('height', slick.$slideTrack.height() + 'px');
                    console.log (slick);

                })**/;
            $('.slider-nav').slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                asNavFor: '.slider-for',
                dots: false,
                arrows: false,
                centerMode: true,
                focusOnSelect: true,
                autoplay: true
            });
           
        } )(jQuery);

        

    </script>



    <?php

    $html = ob_get_clean();
    return $html;
}
