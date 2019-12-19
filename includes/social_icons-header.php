<?php
/**
 * Created by PhpStorm.
 * User: Silvia
 * Date: 26/11/18
 * Time: 20:52
 */

?>


<ul id="vn-top-menu">
    <li class="text-secondary-menu">Viaggi e vacanze in bici e a piedi in Italia e Europa</li>
</ul>


<ul class="et-social-icons">
    <?php if ( 'on' === et_get_option( 'divi_show_google_icon', 'on' ) ) : ?>
        <li class="et-social-icon et-social-google-plus">
            <a href="<?php echo esc_url( et_get_option( 'https://www.instagram.com/verde_natura/', 'https://www.instagram.com/verde_natura/' ) ); ?>" class="icon">
                <span><?php esc_html_e( 'Google', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>
    <?php if ( 'on' === et_get_option( 'divi_show_facebook_icon', 'on' ) ) : ?>
        <li class="et-social-icon et-social-facebook">
            <a href="<?php echo esc_url( et_get_option( 'https://www.facebook.com/verdenaturaviaggi', 'https://www.facebook.com/verdenaturaviaggi' ) ); ?>" class="icon">
                <span><?php esc_html_e( 'Facebook', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>
    <?php if ( 'on' === et_get_option( 'divi_show_twitter_icon', 'on' ) ) : ?>
        <li class="et-social-icon et-social-twitter">
            <a href="<?php echo esc_url( et_get_option( 'https://twitter.com/verdenaturabici', 'https://twitter.com/verdenaturabici' ) ); ?>" class="icon">
                <span><?php esc_html_e( 'Twitter', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>


    <?php if ( 'on' === et_get_option( 'divi_show_rss_icon', 'on' ) ) : ?>
        <?php
        $et_rss_url = '' !== et_get_option( 'divi_rss_url' )
            ? et_get_option( 'divi_rss_url' )
            : get_bloginfo( 'rss2_url' );
        ?>
    <?php endif; ?>


