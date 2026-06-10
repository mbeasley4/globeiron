<?php
/**
 * Certifications block — server-side render.
 *
 * ACF fields:
 *   heading  (text)         — optional headline above the badge grid
 *   intro    (textarea)     — optional intro paragraph
 *   columns  (button_group) — 3 | 4 | 5
 *   bg_style (button_group) — light (default) | dark | brand
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading  = (string) (get_field('heading')  ?: '');
$intro    = (string) (get_field('intro')    ?: '');
$bg_style = esc_attr(get_field('bg_style')  ?: 'light');

$certifications = get_posts([
    'post_type'      => 'certification',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
]);

if (empty($certifications)) {
    return;
}

$col_count  = max(2, min((int) (get_field('columns') ?: 4), count($certifications)));
$grid_style = sprintf(
    '--partners-cols-sm: %d; --partners-cols-md: %d; --partners-cols: %d;',
    max(2, min(3, $col_count)),
    max(2, min(4, $col_count)),
    $col_count
);

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-section-certifications is-style-{$bg_style} is-display-certifications",
]);
?>
<section <?php echo $wrapper_attributes; ?>>
  <div class="partners__inner">

    <?php if ($heading || $intro) : ?>
    <header class="partners__header">
      <?php if ($heading) : ?>
        <h2 class="partners__heading"><?php echo esc_html($heading); ?></h2>
      <?php endif; ?>
      <?php if ($intro) : ?>
        <p class="partners__intro"><?php echo esc_html($intro); ?></p>
      <?php endif; ?>
    </header>
    <?php endif; ?>

    <ul class="partners__grid" style="<?php echo esc_attr($grid_style); ?>" aria-label="<?php esc_attr_e('Certifications', 'globeiron'); ?>">
      <?php foreach ($certifications as $cert) :
        $logo_id = get_post_thumbnail_id($cert->ID);
        $url     = (string) get_post_meta($cert->ID, '_certification_url', true);
        $name    = get_the_title($cert);
      ?>
      <li class="partners__item partners__item--cert">
        <?php if ($url) : ?>
          <a href="<?php echo esc_url($url); ?>"
             target="_blank" rel="noopener noreferrer"
             aria-label="<?php echo esc_attr($name); ?>"
             class="partners__tile-link">
        <?php endif; ?>
        <div class="partners__tile">
          <?php if ($logo_id) : ?>
            <?php echo wp_get_attachment_image($logo_id, 'medium', false, [
                'class'    => 'partners__logo',
                'loading'  => 'lazy',
                'decoding' => 'async',
                'alt'      => esc_attr($name),
            ]); ?>
          <?php else : ?>
            <span class="partners__logo-placeholder"><?php echo esc_html($name); ?></span>
          <?php endif; ?>
          <?php if ($url) : ?>
            <span class="partners__tile-external" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></span>
          <?php endif; ?>
        </div>
        <?php if ($url) : ?>
          </a>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>

  </div>
</section>
