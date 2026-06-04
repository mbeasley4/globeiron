<?php
/**
 * Block: Project Outcome
 *
 * ACF fields:
 *   heading          (text)
 *   body             (wysiwyg)
 *   hide_text        (true_false) — when on, only the background image shows
 *   background_image (image, return array)
 *   background       (select: grey | white | blue)
 *
 * @package Globeiron
 */

declare(strict_types=1);

/** @var bool $is_preview ACF block preview flag, injected by ACF at render time. */
$is_preview = $is_preview ?? false;

$heading    = (string) (get_field('heading')          ?: '');
$body       = get_field('body');
$hide_text  = (bool) get_field('hide_text');
$bg_image   = get_field('background_image')            ?: [];
$background = (string) (get_field('background')        ?: 'grey');

if (empty($heading) && empty($bg_image) && empty($is_preview)) {
    return;
}

$bg_map = [
    'white' => 'has-bg-white',
    'blue'  => 'has-bg-blue',
    'grey'  => 'has-bg-grey',
];
$bg_class = $bg_map[$background] ?? 'has-bg-grey';
$has_image = !empty($bg_image['url']);

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-project-outcome project-outcome ' . $bg_class . ($has_image ? ' has-bg-image' : ''),
]);
?>

<section <?php echo $wrapper_attrs; ?>>

  <?php if ($has_image) : ?>
    <img class="project-outcome__bg"
         src="<?php echo esc_url($bg_image['url']); ?>"
         alt="" aria-hidden="true"
         loading="lazy" decoding="async">
  <?php endif; ?>

  <?php if (!$hide_text && ($heading || $body)) : ?>
  <div class="project-outcome__inner container">

    <?php if ($heading) : ?>
      <h2 class="project-outcome__heading"><?php echo esc_html($heading); ?></h2>
    <?php elseif ($is_preview) : ?>
      <p class="project-outcome__placeholder">Project Outcome<br><small>Add a Heading to get started.</small></p>
    <?php endif; ?>

    <?php if ($body) : ?>
      <div class="project-outcome__body"><?php echo wp_kses_post($body); ?></div>
    <?php endif; ?>

  </div>
  <?php endif; ?>

</section>
