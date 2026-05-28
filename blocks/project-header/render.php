<?php
/**
 * Block: Project Header
 *
 * ACF fields (Hero tab):
 *   hero_image   (image)
 *   eyebrow      (text)
 *   headline     (text, required)
 *
 * Post meta (Project Details sidebar):
 *   tech_location              (text) — via group_project_meta
 *
 * ACF fields (Technical Snapshot tab):
 *   tech_intro  (textarea)
 *   tech_specs  (repeater) — spec_label (text) + spec_value (text)
 *
 * ACF fields (Description tab):
 *   project_description (wysiwyg)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$headline    = (string) (get_field('headline')   ?: '');
$eyebrow     = (string) (get_field('eyebrow')    ?: '');
$hero_image  = get_field('hero_image');
$tech_intro  = (string) (get_field('tech_intro') ?: '');
$description = get_field('project_description');

$location  = (string) (get_field('tech_location', get_the_ID()) ?: '');
$raw_specs = get_field('tech_specs') ?: [];

$specs = $location ? [['label' => 'Location', 'value' => $location]] : [];
foreach ($raw_specs as $row) {
    $label = trim((string) ($row['spec_label'] ?? ''));
    $value = trim((string) ($row['spec_value'] ?? ''));
    if ($label !== '' && $value !== '') {
        $specs[] = ['label' => $label, 'value' => $value];
    }
}

if (empty($headline)) {
    return;
}

$bg_url      = $hero_image['url'] ?? '';
$bg_alt      = $hero_image['alt'] ?? '';

$projects_url = get_post_type_archive_link('project') ?: home_url('/projects/');

$categories = get_the_terms(get_the_ID(), 'project_category');
$category   = ($categories && ! is_wp_error($categories)) ? $categories[0] : null;

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-project-header project-header',
]);

$crosshair = '<svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
  <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';
?>

<div <?php echo $wrapper_attrs; ?>>

    <!-- Ornament: desktop only, bridges hero into description (hidden on mobile via CSS) -->
    <div class="project-header__ornament" aria-hidden="true">
        <div class="project-header__ornament-crosshair"><?php echo $crosshair; ?></div>
        <div class="project-header__ornament-line"></div>
        <div class="project-header__ornament-crosshair"><?php echo $crosshair; ?></div>
    </div>

    <!-- Hero: full-bleed image, container-width content inside -->
    <div class="project-header__hero">

        <?php if ($bg_url) : ?>
            <img class="project-header__bg"
                 src="<?php echo esc_url($bg_url); ?>"
                 alt="<?php echo esc_attr($bg_alt); ?>"
                 loading="eager" decoding="async">
        <?php endif; ?>

        <!-- Topbar: full container width, back link left / category right (desktop only) -->
        <div class="project-header__topbar container">
            <a href="<?php echo esc_url($projects_url); ?>" class="project-header__back">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                    <circle cx="9" cy="9" r="8" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M10.5 6L7.5 9l3 3" stroke="currentColor" stroke-width="1.2"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to All Projects
            </a>
            <?php if ($category) : ?>
                <div class="project-header__cat-label">
                    <span>Category:</span>
                    <strong><?php echo esc_html($category->name); ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <!-- Container-width wrapper: category (mobile) + eyebrow + headline -->
        <div class="project-header__hero-inner container">

            <?php if ($category) : ?>
                <p class="project-header__cat-mobile">
                    <span>Category:</span>
                    <strong><?php echo esc_html($category->name); ?></strong>
                </p>
            <?php endif; ?>

            <div class="project-header__content">
                <h1 class="project-header__headline">
                    <?php if ($eyebrow) : ?>
                        <span class="project-header__eyebrow"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php echo esc_html($headline); ?>
                </h1>
            </div>

        </div><!-- end __hero-inner -->

    </div><!-- end __hero -->

    <?php if (! empty($specs)) : ?>
    <!-- Snapshot: stacked card on mobile; absolutely-positioned card on desktop -->
    <div class="project-header__snapshot">
        <h2 class="project-header__snapshot-title">Technical Snapshot</h2>
        <?php if ($tech_intro) : ?>
            <p class="project-header__snapshot-intro"><?php echo esc_html($tech_intro); ?></p>
        <?php endif; ?>
        <ul class="project-header__specs">
            <?php foreach ($specs as $spec) : ?>
                <li class="project-header__spec">
                    <strong><?php echo esc_html($spec['label']); ?>:</strong>
                    <?php echo esc_html($spec['value']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($description) : ?>
    <div class="project-body">
        <div class="project-body__inner container">
            <div class="project-body__description">
                <?php echo wp_kses_post($description); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
