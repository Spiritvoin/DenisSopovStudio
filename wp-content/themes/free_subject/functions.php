<?php
	register_sidebar( array(
		'name' => 'Левый-sidebar',
		'id' => 'left-sidebar',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
	register_sidebar( array(
		'name' => 'Правый-sidebar',
		'id' => 'right-sidebar',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );

?>
<?php
	register_sidebar( array(
		'name' => '404',
		'id' => '404',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );

register_sidebar( array(
		'name' => 'index-insert',
		'id' => 'index-insert',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
?>
<?php
        function related_posts_shortcode($atts)
{
    extract(shortcode_atts(array(
                                'limit' => '5',
                           ), $atts));

    global $wpdb, $post, $table_prefix;

    if ($post->ID) {
        $retval = '<ul>';
        // Get tags
        $tags = wp_get_post_tags($post->ID);
        $tagsarray = array();
        foreach ($tags as $tag) {
            $tagsarray[] = $tag->term_id;
        }
        $tagslist = implode(',', $tagsarray);

        // Do the query
        $q = "SELECT p.*, count(tr.object_id) as count
            FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p WHERE tt.taxonomy ='post_tag' AND tt.term_taxonomy_id = tr.term_taxonomy_id AND tr.object_id  = p.ID AND tt.term_id IN ($tagslist) AND p.ID != $post->ID
                AND p.post_status = 'publish'
                AND p.post_date_gmt < NOW()
            GROUP BY tr.object_id
            ORDER BY count DESC, p.post_date_gmt DESC
            LIMIT $limit;";

        $related = $wpdb->get_results($q);
        if ($related) {
            foreach ($related as $r) {
                $retval .= '
    <li><a title="' . wptexturize($r->post_title) . '" href="' . get_permalink($r->ID) . '">' . wptexturize($r->post_title) . '</a></li>
';
            }
        } else {
            $retval .= '
    <li>No related posts found</li>
';
        }
        $retval .= '</ul>
';
        return $retval;
    }
    return;
}

add_shortcode('related_posts', 'related_posts_shortcode');


add_theme_support('post-thumbnails');
add_action( 'wp_head', 'fb_like_thumbnails' );

function fb_like_thumbnails()
{
    global $posts;
    $default = '/images/logo.png';
 
    $content = $posts[0]->post_content; // $posts is an array, fetch the first element
    $output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
    if ( $output > 0 )
        $thumb = $matches[1][0];
    else
        $thumb = $default;

    echo "\n\n<!-- Facebook Like Thumbnail -->\n<link rel=\"image_src\" href=\"$thumb\" />\n<!-- End Facebook Like Thumbnail -->\n\n";
}
register_sidebar( array(
		'name' => 'index-insert',
		'id' => 'index-insert',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Определяем изображение по умолчанию
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}

?>