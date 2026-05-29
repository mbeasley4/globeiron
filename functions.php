<?php
/**
 * Globeiron theme functions.
 *
 * @package Globeiron
 */

declare(strict_types=1);

define('GLOBEIRON_VERSION', '1.0.8');
define('GLOBEIRON_DIR', get_template_directory());
define('GLOBEIRON_URI', get_template_directory_uri());

require_once GLOBEIRON_DIR . '/inc/post-types.php';
require_once GLOBEIRON_DIR . '/inc/meta-boxes.php';
require_once GLOBEIRON_DIR . '/inc/acf-blocks.php';
require_once GLOBEIRON_DIR . '/inc/block-fields.php';
require_once GLOBEIRON_DIR . '/inc/options.php';

// ─── Theme setup ─────────────────────────────────────────────────────────────
add_action('after_setup_theme', function (): void {
    load_theme_textdomain('globeiron', GLOBEIRON_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Block editor / Gutenberg support
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('appearance-tools');

    add_editor_style('dist/css/editor.css');

    // Generate WebP versions of uploaded images (WP 5.8+)
    add_theme_support('webp-upload');

    // Navigation menus
    register_nav_menus([
        'primary'         => __('Primary Navigation', 'globeiron'),
        'footer'          => __('Footer — Legal / Bottom Bar', 'globeiron'),
        'footer-links'    => __('Footer — Quick Links Column', 'globeiron'),
        'footer-services' => __('Footer — Services Column', 'globeiron'),
    ]);
});

// ─── Fallback image URL (used when no featured image is set) ──────────────────
function globeiron_no_image_url(): string {
    return GLOBEIRON_URI . '/img/no-image.png';
}

// ─── Shared post data formatter ───────────────────────────────────────────────
function globeiron_format_posts(array $posts): array {
    $out         = [];
    $no_image    = globeiron_no_image_url();
    foreach ($posts as $post) {
        $thumb_id  = get_post_thumbnail_id($post->ID);
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium_large') : '';
        $thumb_alt = $thumb_id ? (string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';

        $cats = array_map(fn($c) => [
            'id'   => $c->term_id,
            'name' => $c->name,
            'url'  => get_category_link($c->term_id),
        ], array_slice(get_the_category($post->ID), 0, 2));

        $out[] = [
            'id'            => $post->ID,
            'title'         => html_entity_decode(get_the_title($post->ID), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'permalink'     => get_permalink($post->ID),
            'excerpt'       => html_entity_decode(wp_trim_words(get_the_excerpt($post), 20, '…'), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'date'          => get_the_date('', $post),
            'dateIso'       => get_the_date('c', $post),
            'featuredImage' => ['url' => $thumb_url ?: $no_image, 'alt' => $thumb_alt],
            'categories'    => $cats,
        ];
    }
    return $out;
}

// ─── Disable comments ────────────────────────────────────────────────────────
add_action('init', function (): void {
    foreach (get_post_types() as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }
});

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open',    '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);

add_action('admin_menu', function (): void {
    remove_menu_page('edit-comments.php');
});

add_action('admin_bar_menu', function (WP_Admin_Bar $bar): void {
    $bar->remove_node('comments');
}, 999);

add_action('admin_init', function (): void {
    global $pagenow;
    if (in_array($pagenow, ['edit-comments.php', 'comment.php'], true)) {
        wp_redirect(admin_url());
        exit;
    }
});

// ─── Post permalink base: /blog/%postname%/ ───────────────────────────────────
// Runs once on first load after activation; tracked by a site option so it
// never fires again (avoids flushing rewrite rules on every request).
add_action('init', function (): void {
    if (get_option('globeiron_permalink_v2') === '1') {
        return;
    }

    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/blog/%postname%/');
    $wp_rewrite->flush_rules();
    update_option('globeiron_permalink_v2', '1');
}, 99);

// ─── Legacy redirect: /{slug}/ → /blog/{slug}/ ───────────────────────────────
// After the permalink change, old URLs return 404. This catches those requests,
// looks up the slug against published posts, and issues a permanent redirect.
add_action('template_redirect', function (): void {
    if (! is_404()) {
        return;
    }

    $path     = trim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $segments = explode('/', $path);

    // Only handle single-segment paths (e.g. /my-post-slug/) —
    // multi-segment paths are already namespaced and not legacy posts.
    if (count($segments) !== 1 || $segments[0] === '') {
        return;
    }

    $post = get_page_by_path($segments[0], OBJECT, 'post');

    if ($post instanceof WP_Post && $post->post_status === 'publish') {
        wp_redirect(get_permalink($post->ID), 301);
        exit;
    }
});

// ─── Blog + Projects archives: 21 items per page ─────────────────────────────
add_action('pre_get_posts', function (WP_Query $query): void {
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }
    if ($query->is_home() || $query->is_post_type_archive('project')) {
        $query->set('posts_per_page', 21);
    }
});

// ─── AJAX: paginated posts for the React blog grid ────────────────────────────
function globeiron_ajax_get_posts(): void {
    check_ajax_referer('globeiron_blog', 'nonce');

    $page     = max(1, (int) ($_POST['page']     ?? 1));
    $per_page = max(1, min(24, (int) ($_POST['per_page'] ?? 21)));
    $category = (int) ($_POST['category'] ?? 0);
    $search   = sanitize_text_field($_POST['search'] ?? '');

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if ($category > 0) {
        $args['cat'] = $category;
    }

    if ($search !== '') {
        $args['s'] = $search;
    }

    $query = new WP_Query($args);

    wp_send_json([
        'posts'       => globeiron_format_posts($query->posts),
        'currentPage' => $page,
        'totalPages'  => (int) $query->max_num_pages,
    ]);
}
add_action('wp_ajax_globeiron_get_posts',        'globeiron_ajax_get_posts');
add_action('wp_ajax_nopriv_globeiron_get_posts', 'globeiron_ajax_get_posts');

// ─── Projects CPT data formatter ─────────────────────────────────────────────
function globeiron_format_projects(array $posts): array {
    $out      = [];
    $no_image = globeiron_no_image_url();
    foreach ($posts as $post) {
        $thumb_id  = get_post_thumbnail_id($post->ID);
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium_large') : '';
        $thumb_alt = $thumb_id ? (string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';

        $out[] = [
            'id'            => $post->ID,
            'title'         => html_entity_decode(get_the_title($post->ID), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'permalink'     => get_permalink($post->ID),
            'excerpt'       => html_entity_decode(wp_trim_words(get_the_excerpt($post), 20, '…'), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'featuredImage' => ['url' => $thumb_url ?: $no_image, 'alt' => $thumb_alt],
            'year'          => get_post_meta($post->ID, '_project_year', true),
        ];
    }
    return $out;
}

// ─── AJAX: paginated projects for the React projects grid ─────────────────────
function globeiron_ajax_get_projects(): void {
    check_ajax_referer('globeiron_projects', 'nonce');

    $page     = max(1, (int) ($_POST['page']     ?? 1));
    $per_page = max(1, min(24, (int) ($_POST['per_page'] ?? 21)));
    $search   = sanitize_text_field($_POST['search'] ?? '');
    $type_id  = max(0, (int) ($_POST['type']    ?? 0));
    $exclude  = max(0, (int) ($_POST['exclude'] ?? 0));

    $args = [
        'post_type'      => 'project',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ($search !== '') {
        $args['s'] = $search;
    }

    if ($type_id > 0) {
        $args['tax_query'] = [['taxonomy' => 'project_category', 'field' => 'term_id', 'terms' => $type_id]];
    }

    if ($exclude > 0) {
        $args['post__not_in'] = [$exclude];
    }

    $query = new WP_Query($args);

    wp_send_json([
        'posts'       => globeiron_format_projects($query->posts),
        'currentPage' => $page,
        'totalPages'  => (int) $query->max_num_pages,
    ]);
}
add_action('wp_ajax_globeiron_get_projects',        'globeiron_ajax_get_projects');
add_action('wp_ajax_nopriv_globeiron_get_projects', 'globeiron_ajax_get_projects');

// ─── Preconnect hints ────────────────────────────────────────────────────────
add_action('wp_head', function (): void {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com">' . "\n";
}, 1);

// Make Google Fonts load non-render-blocking
add_filter('style_loader_tag', function (string $html, string $handle): string {
    if ($handle !== 'globeiron-fonts') {
        return $html;
    }
    $href = 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap';
    return '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n"
        . '<noscript><link rel="stylesheet" href="' . esc_url($href) . '"></noscript>' . "\n";
}, 10, 2);

// ─── Enqueue assets ──────────────────────────────────────────────────────────
add_action('wp_enqueue_scripts', function (): void {
    // Load Google Fonts non-render-blocking via preload swap trick
    wp_enqueue_style(
        'globeiron-fonts',
        'https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'globeiron-main',
        GLOBEIRON_URI . '/dist/css/main.css',
        ['globeiron-fonts'],
        GLOBEIRON_VERSION
    );

    wp_enqueue_script(
        'gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
        [],
        '3.12.5',
        true
    );

    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
        ['gsap'],
        '3.12.5',
        true
    );

    wp_enqueue_script(
        'globeiron-main',
        GLOBEIRON_URI . '/dist/js/main.js',
        ['gsap', 'gsap-scrolltrigger'],
        GLOBEIRON_VERSION,
        true
    );

    // Blog archive: React-powered grid
    if (is_home()) {
        global $wp_query;
        $per_page = 21;

        wp_enqueue_script(
            'globeiron-blog',
            GLOBEIRON_URI . '/dist/js/blog.js',
            ['wp-element'],
            GLOBEIRON_VERSION,
            true
        );

        $featured_cat_obj = get_category_by_slug('featured-post');
        $exclude_cat_ids  = $featured_cat_obj ? [$featured_cat_obj->term_id] : [];
        $top_categories   = get_categories([
            'hide_empty' => true,
            'number'     => 5,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'exclude'    => $exclude_cat_ids,
        ]);

        wp_localize_script('globeiron-blog', 'globeironBlog', [
            'ajaxUrl'      => admin_url('admin-ajax.php'),
            'nonce'        => wp_create_nonce('globeiron_blog'),
            'blogUrl'      => get_pagenum_link(1),
            'perPage'      => $per_page,
            'totalPages'   => (int) $wp_query->max_num_pages,
            'currentPage'  => max(1, (int) get_query_var('paged', 1)),
            'initialPosts' => globeiron_format_posts($wp_query->posts),
            'categories'   => array_map(fn($c) => ['id' => $c->term_id, 'name' => $c->name], $top_categories),
        ]);
    }

    // Projects archive: React-powered grid
    if (is_post_type_archive('project')) {
        global $wp_query;
        $per_page = 21;

        wp_enqueue_script(
            'globeiron-projects',
            GLOBEIRON_URI . '/dist/js/projects.js',
            ['wp-element'],
            GLOBEIRON_VERSION,
            true
        );

        // Featured project (most recent) — shown in the page hero, excluded from grid
        $feat_results = get_posts(['post_type' => 'project', 'numberposts' => 1, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC', 'fields' => 'ids']);
        $featured_id  = $feat_results[0] ?? 0;

        // Top project categories by post count
        $raw_types  = get_terms(['taxonomy' => 'project_category', 'hide_empty' => true, 'number' => 6, 'orderby' => 'count', 'order' => 'DESC']);
        $types_data = array_map(fn($t) => ['id' => $t->term_id, 'name' => $t->name], is_array($raw_types) ? $raw_types : []);

        // Strip featured project from the SSR'd initial list
        $initial = array_values(array_filter(
            globeiron_format_projects($wp_query->posts),
            fn($p) => $featured_id === 0 || $p['id'] !== $featured_id
        ));

        wp_localize_script('globeiron-projects', 'globeironProjects', [
            'ajaxUrl'      => admin_url('admin-ajax.php'),
            'nonce'        => wp_create_nonce('globeiron_projects'),
            'archiveUrl'   => get_post_type_archive_link('project'),
            'perPage'      => $per_page,
            'totalPages'   => (int) $wp_query->max_num_pages,
            'currentPage'  => max(1, (int) get_query_var('paged', 1)),
            'initialPosts' => $initial,
            'featuredId'   => $featured_id,
            'types'        => $types_data,
        ]);
    }
});

// ─── Block editor assets ─────────────────────────────────────────────────────
add_action('enqueue_block_editor_assets', function (): void {
    wp_enqueue_script(
        'globeiron-editor',
        GLOBEIRON_URI . '/dist/js/editor.js',
        ['wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-hooks', 'wp-i18n'],
        GLOBEIRON_VERSION,
        true
    );

    wp_enqueue_style(
        'globeiron-editor',
        GLOBEIRON_URI . '/dist/css/editor.css',
        ['wp-edit-blocks'],
        GLOBEIRON_VERSION
    );
});

// ─── Core Heading guard: keep page-level H1s reserved for templates/heroes ───
add_filter('render_block_core/heading', function (string $block_content, array $block): string {
    $level = (int) ($block['attrs']['level'] ?? 2);

    if ($level !== 1) {
        return $block_content;
    }

    return str_replace(['<h1', '</h1>'], ['<h2', '</h2>'], $block_content);
}, 10, 2);

// ─── Custom block category ────────────────────────────────────────────────────
add_filter('block_categories_all', function (array $categories): array {
    array_unshift($categories, [
        'slug'  => 'globeiron',
        'title' => __('Globeiron', 'globeiron'),
        'icon'  => null,
    ]);

    return $categories;
});

// ─── Widget areas ────────────────────────────────────────────────────────────
add_action('widgets_init', function (): void {
    register_sidebar([
        'name'          => __('Primary Sidebar', 'globeiron'),
        'id'            => 'sidebar-primary',
        'description'   => __('Add widgets here.', 'globeiron'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    register_sidebar([
        'name'          => __('Footer Widgets', 'globeiron'),
        'id'            => 'footer-widgets',
        'description'   => __('Add footer widgets here.', 'globeiron'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
});
