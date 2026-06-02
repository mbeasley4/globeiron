<?php
/**
 * Certifications block — server-side render.
 *
 * ACF fields:
 *   heading  (text)     — optional headline above the badge grid
 *   intro    (textarea) — optional intro paragraph
 *   bg_style (select)   — dark | light | brand
 *
 * Pulls all published Certification CPT entries ordered alphabetically.
 * Uses the same partners__* CSS classes as the Partnership block.
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading  = (string) (get_field('heading')  ?: '');
$intro    = (string) (get_field('intro')    ?: '');
$bg_style = esc_attr(get_field('bg_style')  ?: 'dark');

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

$col_setting = max(3, min(5, (int) (get_field('columns') ?: 4)));
$grid_cols   = max(2, min($col_setting, count($certifications)));
$grid_cols_sm = max(2, min(3, count($certifications)));
$grid_cols_md = max(2, min(4, count($certifications)));
$grid_style = sprintf(
    '--partners-cols-sm: %d; --partners-cols-md: %d; --partners-cols: %d;',
    $grid_cols_sm,
    $grid_cols_md,
    $grid_cols
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
        $name    = get_the_title($cert);
        $url     = get_post_meta($cert->ID, '_certification_url', true);
        $logo_id = get_post_thumbnail_id($cert->ID);
        $logo    = $logo_id ? wp_get_attachment_image_src($logo_id, 'medium') : false;
      ?>
      <li class="partners__item">
        <?php if ($url) : ?>
          <a href="<?php echo esc_url($url); ?>"
             target="_blank" rel="noopener noreferrer"
             aria-label="<?php echo esc_attr($name); ?>"
             class="partners__tile-link">
        <?php endif; ?>
        <div class="partners__tile">
          <?php if ($logo) : ?>
            <img src="<?php echo esc_url($logo[0]); ?>"
                 alt="<?php echo esc_attr($name); ?>"
                 class="partners__logo"
                 width="<?php echo esc_attr((string) $logo[1]); ?>"
                 height="<?php echo esc_attr((string) $logo[2]); ?>"
                 loading="lazy" decoding="async">
          <?php else : ?>
            <span class="partners__logo-placeholder"><?php echo esc_html($name); ?></span>
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
