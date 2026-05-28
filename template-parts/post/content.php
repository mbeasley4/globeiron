<?php
/**
 * Post card template part — used in the blog index grid.
 *
 * @package Globeiron
 */

$categories = get_the_category();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>

    <?php if (has_post_thumbnail()) : ?>
    <a href="<?php the_permalink(); ?>" class="post-card__image" tabindex="-1" aria-hidden="true">
        <?php the_post_thumbnail('medium_large'); ?>
    </a>
    <?php endif; ?>

    <div class="post-card__body">

        <?php if ($categories) : ?>
        <div class="post-card__cats">
            <?php foreach (array_slice($categories, 0, 2) as $cat) : ?>
                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="badge badge--primary post-card__cat">
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h2 class="post-card__title">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>

        <p class="post-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '&hellip;'); ?></p>

        <footer class="post-card__footer">
            <div class="post-card__meta">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
            </div>
            <a href="<?php the_permalink(); ?>" class="post-card__read-more" aria-label="<?php echo esc_attr(sprintf(__('Read more: %s', 'globeiron'), get_the_title())); ?>">
                <?php esc_html_e('Read more', 'globeiron'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </footer>

    </div>
</article>
