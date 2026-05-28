<?php
/**
 * Section: Process Steps
 *
 * Figma section: "Our process make it easy"
 * Layout: light background with decorative world-map SVG, eyebrow → heading →
 *         3-column steps each with a yellow icon square, bold title, and description.
 *
 * ACF fields:
 *   eyebrow (text)
 *   heading (text)
 *   steps   (repeater)
 *     └─ icon_type   (select: reach_out | inspection | project_starts | custom)
 *     └─ icon        (image — used when icon_type = 'custom')
 *     └─ title       (text)
 *     └─ description (textarea)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$section_title = (string) (get_field('section_title') ?: '');
$heading       = (string) (get_field('heading')       ?: '');
$steps   = get_field('steps') ?: [];

if (empty($heading)) {
    return;
}

// ── Bundled inline SVG icons ──────────────────────────────────────────────────
$icons = [
    'reach_out' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
    'inspection' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>',
    'project_starts' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>',
];

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-process section-process',
]);
?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-process__inner">

    <div class="section-process__header">
      <?php if ($section_title) : ?>
        <p class="section-process__eyebrow"><?php echo esc_html($section_title); ?></p>
      <?php endif; ?>
      <h2 class="section-process__heading"><?php echo esc_html($heading); ?></h2>
    </div>

    <?php if ($steps) : ?>
      <div class="section-process__steps">
        <?php foreach ($steps as $i => $step) :
          $icon_type   = $step['icon_type']   ?? 'reach_out';
          $custom_icon = $step['icon']        ?? null;
          $title       = $step['title']       ?? '';
          $description = $step['description'] ?? '';
        ?>
          <div class="section-process__step">
            <div class="section-process__icon-wrap" aria-hidden="true">
              <?php if ($icon_type === 'custom' && $custom_icon) : ?>
                <img src="<?php echo esc_url($custom_icon['url']); ?>" alt="" width="40" height="40" loading="lazy">
              <?php else : ?>
                <?php echo $icons[$icon_type] ?? $icons['reach_out']; ?>
              <?php endif; ?>
            </div>
            <h3 class="section-process__step-title"><?php echo esc_html($title); ?></h3>
            <?php if ($description) : ?>
              <p class="section-process__step-desc"><?php echo wp_kses_post($description); ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>
