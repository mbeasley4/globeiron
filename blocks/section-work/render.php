<?php
/**
 * Section: Our Work (Projects Slider)
 *
 * ACF fields on this block (group_6a15b4e4aeaed):
 *   headline        (Text)        — section heading
 *   description     (WYSIWYG)     — optional intro beneath the heading
 *   select_projects (Post Object) — curated list; leave empty for most-recent fallback
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading     = (string) (get_field('headline')    ?: 'Our Work in the Field');
$description = get_field('description') ?: '';

// Build project list: curated selection or most-recent fallback.
$selected = get_field('select_projects');
$projects = [];

if (!empty($selected)) {
    $raw = is_array($selected) ? $selected : [$selected];
    $projects = array_values(array_filter($raw, fn($p) => $p instanceof WP_Post));
}

if (empty($projects)) {
    $projects = get_posts([
        'post_type'      => 'project',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);
}

if (empty($projects)) {
    if (is_admin()) {
        echo '<p style="padding:2rem;text-align:center;color:#657693;">No published projects found. Add projects to see the slider.</p>';
    }
    return;
}

$block_id = 'sw-' . ($block['id'] ?? uniqid());

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-section-work section-work',
    'id'    => esc_attr($block_id),
]);
?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-work__inner">

    <div class="section-work__header">
      <h2 class="section-work__heading"><?php echo esc_html($heading); ?></h2>
      <?php if ($description) : ?>
        <div class="section-work__subheading"><?php echo wp_kses_post($description); ?></div>
      <?php endif; ?>
    </div>

    <nav class="section-work__tabs" aria-label="<?php esc_attr_e('Project navigation', 'globeiron'); ?>">
      <?php foreach ($projects as $i => $project) : ?>
        <button
          class="section-work__tab<?php echo $i === 0 ? ' is-active' : ''; ?>"
          aria-label="<?php printf(esc_attr__('Go to project %d', 'globeiron'), $i + 1); ?>"
          data-index="<?php echo $i; ?>"
          type="button"
        ><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></button>
      <?php endforeach; ?>
    </nav>

    <div class="splide section-work__splide" id="<?php echo esc_attr($block_id); ?>"
         aria-label="<?php esc_attr_e('Our Work', 'globeiron'); ?>">
      <div class="splide__track">
        <ul class="splide__list">
          <?php foreach ($projects as $i => $project) :
            $img_id       = get_post_thumbnail_id($project->ID);
            $img_src      = $img_id ? wp_get_attachment_image_src($img_id, 'large') : null;
            $img_alt      = $img_id ? (string) get_post_meta($img_id, '_wp_attachment_image_alt', true) : '';
            $img_fallback = GLOBEIRON_URI . '/img/no-image.png';
            $eyebrow      = (string) (get_field('eyebrow',       $project->ID) ?: '');
            $location     = (string) (get_field('tech_location',  $project->ID) ?: get_post_meta($project->ID, 'location', true));
            $description  = (string) (get_the_excerpt($project) ?: get_post_meta($project->ID, 'project_summary', true));
            $raw_cats     = get_the_terms($project->ID, 'project_category');
            $cat_name     = ($raw_cats && !is_wp_error($raw_cats)) ? $raw_cats[0]->name : '';
            $permalink    = get_permalink($project->ID);
          ?>
          <li class="splide__slide section-work__slide">
            <div class="section-work__slide-image">
              <img
                src="<?php echo $img_src ? esc_url($img_src[0]) : esc_url($img_fallback); ?>"
                <?php if ($img_src) : ?>
                width="<?php echo (int) $img_src[1]; ?>"
                height="<?php echo (int) $img_src[2]; ?>"
                <?php endif; ?>
                alt="<?php echo esc_attr($img_alt ?: get_the_title($project)); ?>"
                loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"
              >
            </div>

            <div class="section-work__slide-info">
              <h3 class="section-work__project-title"><?php echo esc_html(get_the_title($project)); ?></h3>

              <?php if ($description) : ?>
                <p class="section-work__project-description"><?php echo esc_html($description); ?></p>
              <?php endif; ?>

              <?php if ($eyebrow) : ?>
                <p class="section-work__project-type"><?php echo esc_html($eyebrow); ?></p>
              <?php endif; ?>

              <?php if ($location || $cat_name) : ?>
                <dl class="section-work__meta">
                  <?php if ($location) : ?>
                    <div class="section-work__meta-row">
                      <dt><?php esc_html_e('Location:', 'globeiron'); ?></dt>
                      <dd><?php echo esc_html($location); ?></dd>
                    </div>
                  <?php endif; ?>
                  <?php if ($cat_name) : ?>
                    <div class="section-work__meta-row">
                      <dt><?php esc_html_e('Category:', 'globeiron'); ?></dt>
                      <dd><?php echo esc_html($cat_name); ?></dd>
                    </div>
                  <?php endif; ?>
                </dl>
              <?php endif; ?>

              <a href="<?php echo esc_url($permalink); ?>" class="btn btn--brand section-work__cta">
                <?php esc_html_e('View Project Details', 'globeiron'); ?>
              </a>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

  </div>
</section>
