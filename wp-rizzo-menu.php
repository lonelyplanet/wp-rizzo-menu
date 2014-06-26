<?php
namespace LonelyPlanet\Rizzo;

defined('ABSPATH') || exit;

class RizzoMenuPlugin
{
    protected $plugin_file; // Keep this just in case I want to do activation hooks.

    public function __construct($plugin_file)
    {
        $this->plugin_file = $plugin_file;

        // add_action('admin_menu', array($this, 'admin_menu'));

        register_nav_menu('rizzo', 'Rizzo Menu');
        add_filter('rizzo_html_body-endpoint', array($this, 'insert_menu'), 10, 1);
    }

    public function admin_menu()
    {
        add_theme_page(
            'Rizzo Menu',
            'Rizzo Menu',
            'manage_options',
            $this->menu_slug = 'rizzo-menu',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page()
    {
        echo '<h1>Rizzo Menu</h1>';
    }

    public function insert_menu($html)
    {
        return $html;

        // Come back to this when Ben has the new endpoint for India created.

        $placeholder_text = 'Rizzo Menu';
        $placeholder_comment = '<!--' . $placeholder_text . '-->';

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        @$dom->loadHTML($html); // This emits lots of warnings, lets silence them.
    
        // $nav = $dom->getElementById('js-nav--primary');
        $xpath = new \DomXPath($dom);
        $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' nav-container ')]");
        // $nodes = $xpath->query("//div[@class='wv--split--left']");
        $nav = $nodes->item(0);

        $placeholder = $nav->cloneNode(false);

        // $placeholder = $dom->createElement('div');
        // $placeholder->setAttribute('id', 'js-nav--primary');
        $placeholder->appendChild($dom->createComment($placeholder_text));
        $nav->parentNode->replaceChild($placeholder, $nav);

        // WP Engine is using PHP 5.3.2, which is four years old as of 2014.
        // The saveHTML method didn't take a node parameter until 5.3.6.
        $html = preg_replace('#^.*<body>(.*)</body>.*$#ms', '$1', $dom->saveHTML());

        $menu = wp_nav_menu(
            array(
                'theme_location'  => 'rizzo',
                'echo'            => false,
                'fallback_cb'     => null,
                // 'walker' => null
            )
        );

        $html = str_replace($placeholder_comment, $menu, $html);

        return $html;
    }

}

$wprizzomenu = new RizzoMenuPlugin(WP_RIZZO_MENU_FILE);
