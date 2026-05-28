<?php
/**
 * Section: Features / Why Choose Us
 *
 * ACF fields:
 *   section_title        (text)
 *   heading              (text)
 *   body                 (wysiwyg)
 *   features             (repeater)
 *     └─ title       (text)
 *     └─ description (wysiwyg)
 *   show_globes          (true/false)
 *   show_scroll_indicator (true/false)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$section_title         = (string) (get_field('section_title') ?: '');
$heading               = (string) (get_field('heading')       ?: '');
$body                  = (string) (get_field('body')           ?: '');
$features              = get_field('features')                 ?: [];
$show_globes           = (bool)   get_field('show_globes');
$show_scroll_indicator = (bool)   get_field('show_scroll_indicator');
$style                 = (string) (get_field('style')          ?: 'none');
$style_crosshairs      = (string) (get_field('style_crosshairs') ?: 'show');
$show_crosshairs       = $style_crosshairs !== 'hide';

if (empty($heading)) {
    return;
}

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-features section-features'
        . ($show_globes ? ' has-globes' : '')
        . ($show_scroll_indicator ? ' has-scroll-indicator' : '')
        . ($style === 'xtra' ? ' style--xtra' : '')
        . ($style === 'none' ? ' desktop-no-padding-top' : ''),
]);

$crosshair_svg = '<svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
  <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';
?>

<section <?php echo $wrapper_attrs; ?>>

  <?php /* ── Overflow-clipped wrapper (contains globe ornaments) ─────────── */ ?>
  <div class="section-features__overflow-clip">

    <?php if ($show_globes) : ?>
      <img class="section-features__globe-left"
           src="<?php echo esc_url(get_template_directory_uri()); ?>/img/features/feat-top-left.png"
           alt="" aria-hidden="true" loading="lazy">
      <img class="section-features__globe-right"
           src="<?php echo esc_url(get_template_directory_uri()); ?>/img/features/feat-top-right.png"
           alt="" aria-hidden="true" loading="lazy">
    <?php endif; ?>

    <div class="section-features__inner">

      <div class="section-features__header">
        <?php if ($section_title) : ?>
          <p class="section-features__eyebrow"><?php echo esc_html($section_title); ?></p>
        <?php endif; ?>
        <h2 class="section-features__heading"><?php echo esc_html($heading); ?></h2>
        <?php if ($body) : ?>
          <p class="section-features__body"><?php echo wp_kses_post($body); ?></p>
        <?php endif; ?>
      </div>

      <?php if ($features) : ?>
        <div class="section-features__grid">
          <?php foreach ($features as $feature) :
            $title       = $feature['title']       ?? '';
            $description = $feature['description'] ?? '';
          ?>
            <div class="section-features__item">
              <h3 class="section-features__item-title"><?php echo esc_html($title); ?></h3>
              <?php if ($description) : ?>
                <p class="section-features__item-desc"><?php echo wp_kses_post($description); ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($show_crosshairs) : ?>
      <?php /* ── Side crosshair ornaments ──────────────────────────────────── */ ?>
      <div class="section-features__ornament section-features__ornament--left" aria-hidden="true">
        <svg class="section-features__ornament-crosshair" viewBox="0 0 28 28"
             fill="none" stroke="currentColor" stroke-width="1.5"
             xmlns="http://www.w3.org/2000/svg">
          <circle cx="14" cy="14" r="12" pathLength="1"/>
          <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
          <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
        </svg>
      </div>
      <div class="section-features__ornament section-features__ornament--right" aria-hidden="true">
        <svg class="section-features__ornament-crosshair" viewBox="0 0 28 28"
             fill="none" stroke="currentColor" stroke-width="1.5"
             xmlns="http://www.w3.org/2000/svg">
          <circle cx="14" cy="14" r="12" pathLength="1"/>
          <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
          <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
        </svg>
      </div>
      <?php endif; ?>

    </div>

  </div>

  <?php /* ── Scroll indicator (overflows into next section) ──────────────── */ ?>
  <?php if ($show_scroll_indicator) : ?>
    <div class="section-features__scroll-indicator" aria-hidden="true">
      <svg class="section-features__scroll-crosshair section-features__scroll-crosshair--top"
           viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
           xmlns="http://www.w3.org/2000/svg">
        <circle cx="14" cy="14" r="12" pathLength="1"/>
        <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
        <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
      </svg>
      <div class="section-features__scroll-line"></div>
      <svg class="section-features__scroll-crosshair section-features__scroll-crosshair--end"
           viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
           xmlns="http://www.w3.org/2000/svg">
        <circle cx="14" cy="14" r="12" pathLength="1"/>
        <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
        <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
      </svg>
    </div>
  <?php endif; ?>

</section>
