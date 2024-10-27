<?php

/*
 *  Contains misc function
 */

// Prevent direct access to file
defined('ABSPATH') or die("Cheatin' uh?");

/**
 * 
 * @return string
 */
function getPageType() {

    if (is_home() || is_front_page()) {
        return 'home';
    }
    if (is_page()) {
        return 'page';
    }
    if (is_category()) {
        return 'category';
    }
    if (is_archive()) {
        return 'archive';
    }
    if (is_search()) {
        return 'search';
    }
    if (is_404()) {
        return 'errorPage';
    }
    if (is_single()) {
        return get_post_type(get_the_ID());
    }

    return '';
}



/**
 * Return public URL of home page and first category, page and post 
 */
function getUrlsForExperiment() {
    $post_types = get_post_types();
    $links = array();
    //home page
    $links['Home'] = get_home_url();

    //category page url
    $category = get_categories(array('number' => 1));
    if (sizeof($category) == 1) {
        $links['Category'] = get_category_link($category[0]->term_id);
    }

    $temp = wp_get_archives(array('format' => 'link', 'echo' => 0, 'limit' => 1, 'order' => 'DESC'));
    preg_match('/href\s*=\s*[\'\"]([^\'\"]+)[\'\"]/', $temp, $matches);
    if (sizeof($matches) == 2) {
        $links['Archive'] = $matches[1];
    }

    //All posts
    foreach ($post_types as $post_type) {
        $posts = get_posts(array('numberposts' => 1, 'post_type' => $post_type));
        if (is_array($posts) && !empty($posts)) {
            $post = $posts[0];
            $links[ucwords(strtolower($post_type))] = get_permalink($post->ID);
        }
    }
    return $links;
}

function cleanUrl($url) {

    $parse = parse_url($url);
    $clean_url = $parse['host'];

    if (!empty($parse['path'])) {
        $clean_url .= $parse['path'];
    }

    return rtrim($clean_url);
}

function detectPlatform() {
    $detect = new Mobile_Detect();
    $platform = "desktop";
    if ($detect->isTablet()) {
        $platform = "tablet";
    } else if ($detect->isMobile()) {
        $platform = "mobile";
    }

    return $platform;
}
