<?php
namespace LonelyPlanet\Rizzo;

defined('ABSPATH') || exit;

class RizzoMenuPlugin
{
    protected $menu_location;

    public function __construct()
    {

        $this->menu_location = 'rizzo';

        // Register a menu to go into the body header.
        register_nav_menu($this->menu_location, 'Rizzo Menu');

        // This filter is in the WP Rizzo plugin.
        add_filter('rizzo_html_body-endpoint', array($this, 'insert_menu'), 10, 1);

        if (is_admin()) {
            add_action('admin_init', array($this, 'admin_init'));
        }
    }

    public function admin_message($message, $class_name = 'updated', $debug = false)
    {
        if ( ! in_array($class_name, array('update-nag', 'updated', 'error'))) {
            $class_name = 'updated';
        }

        if ($debug) {
            $message = '<pre>' . var_export($message, true) . '</pre>';
        } elseif ($class_name != 'update-nag') {
            $message = \wpautop($message);
        }

        add_action('admin_notices', function () use ($message, $class_name) {
            echo '<div class="', $class_name, '">', $message, '</div>';
        });
    }

    public function admin_nag($message, $debug = false)
    {
        $this->admin_message($message, 'update-nag', $debug);
    }

    public function admin_error($message, $debug = false)
    {
        $this->admin_message($message, 'error', $debug);
    }

    public function admin_notice($message, $debug = false)
    {
        $this->admin_message($message, 'updated', $debug);
    }

    public function admin_debug($message)
    {
        $this->admin_message($message, 'updated', true);
    }

    public function admin_init()
    {

        $show_nag = false;

        if (isset($_POST['menu-locations'][$this->menu_location])) {

            $show_nag = (int)$_POST['menu-locations'][$this->menu_location] === 0;

        } else {

            $nav_menus = get_nav_menu_locations();
            $show_nag = isset($nav_menus[$this->menu_location]) && $nav_menus[$this->menu_location] === 0;

        }

        if ($show_nag) {

            $this->admin_nag(
                sprintf(
                    'Please assign a menu to the <a href="%s">rizzo menu location</a>.',
                    admin_url('nav-menus.php?action=locations')
                )
            );

        }

    }

    public function insert_menu($html)
    {
        $placeholder_text = 'Rizzo Menu';
        $placeholder_comment = '<!--' . $placeholder_text . '-->';

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadHTML($html); // This may emit lots of warnings if $html isn't valid. You may want to silence them with @.
    
        $dom->getElementById('js-nav--primary')->appendChild(
            $dom->createComment($placeholder_text)
        );

        // WP Engine is using PHP 5.3.2, which is four years old as of 2014.
        // The saveHTML method didn't take a node parameter until 5.3.6.
        $html = preg_replace('#^.*<body>(.*)</body>.*$#ms', '$1', $dom->saveHTML());

        include_once 'MenuWalker.php';

        /*
            'theme_location'  => '',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        */

        $menu = wp_nav_menu(
            array(
                'theme_location'  => 'rizzo',
                'echo'            => false,
                'fallback_cb'     => false,
                'container'       => false,
                'menu_id'         => 'js-nav--primary',
                // 'link_before'     => '<span itemprop="name">',
                // 'link_after'      => '</span>',
                // 'items_wrap'      => '<div itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" role="navigation">%3$s</div>',
                'items_wrap'      => '%3$s',
                'depth'           => 0,
                'walker'          => new MenuWalker()
            )
        );

        $html = str_replace($placeholder_comment, $menu, $html);

        return $html;
    }

}

$wprizzomenu = new RizzoMenuPlugin();
