<?php
/**
 * Single project template.
 *
 * @package Globeiron
 */

get_header();
?>

<main id="main" class="site-content single-project">
    <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
</main>

<?php get_footer();
