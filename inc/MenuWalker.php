<?php
namespace LonelyPlanet\Rizzo;

/*
This is the basic structure of the HTMl that needs to be replicated with this Walker.

<nav aria-label="Global navigation" class="wv--nav--inline nav--primary" id="js-nav--primary" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" role="navigation">
    <h6 class="accessibility">Global navigation</h6>

    <div class="nav__item nav__item--primary nav__submenu__trigger">

        <a class="nav__item nav__item--submenu js-nav-item" href="http://www.lonelyplanet.com/destinations" itemprop="url"><span itemprop="name">Destinations</span></a>

        <div class="nav__submenu nav__submenu--destinations">

            <div class="nav--stacked nav__submenu__content js-submenu icon--tapered-arrow-up--after icon--white--after">
                <a class="js-nav-item nav__item nav__submenu__item nav__submenu__item-- nav__submenu__link" href="http://www.lonelyplanet.com/africa" itemprop="url"><span itemprop="name">Africa</span></a>
                <a class="js-nav-item nav__item nav__submenu__item nav__submenu__item-- nav__submenu__link" href="http://www.lonelyplanet.com/antarctica" itemprop="url"><span itemprop="name">Antarctica</span></a>
                <a class="js-nav-item nav__item nav__submenu__item nav__submenu__item-- nav__submenu__link" href="http://www.lonelyplanet.com/asia" itemprop="url"><span itemprop="name">Asia</span></a>
                <a class="js-nav-item nav__item nav__submenu__item nav__submenu__item-- nav__submenu__link" href="http://www.lonelyplanet.com/caribbean" itemprop="url"><span itemprop="name">Caribbean</span></a>
            </div>

        </div>

    </div>

</nav>
*/

class MenuWalker extends \Walker_Nav_Menu
{
    // Handle menu items.
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array)$item->classes;

        $classes[] = 'menu-item-' . $item->ID;

        if ($depth === 0) {

            $classes[] = 'nav__item';
            $classes[] = 'nav__item--primary';
            $classes[] = 'nav__submenu__trigger';

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            $output .= '<div' . $id . $class_names . '>';

        }

        $atts = array(
            'title'  => ! empty($item->attr_title) ? $item->attr_title : '',
            'target' => ! empty($item->target)     ? $item->target     : '',
            'rel'    => ! empty($item->xfn)        ? $item->xfn        : '',
            'href'   => ! empty($item->url)        ? $item->url        : '',
        );

        if ($depth === 0) {

            $atts['class']  = 'nav__item nav__item--submenu js-nav-item';            

        } else {

            $atts['class'] = implode(
                ' ',
                array_merge(
                    (array)$item->classes,
                    explode(' ', 'js-nav-item nav__item nav__submenu__item nav__submenu__item-- nav__submenu__link')
                )
            );         

        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';

        foreach ($atts as $attr => $value) {
            if ( ! empty($value)) {
                $value = 'href' === $attr ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = sprintf(
            '%s<a %s>%s%s%s</a>%s',
            $args->before,
            $attributes,
            $args->link_before,
            apply_filters('the_title', $item->title, $item->ID),
            $args->link_after,
            $args->after
        );

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = array())
    {
        if ($depth === 0)
            $output .= '</div>';
    }

    // Handle sub menu.
    public function start_lvl(&$output, $depth = 0, $args = array())
    {    
        $classes = array(
            'nav--stacked',
            'nav__submenu__content',
            'js-submenu',
            'icon--white--after',
        );

        if ($depth === 0)
            $classes[] = 'icon--tapered-arrow-up--after';
        
        $output .= '<div class="nav__submenu"><div class="' . implode(' ', $classes) . '">';

    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= '</div></div>';
    }

}
