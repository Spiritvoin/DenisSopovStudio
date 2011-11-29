<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
    <style type="text/css" media="screen">
        @import url( <?php bloginfo('stylesheet_url'); ?> );
    </style>
    <?php wp_head(); ?>
    <link href="<?php bloginfo('url'); ?>/wp-content/themes/free_subject/favicon.ico" rel="shortcut icon">
</head>
<body>
<div align="center">
    <table border="0" cellpadding="8" cellspacing="0" width="900" id="page">
        <tr>
            <td colspan="3">
                <!– Выводим на страницу header –>
                <?php get_header(); ?>
                <!– Конец вывода header –>
            </td>
        </tr>
        <tr>
            <td width="200" valign="top">
                <!-- Выводим на страницу sidebar -->
                <?php get_sidebar(); ?>
                <!-- Конец вывода sidebar --></td>
            <td width="500" valign="top">
                <!-- Выводим на страницу index -->
                <div id="index">
                    <?php query_posts( 'post_type=Clients')?>

                   

                    <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>

                        <?php the_title(); ?>

                        <?php the_content(); ?>
                        <?php endwhile; ?>
                    <?php endif;?>

                </div>
                <!– /index –>
            </td>
            <td width="200" valign="top">
                <!-- Выводим на страницу sidebar_right -->
                <?php include(TEMPLATEPATH . '/sidebar_right.php'); ?>
                <!-- Конец вывода sidebar_right -->
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <!-- Выводим на страницу footer -->
                <?php get_footer(); ?>
                <!-- Конец вывода footer --></td>
        </tr>
    </table>
</div>
</body>
</html>
