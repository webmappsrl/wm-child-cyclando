<?php


global $wm_anypost_bootstrap_col_type,
       $wm_anypost_global_taxonomies,
       $wm_anypost_template,
       $wm_anypost_post_type;

$content =  get_the_content();
$title = get_the_title();
$the_excerpt = wp_trim_words($content,16);
$title_link = get_the_permalink();
$current_post_type = get_post_type();
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
            <figure class="webmapp_post_image" style="background-image: url('<?php echo $get_the_post_thumbanil;?>')">
            </figure>
        </div>
        <div class="webmapp_post_meta">
            
                <?php echo "<a href='$title_link' title=\"".get_the_title()."\">"; ?>
                    <h2>
                        <?php echo $title;?>
                    </h2>
                <?php echo "</a>"; ?>
                    <p style="display: -webkit-inline-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;">
                    <?php echo $the_excerpt; ?>
                    </p>
                    <div class="read-more"><?php echo "<a href='$title_link' title=\"".get_the_title()."\">"; ?><p class="blog-button"><?php echo __('Read more' ,'wm-child-verdenatura')?></p><?php echo "</a>"; ?></div>
            
        </div>

    </div>

</div>