<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>


<div id="container">
    <div id="content" role="main">
        <?php if ( is_single() || is_page() ) { ?><?php $title = get_post_meta($post->ID, 'Title', true);  if ($title) { ?>
        <?php echo $title; ?> | <?php bloginfo('name'); ?>
        <?php } else { ?>
        <?php wp_title(''); ?> | <?php bloginfo('name'); ?>
        <?php } ?>
        <?php } ?>

<?php
            /* Run the loop to output the post.
			 * If you want to overload this in a child theme then include a file
			 * called loop-single.php and that will be used instead.
			 */
        get_template_part('loop', 'single');
        ?>

    </div>
    <!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
