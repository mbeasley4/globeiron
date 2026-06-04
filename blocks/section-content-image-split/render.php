<?php
/**
 * Section: Content / Image Split
 *
 * ACF fields:
 *   heading          (text)    — required headline
 *   body             (wysiwyg) — body copy
 *   cta_link         (link)    — optional CTA button
 *   image            (image)   — right-hand photo
 *   background_color (select)  — white (default) | grey | blue
 *   image_position   (select)  — right (default) | left
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading        = (string) (get_field('heading')         ?: '');
$body           = (string) (get_field('body')            ?: '');
$cta            = get_field('cta_link') ?: [];
$image          = get_field('image')    ?: [];
$bg_color       = (string) (get_field('background_color') ?: 'white');
$image_position = (string) (get_field('image_position')   ?: 'right');

if (empty($heading)) {
    return;
}

$bg_class = match ($bg_color) {
    'grey'  => 'has-bg-grey',
    'blue'  => 'has-bg-blue',
    default => 'has-bg-white',
};

$cta_label  = $cta['title']  ?? '';
$cta_url    = $cta['url']    ?? '';
$cta_target = $cta['target'] ?? '';

$has_image = ! empty($image);

$crosshair = '<svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
  <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-section-content-image-split section-content-image-split {$bg_class}"
        . " image-{$image_position}"
        . ($has_image ? '' : ' no-image'),
]);
?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-content-image-split__inner">

    <div class="section-content-image-split__content">

      <h2 class="section-content-image-split__heading"><?php echo esc_html($heading); ?></h2>

      <?php if ($body) : ?>
        <div class="section-content-image-split__body">
          <?php echo wp_kses_post($body); ?>
        </div>
      <?php endif; ?>

      <?php if ($cta_label && $cta_url) : ?>
        <div class="section-content-image-split__cta">
          <a href="<?php echo esc_url($cta_url); ?>"
             class="btn btn--primary"
             <?php if ($cta_target) : ?>target="<?php echo esc_attr($cta_target); ?>" rel="noopener noreferrer"<?php endif; ?>>
            <?php echo esc_html($cta_label); ?>
          </a>
        </div>
      <?php endif; ?>

    </div>

    <?php if ($has_image) : ?>

      <div class="section-content-image-split__divider" aria-hidden="true">
        <div class="section-content-image-split__divider-crosshair section-content-image-split__divider-crosshair--top">
          <?php echo $crosshair; ?>
        </div>
        <div class="section-content-image-split__divider-line"></div>
        <div class="section-content-image-split__divider-crosshair section-content-image-split__divider-crosshair--bottom">
          <?php echo $crosshair; ?>
        </div>
      </div>

      <div class="section-content-image-split__media">
        <img
          src="<?php echo esc_url($image['sizes']['large'] ?? $image['url']); ?>"
          alt="<?php echo esc_attr(($image['alt'] ?? '') ?: $heading); ?>"
          loading="lazy"
          decoding="async">
      </div>

    <?php endif; ?>

  </div>
</section>
