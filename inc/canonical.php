<?php
/**
 * Canonical URL — sitewide fallback
 *
 * Yoast SEO (wordpress-seo) outputs canonical tags when active. This hook
 * runs only when Yoast is absent so there are never duplicate canonicals.
 *
 * @package Globeiron
 */

declare(strict_types=1);

add_action('wp_head', function (): void {

    // Yoast handles canonicals when active — let it.
    if (class_exists('WPSEO_Frontend') || defined('WPSEO_VERSION')) {
        return;
    }

    $canonical = globeiron_canonical_url();

    if ($canonical === '') {
        return;
    }

    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";

}, 1);

function globeiron_canonical_url(): string {

    // Paged archives always point back to page 1.
    $paged = max(1, (int) get_query_var('paged', 1));

    if (is_singular()) {
        $url = (string) get_permalink();

        // Multi-page posts: canonical is always page 1 of the post.
        $page = max(1, (int) get_query_var('page', 1));
        if ($page > 1) {
            $url = (string) get_permalink();
        }

        return $url;
    }

    if (is_home() && ! is_front_page()) {
        $posts_page_id = (int) get_option('page_for_posts');
        $base          = $posts_page_id > 0 ? (string) get_permalink($posts_page_id) : trailingslashit(home_url('/'));
        return $paged > 1 ? (string) get_pagenum_link($paged) : $base;
    }

    if (is_front_page()) {
        return trailingslashit(home_url('/'));
    }

    if (is_post_type_archive()) {
        $base = (string) get_post_type_archive_link((string) get_query_var('post_type'));
        return $paged > 1 ? (string) get_pagenum_link($paged) : $base;
    }

    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        if (! $term instanceof WP_Term) {
            return '';
        }
        $base = (string) get_term_link($term);
        return $paged > 1 ? (string) get_pagenum_link($paged) : $base;
    }

    if (is_author()) {
        $author_id = (int) get_queried_object_id();
        $base      = (string) get_author_posts_url($author_id);
        return $paged > 1 ? (string) get_pagenum_link($paged) : $base;
    }

    if (is_date()) {
        return $paged > 1 ? (string) get_pagenum_link($paged) : (string) get_permalink();
    }

    // Search, 404, and other contexts: omit canonical.
    return '';
}
