<?php
/**
 * Main template file — blog archive.
 *
 * @package Globeiron
 */

get_header(); ?>

<main id="main" class="site-content">

    <?php get_template_part('template-parts/blog/blog-hero'); ?>

    <div class="container section">
        <?php if (have_posts()) : ?>
            <?php
            $blog_page_id   = (int) get_option('page_for_posts');
            $listing_title   = $blog_page_id ? get_field('grid_title', $blog_page_id) : '';
            $listing_content = $blog_page_id ? get_field('grid_content', $blog_page_id) : '';
            ?>
            <?php if ($listing_title || $listing_content) : ?>
            <div class="blog-listing__header">
                <?php if ($listing_title) : ?>
                    <h2 class="blog-listing__heading"><?php echo esc_html($listing_title); ?></h2>
                <?php endif; ?>
                <?php if ($listing_content) : ?>
                    <div class="blog-listing__description"><?php echo wp_kses_post($listing_content); ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div id="blog-grid-root"></div>
        <?php else : ?>
            <?php get_template_part('template-parts/post/content', 'none'); ?>
        <?php endif; ?>
    </div>

</main>

<?php get_footer();
