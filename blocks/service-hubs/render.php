<?php

/**
 * Service Hubs Block — server-side render (ACF)
 *
 * ACF fields:
 *   heading          (text)
 *   description      (wysiwyg, optional)
 *   hide_description (true_false) — hides description without deleting it
 *   background       (select: grey | white | blue)
 *   hubs             (repeater, 2–4 items)
 *     └─ image     (image, array)
 *     └─ title     (text)
 *     └─ content   (wysiwyg)
 *     └─ cta_label (text, optional)
 *     └─ cta_url   (url, optional)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading          = (string) (get_field('heading')    ?: '');
$description      = get_field('description') ?: '';
$hide_description = (bool) get_field('hide_description');
$background       = (string) (get_field('background') ?: 'grey');
$hubs_raw         = get_field('hubs');
$hubs             = is_array($hubs_raw) ? $hubs_raw : [];

$show_description = $description && !$hide_description;

if (empty($heading) || empty($hubs)) {
    return;
}

$col_count = max(2, min(4, count($hubs)));

$bg_map = [
    'white' => 'has-bg-white',
    'blue'  => 'has-bg-blue',
    'grey'  => 'has-bg-grey',
];
$bg_class = $bg_map[$background] ?? 'has-bg-grey';

$has_desc_class = $show_description ? ' section-service-hubs--has-description' : '';

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-service-hubs section-service-hubs {$bg_class}{$has_desc_class}",
]);

$crosshair = '<svg class="section-service-hubs__ornament-crosshair" viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
  <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';
?>

<section <?php echo $wrapper_attrs; ?> style="--hub-cols: <?php echo $col_count; ?>;">
    <div class="section-service-hubs__inner">
        <div class="section-service-hubs__ornament section-service-hubs__ornament--left" aria-hidden="true">
            <?php echo $crosshair; ?>
        </div>
        <div class="section-service-hubs__ornament section-service-hubs__ornament--right" aria-hidden="true">
            <?php echo $crosshair; ?>
        </div>
        <h2 class="section-service-hubs__heading"><?php echo esc_html($heading); ?></h2>

        <?php if ($show_description) : ?>
            <div class="section-service-hubs__description">
                <?php echo wp_kses_post($description); ?>
            </div>
        <?php endif; ?>

        <div class="section-service-hubs__grid">
            <?php foreach ($hubs as $hub) :
                $img        = $hub['image']         ?? null;
                $title      = (string) ($hub['title']    ?? '');
                $content    = (string) ($hub['content']  ?? '');
                $cta        = $hub['cta']                ?? null;
                $cta_url    = (string) ($cta['url']      ?? '');
                $cta_label  = (string) ($cta['title']    ?? '');
                $cta_target = (string) ($cta['target']   ?? '');
            ?>
                <div class="section-service-hubs__card">

                    <?php if ($img) : ?>
                        <figure class="section-service-hubs__figure">
                            <img src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>"
                                alt="<?php echo esc_attr(($img['alt'] ?? '') ?: $title); ?>"
                                loading="lazy">
                        </figure>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h3 class="section-service-hubs__card-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($content) : ?>
                        <div class="section-service-hubs__card-content">
                            <?php echo wp_kses_post($content); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($cta_label && $cta_url) : ?>
                        <a href="<?php echo esc_url($cta_url); ?>"
                            class="section-service-hubs__cta"
                            <?php if ($cta_target) : ?>target="<?php echo esc_attr($cta_target); ?>" rel="noopener" <?php endif; ?>>
                            <?php echo esc_html($cta_label); ?>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>

    </div>

</section>