<?php
/**
 * Homepage template (front-page.php).
 *
 * Uses the block editor — place blocks in the WP page editor in this order:
 *   1. globeiron/hero-home        (style: with_form)
 *   2. globeiron/section-features
 *   3. globeiron/section-services
 *   4. globeiron/section-roofing-types
 *   5. globeiron/section-process
 *   6. globeiron/section-testimonials
 *   7. globeiron/section-contact-map
 *
 * The <main> has no inner container here because every homepage block manages
 * its own full-width breakout and internal container padding.
 *
 * @package Globeiron
 */

get_header(); ?>

<main id="main" class="site-content site-content--home">
    <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
</main>

<?php get_footer();
