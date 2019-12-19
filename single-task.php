<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying all single posts
 *
 * Do not overload this file directly. Instead have a look at templates/single.php file in us-core plugin folder:
 * you should find all the needed hooks there.
 */
 ?>
<?php

  $id = get_the_ID();
  $fields = get_fields();
  $args = array( 'post_id' => $id );
  $comments = get_comments($args);

?>
	<main id="page-content" class="l-main">
<div>
	<h1><?php echo get_the_title() ?></h1>
	<p>
	<?php foreach ($fields as $key => $val): ?>
	<strong><?php echo $key ?></strong>: <?php echo $val; ?><br />
	<?php endforeach; ?>

	<h2>Discussion:</h2>
	<?php if(count($comments)>0): foreach($comments as $c): ?>
	</br>Data: <?php echo $c->comment_date;?> - <?php echo $c->comment_author ?>(<?php echo $c->comment_author_email;?>)</br>
	      <?php echo $c->comment_content ?></br>
    <?php endforeach; endif; ?>
	</p>
</div>
	</main>
