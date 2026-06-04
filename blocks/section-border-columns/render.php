<?php

/**
 * Section Border Columns Block — server-side render (ACF)
 *
 * ACF fields:
 *   heading       (text)
 *   intro_content (wysiwyg, optional)
 *   background    (select: blue | white | grey)
 *   columns       (repeater, 3–4 items)
 *     └─ icon_image (image, array)
 *     └─ title      (text)
 *     └─ content    (wysiwyg)
 *     └─ cta        (link, array)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading    = (string) (get_field('heading')       ?: '');
$intro      = (string) (get_field('intro_content') ?: '');
$background = (string) (get_field('background')    ?: 'blue');
$columns_raw = get_field('columns');
$columns     = is_array($columns_raw) ? $columns_raw : [];

if (empty($heading) || empty($columns)) {
    return;
}

$col_count   = min(4, count($columns));
$centered    = $col_count < 3 ? ' section-border-columns--centered' : '';

$bg_map = [
    'blue'  => 'has-bg-blue',
    'white' => 'has-bg-white',
    'grey'  => 'has-bg-grey',
];
$bg_class = $bg_map[$background] ?? 'has-bg-blue';

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-section-border-columns section-border-columns {$bg_class}{$centered}",
]);

$crosshair = '<svg class="section-border-columns__crosshair" viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
  <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';
?>

<section <?php echo $wrapper_attrs; ?> style="--col-count: <?php echo $col_count; ?>;">

    <div class="section-border-columns__border-box" aria-hidden="true">

        <div class="section-border-columns__corner section-border-columns__corner--tl">
            <?php echo $crosshair; ?>
        </div>
        <div class="section-border-columns__corner section-border-columns__corner--tr">
            <?php echo $crosshair; ?>
        </div>
        <div class="section-border-columns__corner section-border-columns__corner--bl">
            <?php echo $crosshair; ?>
        </div>
        <div class="section-border-columns__corner section-border-columns__corner--br">
            <?php echo $crosshair; ?>
        </div>

        <div class="section-border-columns__edge section-border-columns__edge--top"></div>
        <div class="section-border-columns__edge section-border-columns__edge--bottom"></div>
        <div class="section-border-columns__edge section-border-columns__edge--left"></div>
        <div class="section-border-columns__edge section-border-columns__edge--right"></div>

    </div>

    <div class="section-border-columns__inner">

        <h2 class="section-border-columns__heading"><?php echo esc_html($heading); ?></h2>

        <?php if ($intro) : ?>
            <div class="section-border-columns__intro">
                <?php echo wp_kses_post($intro); ?>
            </div>
        <?php endif; ?>

        <div class="section-border-columns__grid">
            <?php foreach ($columns as $col) :
                $icon       = $col['icon_image']    ?? null;
                $title      = (string) ($col['title']   ?? '');
                $body       = (string) ($col['content'] ?? '');
                $cta        = $col['cta']                ?? null;
                $cta_url    = (string) ($cta['url']      ?? '');
                $cta_label  = (string) ($cta['title']    ?? '');
                $cta_target = (string) ($cta['target']   ?? '');
            ?>
                <div class="section-border-columns__col">

                    <?php if ($icon) : ?>
                        <figure class="section-border-columns__icon" aria-hidden="true">
                            <img src="<?php echo esc_url($icon['sizes']['medium'] ?? $icon['url']); ?>"
                                 alt=""
                                 loading="lazy">
                        </figure>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h3 class="section-border-columns__col-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($body) : ?>
                        <div class="section-border-columns__col-content">
                            <?php echo wp_kses_post($body); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($cta_label && $cta_url) : ?>
                        <a href="<?php echo esc_url($cta_url); ?>"
                           class="section-border-columns__cta"
                           <?php if ($cta_target) : ?>target="<?php echo esc_attr($cta_target); ?>" rel="noopener"<?php endif; ?>>
                            <?php echo esc_html($cta_label); ?>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>

    </div>

</section>
