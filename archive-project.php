<?php
/**
 * Archive template: Project CPT — /our-work/
 *
 * @package Globeiron
 */

get_header(); ?>

<main id="main" class="site-content">

    <div class="container section">
        <?php if (have_posts()) : ?>
            <?php
            // Pull heading/description from a page with slug 'our-work' if it exists,
            // using the same grid_title / grid_content ACF fields as the blog page.
            $our_work_page   = get_page_by_path('our-work');
            $archive_page_id = $our_work_page ? $our_work_page->ID : 0;
            $listing_title   = ($archive_page_id && function_exists('get_field'))
                ? (get_field('grid_title', $archive_page_id) ?: __('Our Work', 'globeiron'))
                : __('Our Work', 'globeiron');
            $listing_content = ($archive_page_id && function_exists('get_field'))
                ? get_field('grid_content', $archive_page_id)
                : '';
            ?>
            <div class="blog-listing__header">
                <h2 class="blog-listing__heading"><?php echo esc_html($listing_title); ?></h2>
                <?php if ($listing_content) : ?>
                    <div class="blog-listing__description"><?php echo wp_kses_post($listing_content); ?></div>
                <?php endif; ?>
            </div>
            <div id="projects-grid-root"></div>
        <?php else : ?>
            <?php get_template_part('template-parts/post/content', 'none'); ?>
        <?php endif; ?>
    </div>

</main>

<?php get_footer();
