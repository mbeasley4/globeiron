<?php
/**
 * Section: Testimonials
 *
 * Figma section: "Our customers have a lot to say"
 * Layout: eyebrow → heading → 3-col testimonial cards.
 * Each card: house/project photo, circular client avatar overlapping the bottom
 * of the photo, client name, optional location, star rating, review text.
 *
 * ACF fields (two modes — pick one per block instance):
 *
 * Mode A — Manual repeater:
 *   testimonials_source = 'manual'
 *   testimonials (repeater)
 *     └─ project_image (image)
 *     └─ client_photo  (image)
 *     └─ client_name   (text)
 *     └─ client_location (text)
 *     └─ rating        (number 1-5)
 *     └─ review        (textarea)
 *
 * Mode B — From CPT (pulls published globeiron_testimonial posts):
 *   testimonials_source = 'cpt'
 *   testimonials_count  (number, default 3)
 *
 * Common fields:
 *   eyebrow   (text)
 *   heading   (text)
 *   cta_label (text)
 *   cta_url   (url)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$section_title       = (string) (get_field('section_title')      ?: '');
$heading            = (string) (get_field('heading')            ?: '');
$cta_label          = (string) (get_field('cta_label')          ?: '');
$cta_url            = (string) (get_field('cta_url')            ?: '');
$testimonials_source = (string) (get_field('testimonials_source') ?: 'manual');
$testimonials_count  = max(1, (int) (get_field('testimonials_count') ?: 3));

if (empty($heading)) {
    return;
}

// ── Collect items ─────────────────────────────────────────────────────────────
$items = [];

if ($testimonials_source === 'cpt') {
    $posts = get_posts([
        'post_type'      => 'globeiron_review',
        'post_status'    => 'publish',
        'posts_per_page' => $testimonials_count,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);
    foreach ($posts as $r) {
        $items[] = [
            'client_name'     => get_the_title($r),
            'client_location' => (string) get_post_meta($r->ID, '_review_location', true),
            'rating'          => (int) (get_post_meta($r->ID, '_review_rating', true) ?: 5),
            'review'          => wp_kses_post(wpautop(get_post_field('post_content', $r->ID))),
            'source'          => (string) get_post_meta($r->ID, '_review_source', true),
            'review_date'     => (string) get_post_meta($r->ID, '_review_date', true),
        ];
    }
} else {
    $items = get_field('testimonials') ?: [];
}

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-testimonials section-testimonials',
]);
?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-testimonials__inner">

    <div class="section-testimonials__header">
      <?php if ($section_title) : ?>
        <p class="section-testimonials__eyebrow"><?php echo esc_html($section_title); ?></p>
      <?php endif; ?>
      <h2 class="section-testimonials__heading"><?php echo esc_html($heading); ?></h2>
    </div>

    <?php if ($items) : ?>
      <div class="section-testimonials__grid">
        <?php foreach ($items as $item) :
          $client_name = (string) ($item['client_name']     ?? '');
          $client_loc  = (string) ($item['client_location'] ?? '');
          $rating      = max(1, min(5, (int) ($item['rating'] ?? 5)));
          $review      = (string) ($item['review'] ?? '');
          $source      = (string) ($item['source']      ?? '');
          $review_date = (string) ($item['review_date'] ?? '');
        ?>
          <div class="section-testimonials__card">
            <div class="section-testimonials__card-body">
              <?php if ($client_name) : ?>
                <h4 class="section-testimonials__client-name"><?php echo esc_html($client_name); ?></h4>
              <?php endif; ?>
              <?php if ($client_loc) : ?>
                <p class="section-testimonials__client-loc"><?php echo esc_html($client_loc); ?></p>
              <?php endif; ?>
              <div class="section-testimonials__stars-row">
                <div class="section-testimonials__stars" aria-label="<?php printf(esc_attr__('%d out of 5 stars', 'globeiron'), $rating); ?>">
                  <?php for ($s = 1; $s <= 5; $s++) : ?>
                    <span class="section-testimonials__star <?php echo $s <= $rating ? 'is-filled' : ''; ?>" aria-hidden="true">★</span>
                  <?php endfor; ?>
                </div>
                <?php if ($source) : ?>
                  <span class="section-testimonials__source"><?php echo esc_html($source); ?></span>
                <?php endif; ?>
              </div>
              <?php if ($review) : ?>
                <div class="section-testimonials__review"><?php echo wp_kses_post($review); ?></div>
              <?php endif; ?>
              <?php if ($review_date) : ?>
                <p class="section-testimonials__date">
                  <time datetime="<?php echo esc_attr($review_date); ?>">
                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($review_date))); ?>
                  </time>
                </p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($cta_label && $cta_url) : ?>
      <div class="section-testimonials__cta">
        <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--brand">
          <?php echo esc_html($cta_label); ?>
        </a>
      </div>
    <?php endif; ?>

  </div>
</section>
