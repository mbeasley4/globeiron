<?php
/**
 * Section: Services
 *
 * ACF fields:
 *   heading  (text)
 *   subtitle (text)
 *   services (repeater)
 *     └─ image       (image)
 *     └─ title       (text)
 *     └─ description (wysiwyg)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading  = (string) (get_field('heading')  ?: '');
$subtitle = (string) (get_field('subtitle') ?: '');
$services = get_field('services')            ?: [];

if (empty($heading) || empty($services)) {
    return;
}

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-services section-services',
]);

$crosshair_svg = '<svg class="section-services__crosshair" viewBox="0 0 28 28"
     fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
  <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';
?>

<section <?php echo $wrapper_attrs; ?>>

  <?php /* ── Blue header ─────────────────────────────────────────────────── */ ?>
  <div class="section-services__header">
    <div class="section-services__ornament section-services__ornament--left" aria-hidden="true">
      <?php echo $crosshair_svg; ?>
    </div>
    <div class="section-services__ornament section-services__ornament--right" aria-hidden="true">
      <?php echo $crosshair_svg; ?>
    </div>
    <div class="section-services__headline">
      <h2 class="section-services__heading"><?php echo esc_html($heading); ?></h2>
      <?php if ($subtitle) : ?>
        <p class="section-services__subtitle"><?php echo esc_html($subtitle); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <?php /* ── 3-column card grid ─────────────────────────────────────────── */ ?>
  <div class="section-services__grid">
    <?php foreach ($services as $type) :
      $img      = $type['image']       ?? null;
      $img_url  = $img['url']          ?? '';
      $img_alt  = ($img['alt'] ?? '') ?: $title;
      $title    = $type['title']       ?? '';
      $desc     = $type['description'] ?? '';
      $cta      = $type['cta_link']    ?? null;
      $cta_url  = $cta['url']          ?? '';
      $cta_text = $cta['title']        ?? '';
      $cta_target = ! empty($cta['target']) ? $cta['target'] : '_self';
    ?>
      <div class="section-services__card<?php echo $cta_url ? ' has-cta' : ''; ?>">

        <?php if ($img_url) : ?>
          <img class="section-services__img"
               src="<?php echo esc_url($img_url); ?>"
               alt="<?php echo esc_attr($img_alt); ?>"
               loading="lazy">
        <?php endif; ?>

        <div class="section-services__card-overlay" aria-hidden="true"></div>

        <div class="section-services__card-top">
          <h3 class="section-services__type-title"><?php echo esc_html($title); ?></h3>
        </div>

        <?php if ($desc || $cta_url) : ?>
          <div class="section-services__card-bottom">
            <?php if ($desc) : ?>
              <div class="section-services__type-desc"><?php echo wp_kses_post($desc); ?></div>
            <?php endif; ?>
            <?php if ($cta_url && $cta_text) : ?>
              <a href="<?php echo esc_url($cta_url); ?>"
                 class="section-services__cta"
                 target="<?php echo esc_attr($cta_target); ?>"><?php echo esc_html($cta_text); ?></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

      </div>
    <?php endforeach; ?>
  </div>

</section>
