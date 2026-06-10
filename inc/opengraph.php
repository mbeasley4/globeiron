<?php
/**
 * Open Graph + Twitter Card meta tags
 *
 * Acts as a fallback when Yoast SEO's Open Graph module is disabled or
 * Yoast is not installed. When Yoast handles OG this file is a complete
 * no-op — nothing is output and no hooks are registered.
 *
 * Covers: homepage, singular posts/pages/projects, blog index, archives.
 *
 * @package Globeiron
 */

declare(strict_types=1);

// ─── Robots meta: noindex on paged archives ───────────────────────────────────
// Page 2+ of archives create near-duplicate content. noindex,follow keeps link
// equity flowing through pagination without indexing thin pages.

add_action('wp_head', function (): void {
    if (! is_paged()) {
        return;
    }
    // Let Yoast handle this if its meta module is active
    if (class_exists('WPSEO_Options') && WPSEO_Options::get('noindex-archive-wpseo', false)) {
        return;
    }
    if (is_home() || is_archive() || is_post_type_archive()) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    }
}, 1);

// ─── Guard: let Yoast own OG when its social module is active ─────────────────

function globeiron_yoast_handles_og(): bool {
    if (! class_exists('WPSEO_Options')) {
        return false;
    }
    return (bool) WPSEO_Options::get('opengraph', false);
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

/**
 * Resolve the best available OG image for the current page.
 * Returns an array with 'url', 'width', 'height' (width/height may be 0).
 */
function globeiron_og_image(): array {
    $empty = ['url' => '', 'width' => 0, 'height' => 0];

    // 1. Featured image on singular content
    if (is_singular() && has_post_thumbnail()) {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
        if ($img) {
            return ['url' => $img[0], 'width' => (int) $img[1], 'height' => (int) $img[2]];
        }
    }

    // 2. ACF options field: og_default_image (Image field returning array)
    if (function_exists('get_field')) {
        $opt = get_field('og_default_image', 'option');
        if (is_array($opt) && ! empty($opt['url'])) {
            return [
                'url'    => $opt['url'],
                'width'  => (int) ($opt['width']  ?? 0),
                'height' => (int) ($opt['height'] ?? 0),
            ];
        }
    }

    // 3. Custom logo
    $logo_id = get_theme_mod('custom_logo');
    if ($logo_id) {
        $img = wp_get_attachment_image_src($logo_id, 'full');
        if ($img) {
            return ['url' => $img[0], 'width' => (int) $img[1], 'height' => (int) $img[2]];
        }
    }

    return $empty;
}

/**
 * Resolve the best available meta description for the current page.
 */
function globeiron_og_description(): string {
    if (is_singular()) {
        global $post;
        $text = $post ? get_the_excerpt($post) : '';
        if (! $text && $post) {
            $text = wp_trim_words(wp_strip_all_tags((string) $post->post_content), 30, '…');
        }
        return $text ? wp_trim_words(wp_strip_all_tags($text), 30, '…') : (string) get_bloginfo('description');
    }

    if (is_home() || is_front_page()) {
        return (string) get_bloginfo('description');
    }

    $desc = (string) get_the_archive_description();
    if ($desc) {
        return wp_trim_words(wp_strip_all_tags($desc), 30, '…');
    }

    return (string) get_bloginfo('description');
}

/**
 * Extract a @handle from a Twitter/X profile URL stored in ACF options.
 * Returns empty string if no usable handle is found.
 */
function globeiron_twitter_handle(): string {
    if (! function_exists('get_field')) {
        return '';
    }
    $url = (string) (get_field('social_twitter', 'option') ?: '');
    if (! $url) {
        return '';
    }
    // Handle plain @handle, full URL, or x.com URL
    if (str_starts_with($url, '@')) {
        return $url;
    }
    $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
    $handle = basename($path);
    return $handle ? '@' . $handle : '';
}

// ─── Output ───────────────────────────────────────────────────────────────────

add_action('wp_head', function (): void {

    if (globeiron_yoast_handles_og()) {
        return;
    }

    $site_name = (string) get_bloginfo('name');
    $locale    = str_replace('-', '_', (string) get_bloginfo('language'));
    $image     = globeiron_og_image();
    $desc      = esc_attr(globeiron_og_description());
    $twitter   = globeiron_twitter_handle();

    // Article-only variables — initialised here so they're always defined
    $pub_time = '';
    $mod_time = '';
    $section  = '';
    $author   = '';

    // ── Determine page-type values ────────────────────────────────────────────

    if (is_front_page()) {
        $og_type  = 'website';
        $og_url   = esc_url(trailingslashit(home_url('/')));
        $og_title = esc_attr($site_name . (get_bloginfo('description') ? ' — ' . get_bloginfo('description') : ''));

    } elseif (is_singular('post')) {
        global $post;
        $og_type  = 'article';
        $og_url   = esc_url((string) get_permalink());
        $og_title = esc_attr((string) get_the_title());

        // Article-specific extra tags rendered after the shared block below
        $pub_time = esc_attr((string) get_the_date('c', $post));
        $mod_time = esc_attr((string) get_the_modified_date('c', $post));
        $cats     = get_the_category($post->ID);
        $section  = $cats ? esc_attr($cats[0]->name) : '';
        $author   = esc_attr((string) get_the_author_meta('display_name', (int) $post->post_author));

    } elseif (is_singular()) {
        $og_type  = 'website';
        $og_url   = esc_url((string) get_permalink());
        $og_title = esc_attr((string) get_the_title());

    } elseif (is_home()) {
        $og_type  = 'website';
        $og_url   = esc_url(get_post_type_archive_link('post') ?: home_url('/blog/'));
        $og_title = esc_attr($site_name . ' — Blog');

    } elseif (is_post_type_archive('project')) {
        $og_type  = 'website';
        $og_url   = esc_url((string) get_post_type_archive_link('project'));
        $og_title = esc_attr($site_name . ' — Our Work');

    } elseif (is_archive()) {
        $og_type  = 'website';
        $og_url   = esc_url((string) get_pagenum_link(1, false));
        $og_title = esc_attr((string) wp_strip_all_tags((string) get_the_archive_title()));

    } else {
        return; // 404, search — skip
    }

    // ── Shared Open Graph tags ────────────────────────────────────────────────
    ?>
<!-- Open Graph -->
<meta property="og:site_name" content="<?php echo $site_name; ?>">
<meta property="og:locale"    content="<?php echo esc_attr($locale); ?>">
<meta property="og:type"      content="<?php echo esc_attr($og_type); ?>">
<meta property="og:title"     content="<?php echo $og_title; ?>">
<meta property="og:description" content="<?php echo $desc; ?>">
<meta property="og:url"       content="<?php echo $og_url; ?>">
<?php if ($image['url']) : ?>
<meta property="og:image"     content="<?php echo esc_url($image['url']); ?>">
<?php if ($image['width'])  : ?><meta property="og:image:width"  content="<?php echo $image['width']; ?>"><?php endif; ?>
<?php if ($image['height']) : ?><meta property="og:image:height" content="<?php echo $image['height']; ?>"><?php endif; ?>
<meta property="og:image:alt" content="<?php echo $og_title; ?>">
<?php endif; ?>
<?php

    // ── Article-specific Open Graph tags ──────────────────────────────────────
    if ($og_type === 'article') :
?>
<meta property="article:published_time" content="<?php echo $pub_time; ?>">
<meta property="article:modified_time"  content="<?php echo $mod_time; ?>">
<?php if (! empty($section)) : ?>
<meta property="article:section"        content="<?php echo $section; ?>">
<?php endif; ?>
<?php if (! empty($author)) : ?>
<meta property="article:author"         content="<?php echo $author; ?>">
<?php endif; ?>
<?php
    endif;

    // ── Twitter Card tags ─────────────────────────────────────────────────────
?>
<!-- Twitter / X Card -->
<meta name="twitter:card"        content="<?php echo $image['url'] ? 'summary_large_image' : 'summary'; ?>">
<?php if ($twitter) : ?>
<meta name="twitter:site"        content="<?php echo esc_attr($twitter); ?>">
<?php endif; ?>
<meta name="twitter:title"       content="<?php echo $og_title; ?>">
<meta name="twitter:description" content="<?php echo $desc; ?>">
<?php if ($image['url']) : ?>
<meta name="twitter:image"       content="<?php echo esc_url($image['url']); ?>">
<meta name="twitter:image:alt"   content="<?php echo $og_title; ?>">
<?php endif; ?>
<?php

}, 5);
