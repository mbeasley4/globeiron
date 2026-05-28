<?php
/**
 * Block: Project Details
 *
 * ACF fields:
 *   heading           (text, required)
 *   body              (wysiwyg)
 *   highlight_heading (text)
 *   highlight_body    (wysiwyg)
 *   image_pairs       (repeater)
 *     before_image    (image, return array)
 *     before_label    (text, default "Before")
 *     after_image     (image, return array)
 *     after_label     (text, default "After")
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading    = (string) (get_field('heading') ?: '');
$body       = get_field('body');
$highlights = get_field('highlights');
$highlights = is_array($highlights) ? $highlights : [];
$image_pairs = get_field('image_pairs') ?: [];

if (empty($heading) && empty($body) && empty($image_pairs) && empty($highlights)) {
    return;
}

$pair_count  = count($image_pairs);
$has_two_img = false; // true if any pair has both images — controls ornament visibility
foreach ($image_pairs as $p) {
    if (!empty($p['before_image']['url']) && !empty($p['after_image']['url'])) {
        $has_two_img = true;
        break;
    }
}

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-project-details project-details',
]);

$crosshair = '<svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7"  x2="14" y2="21" pathLength="1"/>
  <line x1="7"  y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';
?>

<div <?php echo $wrapper_attrs; ?>>
  <div class="project-details__inner container">

    <!-- ── Left: image stack ──────────────────────────────────────────────── -->
    <div class="project-details__media">

      <?php if ($image_pairs) : ?>
      <div class="project-details__images" data-ba-container>

        <?php foreach ($image_pairs as $i => $pair) :
          $before_url   = $pair['before_image']['url'] ?? '';
          $before_alt   = $pair['before_image']['alt'] ?? '';
          $before_label = trim((string) ($pair['before_label'] ?? '')) ?: 'Before';
          $after_url    = $pair['after_image']['url']  ?? '';
          $after_alt    = $pair['after_image']['alt']  ?? '';
          $after_label  = trim((string) ($pair['after_label']  ?? '')) ?: 'After';
          $is_single    = ($before_url xor $after_url); // exactly one image present
          $pair_classes = 'project-details__pair'
              . ($i === 0 ? ' is-active' : '')
              . ($is_single ? ' project-details__pair--single' : '');
        ?>
          <div class="<?php echo $pair_classes; ?>"
               data-ba-pair="<?php echo $i; ?>"
               aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">

            <?php if ($before_url) : ?>
            <div class="project-details__img-wrap project-details__img-wrap--before">
              <div class="project-details__img-inner">
                <img src="<?php echo esc_url($before_url); ?>"
                     alt="<?php echo esc_attr($before_alt); ?>"
                     loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"
                     decoding="async">
              </div>
              <?php if (! $is_single) : ?>
              <span class="project-details__img-label"><?php echo esc_html($before_label); ?></span>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($after_url) : ?>
            <div class="project-details__img-wrap project-details__img-wrap--after">
              <div class="project-details__img-inner">
                <img src="<?php echo esc_url($after_url); ?>"
                     alt="<?php echo esc_attr($after_alt); ?>"
                     loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"
                     decoding="async">
              </div>
              <?php if (! $is_single) : ?>
              <span class="project-details__img-label"><?php echo esc_html($after_label); ?></span>
              <?php endif; ?>
            </div>
            <?php endif; ?>

          </div><!-- /.project-details__pair -->
        <?php endforeach; ?>

        <?php if ($has_two_img) : ?>
        <!-- Decorative L-shaped ornament — only when pairs have two images -->
        <div class="project-details__ornament" aria-hidden="true">
          <div class="project-details__ornament-ch"><?php echo $crosshair; ?></div>
          <div class="project-details__ornament-hline"></div>
          <div class="project-details__ornament-vline"></div>
          <div class="project-details__ornament-ch"><?php echo $crosshair; ?></div>
        </div>
        <?php endif; ?>

        <?php if ($pair_count > 1) : ?>
        <div class="project-details__controls"
             role="group"
             aria-label="<?php esc_attr_e('Image pair navigation', 'globeiron'); ?>">
          <button class="project-details__arrow" data-ba-prev
                  aria-label="<?php esc_attr_e('Previous image pair', 'globeiron'); ?>">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
              <path d="M10.5 6L7.5 9l3 3" stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <button class="project-details__arrow" data-ba-next
                  aria-label="<?php esc_attr_e('Next image pair', 'globeiron'); ?>">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
              <path d="M7.5 6l3 3-3 3" stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        <?php endif; ?>

      </div><!-- /.project-details__images -->
      <?php endif; ?>

    </div><!-- /.project-details__media -->

    <!-- ── Right: content ────────────────────────────────────────────────── -->
    <div class="project-details__content">

      <?php if ($heading) : ?>
        <h2 class="project-details__heading"><?php echo esc_html($heading); ?></h2>
      <?php endif; ?>

      <?php if ($body) : ?>
        <div class="project-details__body"><?php echo wp_kses_post($body); ?></div>
      <?php endif; ?>

      <?php if ($highlights) : ?>
        <div class="project-details__highlights">
          <?php foreach ($highlights as $item) :
            $h = trim((string) ($item['highlight_heading'] ?? ''));
            $b = $item['highlight_body'] ?? '';
            if (!$h && !$b) continue;
          ?>
            <div class="project-details__highlight">
              <?php if ($h) : ?>
                <h3 class="project-details__highlight-heading"><?php echo esc_html($h); ?></h3>
              <?php endif; ?>
              <?php if ($b) : ?>
                <div class="project-details__highlight-body"><?php echo wp_kses_post($b); ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div><!-- /.project-details__content -->

  </div><!-- /.project-details__inner -->
</div>
