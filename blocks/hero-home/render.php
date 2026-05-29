<?php
/**
 * Homepage Hero Block — server-side render (ACF)
 *
 * @package Globeiron
 */

declare(strict_types=1);

// ── Fields ────────────────────────────────────────────────────────────────────
$headline         = (string) (get_field('headline')          ?: '');
$hero_content     = get_field('hero_content'); // wysiwyg — already filtered HTML
$cta_button_1     = get_field('cta_button_1'); // array: url, title, target
$cta_button_2     = get_field('cta_button_2');
$background_image = get_field('background_image'); // array
$bg_url  = $background_image['url'] ?? '';
$bg_attr = $bg_url ? ' style="background-image:url(\'' . esc_url($bg_url) . '\')"' : '';

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'wp-block-globeiron-hero-home hero-home',
]);
?>

<?php if ($bg_url) : ?>
<script>
(function(){var l=document.createElement('link');l.rel='preload';l.as='image';l.href='<?php echo esc_js($bg_url); ?>';l.setAttribute('fetchpriority','high');document.head.appendChild(l);})();
</script>
<?php endif; ?>

<section <?php echo $wrapper_attrs . $bg_attr; ?>>
  <img class="hero-home__globe-left"
       src="<?php echo esc_url(get_template_directory_uri()); ?>/img/hero/hero-bot-left.png"
       alt="" aria-hidden="true" loading="eager">
  <img class="hero-home__globe-right"
       src="<?php echo esc_url(get_template_directory_uri()); ?>/img/hero/hero-bot-right.png"
       alt="" aria-hidden="true" loading="eager">

  <div class="hero-home__inner">

    <div class="hero-home__content">
      <?php if ($headline) : ?>
        <h1 class="hero-home__heading"><?php echo esc_html($headline); ?></h1>
      <?php endif; ?>
      <?php if ($hero_content) : ?>
        <div class="hero-home__content-body"><?php echo wp_kses_post($hero_content); ?></div>
      <?php endif; ?>

      <?php if ($cta_button_1 || $cta_button_2) : ?>
        <div class="hero-home__actions">
          <?php if ($cta_button_1) : ?>
            <a href="<?php echo esc_url($cta_button_1['url']); ?>"
               class="btn btn--brand"
               <?php if ( ! empty($cta_button_1['target'])) : ?>target="<?php echo esc_attr($cta_button_1['target']); ?>"<?php endif; ?>>
              <?php echo esc_html($cta_button_1['title']); ?>
            </a>
          <?php endif; ?>
          <?php if ($cta_button_2) : ?>
            <a href="<?php echo esc_url($cta_button_2['url']); ?>"
               class="btn btn--outline-white"
               <?php if ( ! empty($cta_button_2['target'])) : ?>target="<?php echo esc_attr($cta_button_2['target']); ?>"<?php endif; ?>>
              <?php echo esc_html($cta_button_2['title']); ?>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="hero-home__scroll-indicator" aria-hidden="true">
      <svg class="hero-home__scroll-crosshair hero-home__scroll-crosshair--top"
           viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
           xmlns="http://www.w3.org/2000/svg">
        <circle cx="14" cy="14" r="12" pathLength="1"/>
        <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
        <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
      </svg>
      <div class="hero-home__scroll-line"></div>
      <svg class="hero-home__scroll-crosshair hero-home__scroll-crosshair--end"
           viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
           xmlns="http://www.w3.org/2000/svg">
        <circle cx="14" cy="14" r="12" pathLength="1"/>
        <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
        <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
      </svg>
    </div>

  </div><!-- /.hero-home__inner -->

</section>
