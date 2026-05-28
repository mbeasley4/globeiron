<?php
/**
 * No results template part.
 *
 * @package Globeiron
 */
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nothing Found', 'globeiron'); ?></h1>
    </header>
    <div class="page-content">
        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'globeiron'); ?></p>
        <?php get_search_form(); ?>
    </div>
</section>
