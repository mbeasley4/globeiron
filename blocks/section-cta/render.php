<?php
/**
 * Section: Call to Action
 *
 * ACF fields:
 *   heading          (Text)   — main headline
 *   body             (Wysiwyg / Text) — supporting sentence
 *   background_image (Image)  — full-bleed background photo
 *   cta_primary      (Link)   — primary button (only rendered when url is set)
 *   cta_secondary    (Link)   — secondary button (only rendered when url is set)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading          = (string) (get_field('heading')          ?: '');
$body             = (string) (get_field('body')             ?: '');
$background_image = get_field('background_image')           ?: [];
$cta_primary      = get_field('cta_primary');
$cta_secondary    = get_field('cta_secondary');

// On the front end, skip if there is no heading.
// In the editor ($is_preview = true) always render so the block is clickable.
if (empty($heading) && empty($is_preview)) {
    return;
}

$bg_style = '';
if (!empty($background_image['url'])) {
    $bg_style = 'background-image:url(' . esc_url($background_image['url']) . ');';
}

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-cta section-cta',
    'style' => $bg_style,
]);
?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-cta__inner">

    <?php if ($heading) : ?>
      <h2 class="section-cta__heading"><?php echo esc_html($heading); ?></h2>
    <?php elseif ($is_preview) : ?>
      <p class="section-cta__placeholder">Section — Call to Action<br><small>Fill in the Heading field to get started.</small></p>
    <?php endif; ?>

    <?php if ($body) : ?>
      <div class="section-cta__body"><?php echo wp_kses_post($body); ?></div>
    <?php endif; ?>

    <?php
    $has_primary   = !empty($cta_primary['url']);
    $has_secondary = !empty($cta_secondary['url']);
    if ($has_primary || $has_secondary) :
    ?>
      <div class="section-cta__actions">

        <?php if ($has_primary) : ?>
          <a href="<?php echo esc_url($cta_primary['url']); ?>"
             class="btn btn--brand btn--lg"
             <?php if (!empty($cta_primary['target'])) : ?>target="<?php echo esc_attr($cta_primary['target']); ?>"<?php endif; ?>>
            <?php echo esc_html($cta_primary['title']); ?>
          </a>
        <?php endif; ?>

        <?php if ($has_secondary) : ?>
          <a href="<?php echo esc_url($cta_secondary['url']); ?>"
             class="btn btn--outline-white btn--lg"
             <?php if (!empty($cta_secondary['target'])) : ?>target="<?php echo esc_attr($cta_secondary['target']); ?>"<?php endif; ?>>
            <?php echo esc_html($cta_secondary['title']); ?>
          </a>
        <?php endif; ?>

      </div>
    <?php endif; ?>

  </div>
</section>
