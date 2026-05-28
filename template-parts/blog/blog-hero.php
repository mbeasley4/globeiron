<?php
/**
 * Template part: Blog page featured post hero.
 *
 * Rendered above the #blog-grid-root post grid in index.php.
 * Featured post priority: "Featured Post" category → sticky post → most recent.
 *
 * @package Globeiron
 */

declare(strict_types=1);

// ── Featured post ─────────────────────────────────────────────────────────────
$featured_cat_obj = get_category_by_slug('featured-post');
$featured_post    = null;

if ($featured_cat_obj) {
    $in_featured   = get_posts([
        'numberposts' => 1,
        'post_status' => 'publish',
        'category'    => $featured_cat_obj->term_id,
    ]);
    $featured_post = $in_featured[0] ?? null;
}

if (!$featured_post) {
    $sticky_ids    = get_option('sticky_posts', []);
    $featured_post = !empty($sticky_ids) ? get_post($sticky_ids[0]) : null;
}

if (!$featured_post) {
    $recent        = get_posts(['numberposts' => 1, 'post_status' => 'publish']);
    $featured_post = $recent[0] ?? null;
}

if (!$featured_post) {
    return;
}

$featured_id      = $featured_post->ID;
$featured_title   = get_the_title($featured_post);
$featured_excerpt = wp_trim_words(
    $featured_post->post_excerpt ?: wp_strip_all_tags($featured_post->post_content),
    30,
    '…'
);
$featured_url   = get_permalink($featured_post);
$featured_cats  = get_the_category($featured_id);
$featured_cat   = $featured_cats[0] ?? null;

$queried_cat_id = is_category() ? get_queried_object_id() : 0;
$blog_page_id   = (int) get_option('page_for_posts');
$blog_index_url = $blog_page_id ? get_permalink($blog_page_id) : home_url('/blog');

// ── Hero image — from the Posts Page (Settings › Reading), not the featured post ─
$featured_thumb = $blog_page_id ? (get_the_post_thumbnail_url($blog_page_id, 'large') ?: '') : '';

// ── Top categories for filter tabs ────────────────────────────────────────────
$_featured_cat_obj  = get_category_by_slug('featured-post');
$_exclude_cat_ids   = $_featured_cat_obj ? [$_featured_cat_obj->term_id] : [];
$hero_categories    = get_categories([
    'hide_empty' => true,
    'number'     => 5,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'exclude'    => $_exclude_cat_ids,
]);

?>
<div class="blog-hero">
  <section class="blog-hero__hero">
    <div class="blog-hero__inner">

      <div class="blog-hero__content">

        <?php if ($featured_cat) : ?>
          <a class="blog-hero__category-badge"
             href="<?php echo esc_url(get_category_link($featured_cat->term_id)); ?>">
            <?php echo esc_html($featured_cat->name); ?>
          </a>
        <?php endif; ?>

        <h1 class="blog-hero__heading">
          <a href="<?php echo esc_url($featured_url); ?>"><?php echo esc_html($featured_title); ?></a>
        </h1>

        <?php if ($featured_excerpt) : ?>
          <p class="blog-hero__body"><?php echo esc_html($featured_excerpt); ?></p>
        <?php endif; ?>

        <div class="blog-hero__actions">
          <a href="<?php echo esc_url($featured_url); ?>" class="btn btn--primary">
            <?php esc_html_e('Read Now', 'globeiron'); ?>
          </a>
        </div>

        <?php if ($hero_categories) : ?>
          <div class="blog-hero__filters">
            <?php foreach ($hero_categories as $cat) : ?>
              <a class="blog-hero__filter<?php echo $queried_cat_id === $cat->term_id ? ' is-active' : ''; ?>"
                 href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
                <?php echo esc_html($cat->name); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>

      <?php if ($featured_thumb) : ?>
        <div class="blog-hero__media" aria-hidden="true">
          <img
            class="blog-hero__image"
            src="<?php echo esc_url($featured_thumb); ?>"
            alt=""
            loading="eager"
            decoding="async">
        </div>
      <?php endif; ?>

    </div>
  </section>
</div>
