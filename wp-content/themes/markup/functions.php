<?php
//die('some');
/*if (function_exists('add_theme_support')) {
 add_theme_support('menus');
}*/
add_theme_support( 'menus' );


register_nav_menus(array(
    'top' => 'Верхнее меню',            //Название месторасположения меню в шаблоне
    'bottom' => 'Нижнее меню'   //Название другого месторасположения меню в шаблоне
));



// добавляем хук на инициализацию
add_action('init', 'portfolio_init');

function portfolio_init() {

        // описываем наш тип контента
        $args = array(
                'label' => __('Portfolio'),
                'labels' => array(
                        'edit_item' => __('Edit Work'),
                        'add_new_item' => __('Add New Work'),
                        'view_item' => __('View Work'),
                ),
                'singular_label' => __('Work'),
                'public' => true,
                'show_ui' => true, // показывать в админке?
                '_builtin' => false, // это не встроенный тип данных
                '_edit_link' => 'post.php?post=%d',
                'capability_type' => 'post',
                'hierarchical' => false,
                'rewrite' => array("slug" => "medewerkers"), // формат ссылок
                'supports' => array('title', 'editor', 'thumbnail')
        );

        // регистрируем новый тип
        register_post_type( 'portfolio' , $args ); // первый параметр - это название нашего нового типа данных

        // подключаем таксономию (используемые технологии можно будет назначать как теги)
        register_taxonomy(
                'mtype',
                'portfolio',
                array ('hierarchical' => false, 'label' => __('Technologies'),
                        'singular_label' => __('Technology'),
                        'query_var' => 'mtype')
        );
}

// добавляем хук на инициализацию админки
add_action("admin_init", 'portfolio_admin_init');

function portfolio_admin_init() {
        // добавляем дополнительный блок
        add_meta_box("portfolio-meta", "Details", 'portfolio_options', 'portfolio', 'normal', 'low');
}

// описываем блок
function portfolio_options() {
        global $post;

        // Используем скрытое поле для
        echo '<input type="hidden" name="portfolio_noncename" id="portfolio_noncename" value="' .
        wp_create_nonce( 'portfolio' ) . '" />';

        // наши поля
        $my_fields = array('web' => '', 'customer' => '', 'web' => '', 'date' => '');

        foreach ($my_fields as $key => $value) {
                $my_fields[$key] = get_post_meta($post->ID, 'portfolio-' . $key, true);
        }

        echo '<strong>Web site</strong><br/><br/><input name="portfolio-web" size="60" value="' . $my_fields['web'] . '" /><br/><br/>' . "\n";
        echo '<strong>Customer</strong><br/><br/><input name="portfolio-customer" size="60" value="' . $my_fields['customer'] . '" /><br/><br/>' . "\n";
        echo '<strong>Release date</strong><br/><br/><input name="portfolio-date" size="60" value="' . $my_fields['date'] . '" /><br/><br/>' . "\n";
}

// добавляем хук на сохранение поста
add_action( 'save_post', 'portfolio_save', 1, 2 );

function portfolio_save() {
        global $post;

        $post_id = $post->ID;

        // определяем, что данные пришли с нашей формы и верной авторизацией
        // потому что  save_post может быть вызван и в других случаях

        if ( !wp_verify_nonce( $_POST['portfolio_noncename'], 'portfolio')) return $post_id;

        // если это авто-сохранение, значит форма не сохранена и мы ничего не делаем
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;

        // флаг для определения пользовательского типа данных, он здесь особо не нужен, но не повредит
        $post_flag = 'x';

        if ($post->post_type == "portfolio") {
                $my_fields = array('web' => '', 'customer' => '', 'web' => '', 'date' => '');
                $post_flag = 'portfolio';
        }

        if ($post_flag != 'x') {
                // значит это наш тип контента

                // проходим по всем полям
                foreach ($my_fields as $k=>$v)
                {
                        $key = 'portfolio-' . $post_flag . '-' . $k;

                        $value = @$_POST[$key];
                        if (empty($value))
                        {
                                delete_post_meta($post_id, $key);
                                continue;
                        }

                        // если значение является строкой, то оно должно быть уникальным
                        if (!is_array($value))
                        {
                                // обновляем мета-информацию
                                if (!update_post_meta($post_id, $key, $value))
                                {
                                        // или добавляем
                                        add_post_meta($post_id, $key, $value);
                                }
                        }
                        else
                        {
                                // если мы оказались здесь - нам нужно удалить ключи
                                delete_post_meta($post_id, $key);

                                // проходим по массиву и добавляем новые значения в мета-информацию как различные ячейки с одинаковым именем
                                foreach ($value as $entry)
                                        add_post_meta($post_id, $key, $entry);
                        }
                }

        }
}

add_action("manage_posts_custom_column", "portfolio_custom_columns");
add_filter("manage_edit-portfolio_columns", "portfolio_columns");

// названия колонок
function portfolio_columns($columns)
{
        $columns = array(
                "cb" => "<input type=\"checkbox\" />",
                "title" => "Name",
                "web" => "Web Site",
                "customer" => "Customer",
                "date" => "Release date"
        );
        return $columns;
}

// содержимое колонок
function portfolio_custom_columns($column)
{
        global $post;

        if ("ID" == $column) echo $post->ID;
        elseif ("title" == $column) echo $post->post_title;
        elseif ("web" == $column) {
                $ourl = get_post_meta($post->ID, 'portfolio-web', true);
                echo '<a href="' . $ourl . '" target="_blank">' . $ourl . '</a>';
        }
        elseif ("customer" == $column) {
                $ocustomer = get_post_meta($post->ID, 'portfolio-customer', true);
                echo $ocustomer . '</a>';
        }
        elseif ("date" == $column) {
                $odate = get_post_meta($post->ID, 'portfolio-date', true);
                echo $odate . '</a>';
        }
}
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

?>

<?php
//$option_name = 'myhack_extraction_length' ;
//$newvalue = '255' ;
//
//if ( get_option( $option_name ) != $newvalue ) {
//    update_option( $option_name, $newvalue );
//} else {
//    $deprecated = ' ';
//    $autoload = 'no';
//    add_option( $option_name, $newvalue, $deprecated, $autoload );
//}
//

//add_action("admin_init", 'add_ICQ_Skype_admin_email');
//function add_ICQ_Skype_admin_email()
//{
//
//    $option_name = 'admin_email';
//$newvalue = '250982927';
//
//
//if ( get_option( $option_name ) != $newvalue ) {
//    update_option( $option_name, $newvalue ,'','yes');
////    update_option("ICQ", '250982927', '', 'yes');
////    update_option("Skype", 'Deniss', '', 'yes');
////    update_option("admin_email", 'info@denissopovstudio.com', '', 'yes');
//} else {
//    $deprecated = ' ';
//    $autoload = 'no';
//    add_option( $option_name, $newvalue, $deprecated, $autoload );
//}



function extra_contact_info($contactmethods) {

    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
   
    $contactmethods['icq'] = 'ICQ';
    $contactmethods['skype'] = 'Skype';
    $contactmethods['twitter'] = 'Twitter';
    $contactmethods['vkontakte'] = 'ВКонтакте';
    $contactmethods['facebook'] = 'Facebook';



    return $contactmethods;
}
add_filter('user_contactmethods', 'extra_contact_info');

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}

