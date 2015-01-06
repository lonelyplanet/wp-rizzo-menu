<?php
namespace LonelyPlanet\Rizzo;

class RizzoHeaderWidgets extends RizzoWidgets
{
    public function __construct(array $sidebar_args, $format = null)
    {
        parent::__construct($sidebar_args, $format);

        add_filter('rizzo_html_post-header-endpoint', array($this, 'insert_widget_area'), 9, 1);
    }

    public function insert_widget_area($html)
    {
        if ( ! isset( $html ) ||  $html === '' ) {
            return;
        }

        $classname = 'nav--user';
        $placeholder_text = 'Rizzo Header Widgets';
        $placeholder_comment = '<!--' . $placeholder_text . '-->';

        $use_errors = libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadHTML($html);

        libxml_clear_errors();
        libxml_use_internal_errors($use_errors);// Set it back to what it was.

        $xpath = new \DOMXPath($dom);
        $results = $xpath->query("//*[contains(@class, '" . $classname . "')]");

        if ($results->length > 0) {

            $nav_user = $results->item(0);

            $nav_user->appendChild(
                $dom->createComment($placeholder_text)
            );

            $html = preg_replace('#^.*<body>(.*)</body>.*$#ms', '$1', $dom->saveHTML());

            $html = str_replace($placeholder_comment, $this->get_output(), $html);

        }

        return $html;
    }
}
