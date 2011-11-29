<div id="footer">
    <div id="copyright">2011 All rights reserved.</div>
    <div id="footer-menu">
        <?php wp_nav_menu( $args = array(
  'menu'            => 'Top',              //(string) Название выводимого меню (указывается в админке при создании меню, приоритетнее чем указанное местоположение theme_location - если указано, то параметр theme_location игнорируется)
  'container'       => 'div',           //(string) Контейнет меню. Обворачиватель ul. Указывается тег контейнера (по умолчанию в тег div)
  'container_class' => '',              //(string) class контейнера (div тега)
  'container_id'    => '',              //(string) id контейнера (div тега)
  'menu_class'      => 'menu',          //(string) class самого меню (ul тега)
  'menu_id'         => '',              //(string) id самого меню (ul тега)
  'echo'            => true,            //(boolean) Выводить на экран или возвращать для обработки
  'fallback_cb'     => 'wp_page_menu',  //(string) Используемая (резервная) функция, если меню не существует (не удалось получить)
  'before'          => '',              //(string) Текст перед <a> каждой ссылки
  'after'           => '',              //(string) Текст после </a> каждой ссылки
  'link_before'     => '',              //(string) Текст перед анкором (текстом) ссылки
  'link_after'      => '',              //(string) Текст после анкора (текста) ссылки
  'depth'           => 0,               //(integer) Глубина вложенности (0 - неограничена, 2 - двухуровневое меню)
  'walker'          => '',                //(object) Класс собирающий меню. Default: new Walker_Nav_Menu
  'theme_location'  => '')             //(string) Расположение меню в шаблоне. (указывается ключь которым было зарегистрировано меню в функции register_nav_menus
                        );?>
        
    </div>
</div>



<?php wp_footer(); ?>
</body>
</html>