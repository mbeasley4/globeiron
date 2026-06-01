<?php
/**
 * Section - Page Hero block render template.
 *
 * Three modes controlled by the hero_type field:
 *   basic   — static headline, content text, and hero image
 *   post    — latest featured blog post + hero image
 *   project — latest featured project + the project's own featured image
 *
 * @package Globeiron
 */

declare(strict_types=1);

$hero_type  = get_field('hero_type') ?: 'basic';
$hero_image = get_field('hero_image'); // array|null — used for basic + post modes

$heading       = '';
$body          = '';
$permalink     = '';
$heading_link  = ''; // separate from $permalink — project mode keeps heading plain
$cta_label     = '';
$eyebrow_label = '';
$location      = '';
$cat_name      = '';
$image_id      = $hero_image['ID'] ?? 0;  // attachment ID for srcset generation
$image_url     = $hero_image ? ($hero_image['sizes']['large'] ?? $hero_image['url']) : '';
$image_alt     = $hero_image['alt'] ?? '';

// ── Resolve content by mode ───────────────────────────────────────────────────

if ($hero_type === 'basic') {

    $heading = get_field('headline') ?: '';
    $body    = get_field('content')  ?: '';

} elseif ($hero_type === 'post') {

    // Featured post: "featured-post" category → sticky → most recent
    $feat_cat = get_category_by_slug('featured-post');
    $post_obj = null;

    if ($feat_cat) {
        $results  = get_posts(['numberposts' => 1, 'post_status' => 'publish', 'category' => $feat_cat->term_id]);
        $post_obj = $results[0] ?? null;
    }
    if (!$post_obj) {
        $sticky = get_option('sticky_posts', []);
        if (!empty($sticky)) {
            $post_obj = get_post($sticky[0]);
        }
    }
    if (!$post_obj) {
        $results  = get_posts(['numberposts' => 1, 'post_status' => 'publish']);
        $post_obj = $results[0] ?? null;
    }

    if ($post_obj) {
        $heading      = get_the_title($post_obj);
        $body         = wp_trim_words(
            $post_obj->post_excerpt ?: wp_strip_all_tags($post_obj->post_content),
            30,
            '…'
        );
        $permalink    = get_permalink($post_obj);
        $heading_link = $permalink; // post heading links to the article
        $cta_label    = __('Read Now', 'globeiron');
    }

} elseif ($hero_type === 'project') {

    $results  = get_posts([
        'post_type'   => 'project',
        'numberposts' => 1,
        'post_status' => 'publish',
        'orderby'     => 'date',
        'order'       => 'DESC',
    ]);
    $post_obj = $results[0] ?? null;

    if ($post_obj) {
        $heading   = get_the_title($post_obj);
        $body      = wp_trim_words(
            $post_obj->post_excerpt ?: wp_strip_all_tags($post_obj->post_content),
            30,
            '…'
        );
        $permalink     = get_permalink($post_obj);
        $cta_label     = __('View Project Details', 'globeiron');
        $eyebrow_label = (string) (get_field('eyebrow',      $post_obj->ID) ?: '');
        $location      = (string) (get_field('tech_location', $post_obj->ID) ?: '');
        $raw_cats      = get_the_terms($post_obj->ID, 'project_category');
        $cat_name      = ($raw_cats && !is_wp_error($raw_cats)) ? $raw_cats[0]->name : '';

        // Project's own featured image, fallback to no-image.png
        $thumb_id  = get_post_thumbnail_id($post_obj->ID);
        $image_id  = $thumb_id ?: 0;
        $image_url = $thumb_id
            ? (wp_get_attachment_image_url($thumb_id, 'large') ?: globeiron_no_image_url())
            : globeiron_no_image_url();
        $image_alt = $thumb_id
            ? (string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true)
            : '';
    }
}

if (!$heading && $hero_type === 'basic') {
    return; // nothing to show
}

$type_class = $hero_type === 'project' ? ' blog-hero--project' : '';

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-section-page-hero blog-hero{$type_class}",
]);
?>
<div <?php echo $wrapper_attributes; ?>>
  <section class="blog-hero__hero">
    <div class="blog-hero__inner">

      <div class="blog-hero__content">

        <?php if ($heading) : ?>
          <h1 class="blog-hero__heading">
              <?php echo esc_html($heading); ?>
          </h1>
        <?php endif; ?>

        <?php if ($eyebrow_label) : ?>
          <p class="blog-hero__eyebrow"><?php echo esc_html($eyebrow_label); ?></p>
        <?php endif; ?>

        <?php if ($location || $cat_name) : ?>
          <dl class="blog-hero__meta">
            <?php if ($location) : ?>
              <div class="blog-hero__meta-row">
                <dt><?php esc_html_e('Location', 'globeiron'); ?>:</dt>
                <dd><?php echo esc_html($location); ?></dd>
              </div>
            <?php endif; ?>
            <?php if ($cat_name) : ?>
              <div class="blog-hero__meta-row">
                <dt><?php esc_html_e('Category', 'globeiron'); ?>:</dt>
                <dd><?php echo esc_html($cat_name); ?></dd>
              </div>
            <?php endif; ?>
          </dl>
        <?php endif; ?>

        <?php if ($body) : ?>
          <p class="blog-hero__body"><?php echo esc_html($body); ?></p>
        <?php endif; ?>

        <?php if ($cta_label && $permalink) : ?>
          <div class="blog-hero__actions">
            <a href="<?php echo esc_url($permalink); ?>" class="btn btn--primary">
              <?php echo esc_html($cta_label); ?>
            </a>
          </div>
        <?php endif; ?>

      </div>

      <?php if ($image_url) : ?>
        <div class="blog-hero__media" aria-hidden="true">
          <?php if ($image_id) : ?>
            <?php echo wp_get_attachment_image($image_id, 'large', false, [
                'class'   => 'blog-hero__image',
                'loading' => 'eager',
                'decoding'=> 'async',
                'sizes'   => '(max-width: 768px) calc(100vw - 2rem), (max-width: 1280px) 45vw, 730px',
            ]); ?>
          <?php else : ?>
            <img class="blog-hero__image"
                 src="<?php echo esc_url($image_url); ?>"
                 alt="<?php echo esc_attr($image_alt); ?>"
                 loading="eager"
                 decoding="async">
          <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
  </section>
</div>
