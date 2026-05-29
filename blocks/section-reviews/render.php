<?php
/**
 * Section: Customer Reviews Slider
 *
 * ACF fields on this block:
 *   heading        (Text)   — section heading, default "Customer Reviews"
 *   overall_rating (Number) — 1–5 aggregate star rating shown in header, default 5
 *   rating_label   (Text)   — label beneath stars, default "Excellent"
 *   reviews_count  (Number) — how many reviews to load, default 12
 *
 * Pulls from: globeiron_review CPT
 *   post_title       = reviewer name
 *   post_content     = full review body
 *   _review_headline = card title (e.g. "Excellent Service")
 *   _review_rating   = 1–5
 *   _review_source   = google|facebook|yelp|direct
 *   _review_date     = Y-m-d
 *   featured image   = reviewer photo (optional)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading        = (string) (get_field('heading')        ?: 'Customer Reviews');
$overall_rating = max(1, min(5, (int) (get_field('overall_rating') ?: 5)));
$rating_label   = (string) (get_field('rating_label')  ?: 'Excellent');
$reviews_count  = max(1, (int) (get_field('reviews_count') ?: 12));

$reviews = get_posts([
    'post_type'      => 'globeiron_review',
    'post_status'    => 'publish',
    'posts_per_page' => $reviews_count,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
]);

if (empty($reviews)) {
    if (is_admin()) {
        echo '<p style="padding:2rem;text-align:center;color:#657693;">No published reviews found. Add reviews to see the slider.</p>';
    }
    return;
}

$block_id = 'sr-' . ($block['id'] ?? uniqid());

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-reviews section-reviews',
]);

$source_icons = [
    'google' => '<svg class="review-card__source-svg" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.29-8.16 2.29-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>',
    'facebook' => '<svg class="review-card__source-svg" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path fill="#1877F2" d="M48 24C48 10.745 37.255 0 24 0S0 10.745 0 24c0 11.979 8.776 21.908 20.25 23.708V30.938h-6.094V24h6.094v-5.288c0-6.014 3.583-9.337 9.065-9.337 2.625 0 5.372.469 5.372.469v5.906h-3.026c-2.981 0-3.911 1.85-3.911 3.75V24h6.656l-1.064 6.938H27.75v16.77C39.224 45.908 48 35.979 48 24z"/></svg>',
    'yelp'     => '<svg class="review-card__source-svg" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path fill="#D32323" d="M38.08 27.96c-.44-1.01-1.35-1.7-2.42-1.86l-9.92-1.4a.71.71 0 0 0-.5 1.23l7.36 6.87c.75.7 1.82.95 2.82.64a3.54 3.54 0 0 0 2.37-2.6c.19-.95.02-1.9-.71-2.88zM21.38 23.1l9.23-3.9c1.01-.43 1.7-1.35 1.84-2.43a3.54 3.54 0 0 0-1.12-3.07L23.6 7.1a3.54 3.54 0 0 0-4.83.27c-.63.67-.93 1.55-.85 2.46l.87 12.26c.06.85.87 1.38 1.59 1.01zM18.5 26.7l-9.92 1.4c-1.07.15-1.98.85-2.42 1.86-.73.98-.9 1.93-.71 2.88a3.54 3.54 0 0 0 2.37 2.6c1 .31 2.07.06 2.82-.64l7.36-6.87a.71.71 0 0 0-.5-1.23zM20.65 28.7l-5.57 8.36c-.6.9-.67 2.03-.18 2.99.5.97 1.44 1.62 2.53 1.73 1.08.12 2.12-.32 2.79-1.16l5.79-7.3a.71.71 0 0 0-.7-1.13l-4.66-3.49zM27.35 28.7l-4.66 3.49a.71.71 0 0 0-.7 1.13l5.79 7.3c.67.84 1.71 1.28 2.79 1.16 1.09-.11 2.03-.76 2.53-1.73.49-.96.42-2.09-.18-2.99l-5.57-8.36z"/></svg>',
];

if (!function_exists('globeiron_review_time_ago')) {
    function globeiron_review_time_ago(string $date): string {
        if (!$date) return '';
        $ts = strtotime($date);
        if (!$ts) return '';
        $diff   = time() - $ts;
        $years  = (int) floor($diff / (365.25 * 86400));
        if ($years  > 0) return $years  === 1 ? __('1 year ago',    'globeiron') : sprintf(__('%d years ago',  'globeiron'), $years);
        $months = (int) floor($diff / (30.44  * 86400));
        if ($months > 0) return $months === 1 ? __('1 month ago',   'globeiron') : sprintf(__('%d months ago', 'globeiron'), $months);
        $weeks  = (int) floor($diff / (7     * 86400));
        if ($weeks  > 0) return $weeks  === 1 ? __('1 week ago',    'globeiron') : sprintf(__('%d weeks ago',  'globeiron'), $weeks);
        return __('Recently', 'globeiron');
    }
}
?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-reviews__inner">

    <div class="section-reviews__header">
      <h2 class="section-reviews__heading"><?php echo esc_html($heading); ?></h2>
      <div class="section-reviews__rating">
        <div class="section-reviews__stars" role="img" aria-label="<?php printf(esc_attr__('%d out of 5 stars', 'globeiron'), $overall_rating); ?>">
          <?php for ($i = 1; $i <= 5; $i++) : ?>
            <svg class="section-reviews__star<?php echo $i <= $overall_rating ? ' is-filled' : ''; ?>"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          <?php endfor; ?>
        </div>
        <p class="section-reviews__rating-label"><?php echo esc_html(strtoupper($rating_label)); ?></p>
      </div>
    </div>

    <div class="splide section-reviews__splide"
         id="<?php echo esc_attr($block_id); ?>"
         aria-label="<?php esc_attr_e('Customer Reviews', 'globeiron'); ?>">
      <div class="splide__track">
        <ul class="splide__list">
          <?php foreach ($reviews as $review) :
            $name     = get_the_title($review);
            $body     = wp_trim_words(get_the_content(null, false, $review), 28, '…');
            $headline = (string) (get_field('review_headline', $review->ID) ?: get_post_meta($review->ID, '_review_headline', true));
            $source   = strtolower(trim((string) (get_field('review_source', $review->ID) ?: get_post_meta($review->ID, '_review_source', true) ?: 'google')));
            $date     = (string) (get_field('review_date', $review->ID) ?: get_post_meta($review->ID, '_review_date', true));
            $time_ago = globeiron_review_time_ago($date);
            $photo_id = get_post_thumbnail_id($review->ID);
            $photo_src = $photo_id ? wp_get_attachment_image_src($photo_id, 'thumbnail') : null;
            $initial  = strtoupper(mb_substr($name, 0, 1));
            $icon_key = array_key_exists($source, $source_icons) ? $source : 'google';
          ?>
          <li class="splide__slide">
            <div class="review-card">

              <?php if ($headline) : ?>
                <h3 class="review-card__title"><?php echo esc_html($headline); ?></h3>
              <?php endif; ?>

              <p class="review-card__body"><?php echo esc_html($body); ?></p>

              <hr class="review-card__divider" aria-hidden="true">

              <div class="review-card__footer">
                <div class="review-card__author">
                  <?php if ($photo_src) : ?>
                    <img class="review-card__avatar review-card__avatar--photo"
                         src="<?php echo esc_url($photo_src[0]); ?>"
                         alt="<?php echo esc_attr($name); ?>"
                         width="44" height="44" loading="lazy">
                  <?php else : ?>
                    <div class="review-card__avatar" aria-hidden="true"><?php echo esc_html($initial); ?></div>
                  <?php endif; ?>
                  <div class="review-card__author-info">
                    <p class="review-card__name"><?php echo esc_html($name); ?></p>
                    <?php if ($time_ago) : ?>
                      <p class="review-card__date"><?php echo esc_html($time_ago); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
                <?php echo $source_icons[$icon_key]; ?>
              </div>

            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="splide__arrows section-reviews__arrows">
        <button class="splide__arrow splide__arrow--prev section-reviews__arrow"
                type="button"
                aria-label="<?php esc_attr_e('Previous reviews', 'globeiron'); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button class="splide__arrow splide__arrow--next section-reviews__arrow"
                type="button"
                aria-label="<?php esc_attr_e('Next reviews', 'globeiron'); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>

    </div>

  </div>
</section>
