<?php
namespace LonelyPlanet\Rizzo;

defined('ABSPATH') || exit;

add_action('plugins_loaded', function () {

    include __DIR__ . '/inc/RizzoMenuPlugin.php';
    include __DIR__ . '/inc/RizzoHeaderWidgets.php';

    $wprizzomenu = new RizzoMenuPlugin();

    new RizzoHeaderWidgets(
        array(
            'name'          => 'Rizzo Header',
            'id'            => 'rizzo-header',
            'description'   => '',
            'class'         => '',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '',
            'after_title'   => ''
        ),
        '<div class="wv--split--right"><div class="wv--nav--inline split--right__inner">%s</div></div>'
    );

}, 50);
