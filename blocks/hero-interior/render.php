<?php

/**
 * Interior Hero Block — server-side render (ACF)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$layout  = get_field('hero_interior_layout') ?: 'standard';
$heading = get_field('heading');

if (empty($heading)) {
    return;
}

// ── Split Collage layout ───────────────────────────────────────────────────────
if ($layout === 'split_collage') {

    $body                = get_field('body');
    $cta_link            = get_field('cta_link') ?: [];
    $cta_label           = $cta_link['title']  ?? '';
    $cta_url             = $cta_link['url']    ?? '';
    $cta_target          = $cta_link['target'] ?? '';
    $cta_secondary_label = get_field('cta_secondary_label');
    $cta_secondary_url   = get_field('cta_secondary_url');
    $collage_img_1       = get_field('collage_image_1');
    $collage_img_2       = get_field('collage_image_2');
    $background_image    = get_field('background_image');
    $bg_url              = $background_image['url'] ?? '';
    $style               = $bg_url ? "--hero-bg: url('" . esc_url($bg_url) . "');" : '';

    $wrapper_attributes = get_block_wrapper_attributes([
        'class' => 'wp-block-globeiron-hero-interior hero-interior hero-interior--collage',
    ]);

    $collage_crosshair = '<svg class="hero-interior__collage-ornament" viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
        <circle cx="14" cy="14" r="12" pathLength="1"></circle>
        <line x1="14" y1="7" x2="14" y2="21" pathLength="1"></line>
        <line x1="7" y1="14" x2="21" y2="14" pathLength="1"></line>
    </svg>';
?>
    <section <?php echo $wrapper_attributes; ?> style="<?php echo esc_attr($style); ?>">
        <?php echo str_replace('hero-interior__collage-ornament', 'hero-interior__collage-ornament hero-interior__collage-ornament--top', $collage_crosshair); ?>
        <span class="hero-interior__collage-line hero-interior__collage-line--vertical" aria-hidden="true"></span>
        <?php echo str_replace('hero-interior__collage-ornament', 'hero-interior__collage-ornament hero-interior__collage-ornament--bottom', $collage_crosshair); ?>
        <?php echo str_replace('hero-interior__collage-ornament', 'hero-interior__collage-ornament hero-interior__collage-ornament--left', $collage_crosshair); ?>
        <span class="hero-interior__collage-line hero-interior__collage-line--horizontal" aria-hidden="true"></span>
        <?php echo str_replace('hero-interior__collage-ornament', 'hero-interior__collage-ornament hero-interior__collage-ornament--right', $collage_crosshair); ?>

        <?php if ($collage_img_1) : ?>
            <figure class="hero-interior__collage-top" aria-hidden="true">
                <img src="<?php echo esc_url($collage_img_1['sizes']['large'] ?? $collage_img_1['url']); ?>"
                    alt=""
                    loading="eager">
            </figure>
        <?php endif; ?>

        <div class="hero-interior--collage-wrap">
            <div class="hero-interior__content">
                <h1 class="hero-interior__heading">
                    <?php echo esc_html($heading); ?>
                </h1>
                <?php if ($body) : ?>
                    <div class="hero-interior__body">
                        <?php echo wp_kses_post($body); ?>
                    </div>
                <?php endif; ?>
                <?php if ($cta_label && $cta_url) : ?>
                    <div class="hero-interior__cta-row">
                        <a href="<?php echo esc_url($cta_url); ?>"
                           class="btn btn--primary"
                           <?php if ($cta_target) : ?>target="<?php echo esc_attr($cta_target); ?>" rel="noopener noreferrer"<?php endif; ?>>
                            <?php echo esc_html($cta_label); ?>
                        </a>
                        <?php if ($cta_secondary_label && $cta_secondary_url) : ?>
                            <a href="<?php echo esc_url($cta_secondary_url); ?>" class="btn btn--secondary">
                                <?php echo esc_html($cta_secondary_label); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="hero-interior__collage" aria-hidden="true">
                <?php if ($collage_img_2) : ?>
                    <figure class="hero-interior__collage-worker">
                        <img src="<?php echo esc_url($collage_img_2['sizes']['large'] ?? $collage_img_2['url']); ?>"
                            alt=""
                            loading="eager">
                    </figure>
                <?php endif; ?>
                <div class="hero-interior__collage-fill"></div>
            </div>
        </div>
    </section>
<?php
    return;
}

// ── Standard layout (existing) ─────────────────────────────────────────────────
$background_image = get_field('background_image');
$eyebrow          = get_field('eyebrow');
$subheading       = get_field('subheading');
$text_align       = get_field('text_align') ?: 'left';

$bg_url        = $background_image['url'] ?? '';
$style         = $bg_url ? "--hero-bg: url('" . esc_url($bg_url) . "');" : '';
$align_class   = "is-align-{$text_align}";

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-hero-interior hero-interior {$align_class}",
]);
?>

<section <?php echo $wrapper_attributes; ?> style="<?php echo esc_attr($style); ?>">

    <div class="hero-interior__inner">
        <div class="hero-interior__content">
            <?php if ($eyebrow) : ?>
                <p class="hero-interior__eyebrow">
                    <?php echo esc_html($eyebrow); ?>
                </p>
            <?php endif; ?>
            <h1 class="hero-interior__heading">
                <?php echo esc_html($heading); ?>
            </h1>
            <?php if ($subheading) : ?>
                <div class="hero-interior__subheading">
                    <?php echo wp_kses_post($subheading); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</section>
