<?php

// Using easy faders, consider replacing with either swiper, flex, sudoslider, superslides

add_filter( 'post_gallery', 'simple_post_gallery', 10, 2 );
function simple_post_gallery( $output, $attr) {
    global $post, $wp_locale;

    static $instance = 0;
    $instance++;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'large', // full, large, medium, thumbnail, custom
        'include'    => '',
        'exclude'    => '',

        'type'       => 'gallery', // stacked, gallery
        'effect'     => 'slide', // fade, backSlide, goDown, fadeUp
        'selector'   => 'slider',

    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_image($att_id, $size, false, false) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    // $itemwidth = $columns > 0 ? floor(100/$columns) : 100;

    if ( $type == 'gallery' ) :

        $permalink = false;
        $effect = "{$effect}";
        $selector = "{$selector}";
        $output .= apply_filters('gallery_style', "<ul data-slider='$effect' class='$selector gallery galleryId-{$id}'>");

    else : // stacked

        // link to attachment
        $permalink = true;
        $output .= apply_filters('gallery_style', "<ul class='$selector gallery galleryId-{$id}'>");

    endif; // $type == gallery

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {

        $imageURL = wp_get_attachment_image_src($id, $size, false)[0];

        $default_attr = array(
            // 'data-original' => wp_get_attachment_image_src($id, $size, false)[0],
            // 'src'           => get_stylesheet_directory_uri() . '/assets/images/gray.png',
            'src' => wp_get_attachment_image_src($id, $size, false)[0],
        );

        $link = isset( $attr['link'] ) && 'file' == $attr['link'] ? wp_get_attachment_image($id, $size, false, $default_attr) : wp_get_attachment_image($id, $size, false, $default_attr);

        $output .= "<li>";

        if ( $permalink ) {

            $output .= '<figure class="mfp-image">';
            $output .= '<a href="' . $imageURL . '" data-effect="mfp-fade-in-up">';
            $output .= $link;
            $output .= '</a>';
            $output .= '</figure>';

        } else{
            $output .= $link;
        }

        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} class='gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
        if ( $columns > 0 && ++$i % $columns == 0 )
            $output .= '';

        $output .= "</li>";
    }

    $output .= "</ul>"; // .slider

    return $output;
}

// Add class slide to attachments to enable slider
function add_slide_class_to_attachments( $attr ) {
    $attr['class'] .= ' slide lazy';
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'add_slide_class_to_attachments' );
