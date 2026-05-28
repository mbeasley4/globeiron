<?php
/**
 * Section - Post Listing block render template.
 *
 * Filterable, paginated React grid. Outputs either the blog grid or
 * projects grid depending on the `listing_post_type` field value.
 *
 * @package Globeiron
 */

declare(strict_types=1);

$listing_post_type = get_field('listing_post_type') ?: 'post';
$grid_title        = get_field('grid_title');
$grid_content      = get_field('grid_content');
$per_page          = 21;

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-post-listing',
]);

// ── Blog posts ────────────────────────────────────────────────────────────────
if ($listing_post_type === 'post') {

    $current_page = max(1, (int) (get_query_var('paged') ?: 1));

    $query = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $current_page,
    ]);

    $featured_cat_obj = get_category_by_slug('featured-post');
    $exclude_cat_ids  = $featured_cat_obj ? [$featured_cat_obj->term_id] : [];
    $top_categories   = get_categories([
        'hide_empty' => true,
        'number'     => 5,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'exclude'    => $exclude_cat_ids,
    ]);

    wp_enqueue_script(
        'globeiron-blog',
        GLOBEIRON_URI . '/dist/js/blog.js',
        ['wp-element'],
        GLOBEIRON_VERSION,
        true
    );

    wp_localize_script('globeiron-blog', 'globeironBlog', [
        'ajaxUrl'      => admin_url('admin-ajax.php'),
        'nonce'        => wp_create_nonce('globeiron_blog'),
        'blogUrl'      => get_pagenum_link(1),
        'perPage'      => $per_page,
        'totalPages'   => (int) $query->max_num_pages,
        'currentPage'  => $current_page,
        'initialPosts' => globeiron_format_posts($query->posts),
        'categories'   => array_map(fn($c) => ['id' => $c->term_id, 'name' => $c->name], $top_categories),
    ]);
    ?>
    <div <?php echo $wrapper_attributes; ?>>
      <div class="container section">
        <?php if ($grid_title || $grid_content) : ?>
          <div class="blog-listing__header">
            <?php if ($grid_title) : ?>
              <h2 class="blog-listing__heading"><?php echo esc_html($grid_title); ?></h2>
            <?php endif; ?>
            <?php if ($grid_content) : ?>
              <div class="blog-listing__description"><?php echo wp_kses_post($grid_content); ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <div id="blog-grid-root"></div>
      </div>
    </div>
    <?php

// ── Projects ──────────────────────────────────────────────────────────────────
} else {

    // Featured project (most recent) — shown above the grid, excluded from results
    $feat_results = get_posts(['post_type' => 'project', 'numberposts' => 1, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC', 'fields' => 'ids']);
    $featured_id  = $feat_results[0] ?? 0;

    $query_args = [
        'post_type'      => 'project',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if ($featured_id) {
        $query_args['post__not_in'] = [$featured_id];
    }

    $query = new WP_Query($query_args);

    // Top project categories by post count
    $raw_types  = get_terms(['taxonomy' => 'project_category', 'hide_empty' => true, 'number' => 6, 'orderby' => 'count', 'order' => 'DESC']);
    $types_data = array_map(fn($t) => ['id' => $t->term_id, 'name' => $t->name], is_array($raw_types) ? $raw_types : []);

    wp_enqueue_script(
        'globeiron-projects',
        GLOBEIRON_URI . '/dist/js/projects.js',
        ['wp-element'],
        GLOBEIRON_VERSION,
        true
    );

    wp_localize_script('globeiron-projects', 'globeironProjects', [
        'ajaxUrl'      => admin_url('admin-ajax.php'),
        'nonce'        => wp_create_nonce('globeiron_projects'),
        'archiveUrl'   => get_post_type_archive_link('project') ?: home_url('/our-work/'),
        'perPage'      => $per_page,
        'totalPages'   => (int) $query->max_num_pages,
        'currentPage'  => 1,
        'initialPosts' => globeiron_format_projects($query->posts),
        'featuredId'   => $featured_id,
        'types'        => $types_data,
    ]);
    ?>
    <div <?php echo $wrapper_attributes; ?>>
      <div class="container section">
        <?php if ($grid_title || $grid_content) : ?>
          <div class="blog-listing__header">
            <?php if ($grid_title) : ?>
              <h2 class="blog-listing__heading"><?php echo esc_html($grid_title); ?></h2>
            <?php endif; ?>
            <?php if ($grid_content) : ?>
              <div class="blog-listing__description"><?php echo wp_kses_post($grid_content); ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <div id="projects-grid-root"></div>
      </div>
    </div>
    <?php
}
