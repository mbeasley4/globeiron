<?php
/**
 * Section: Map — Regional Reach
 *
 * ACF fields:
 *   eyebrow             (Text)      — optional eyebrow label
 *   heading             (Text)      — required heading
 *   map_section_content (Textarea)  — body copy beneath heading
 *   regions             (Repeater)  — service area items; when present shows crosshair rail
 *     └─ region_name   (Text)
 *     └─ region_desc   (Text)
 *   map_embed           (Textarea)  — Google Maps <iframe>
 *   background_color    (Button)    — white (default) | grey | blue
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading     = (string) (get_field('heading')             ?: '');
$map_content = (string) (get_field('map_section_content') ?: '');
$regions_raw = get_field('regions');
$regions     = is_array($regions_raw) ? $regions_raw : [];
$map_embed   = (string) (get_field('map_embed')           ?: '');
$bg_color    = (string) (get_field('background_color')    ?: 'white');

if (empty($heading)) {
    return;
}

$bg_class = match ($bg_color) {
    'grey'  => 'has-bg-grey',
    'blue'  => 'has-bg-blue',
    default => 'has-bg-white',
};

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-section-contact-map section-contact-map {$bg_class}",
]);

$allowed_iframe = [
    'iframe' => [
        'src'             => true,
        'width'           => true,
        'height'          => true,
        'style'           => true,
        'allowfullscreen' => true,
        'loading'         => true,
        'referrerpolicy'  => true,
        'title'           => true,
        'frameborder'     => true,
    ],
];

$crosshair = '<svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="14" cy="14" r="12" pathLength="1"/><line x1="14" y1="7" x2="14" y2="21" pathLength="1"/><line x1="7" y1="14" x2="21" y2="14" pathLength="1"/></svg>';
?>

<?php $has_regions = ! empty($regions); ?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-contact-map__inner sm:tw-px-6 lg:tw-px-10 tw-max-w-[1440px] tw-mx-auto">

    <div class="section-contact-map__panel section-contact-map__panel--v1 is-active<?php echo $has_regions ? '' : ' has-no-regions'; ?>">

      <div class="section-contact-map__text">
        <h2 class="section-contact-map__heading"><?php echo esc_html($heading); ?></h2>

        <?php if ($map_content) : ?>
          <p class="section-contact-map__content"><?php echo $map_content; ?></p>
        <?php endif; ?>

        <?php if ($has_regions) : ?>
          <div class="section-contact-map__regions-wrapper">
            <div class="section-contact-map__rail" aria-hidden="true">
              <div class="section-contact-map__rail-crosshair section-contact-map__rail-crosshair--top">
                <?php echo $crosshair; ?>
              </div>
              <div class="section-contact-map__rail-line"></div>
              <div class="section-contact-map__rail-crosshair section-contact-map__rail-crosshair--bottom">
                <?php echo $crosshair; ?>
              </div>
            </div>
            <ul class="section-contact-map__regions">
              <?php foreach ($regions as $region) :
                $name = (string) ($region['region_name'] ?? '');
                $desc = (string) ($region['region_desc'] ?? '');
              ?>
                <li class="section-contact-map__region">
                  <div class="section-contact-map__region-body">
                    <?php if ($name) : ?>
                      <strong class="section-contact-map__region-name"><?php echo esc_html($name); ?></strong>
                    <?php endif; ?>
                    <?php if ($desc) : ?>
                      <p class="section-contact-map__region-desc"><?php echo esc_html($desc); ?></p>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>

      <?php if (! $has_regions && $map_embed) : ?>
        <div class="section-contact-map__divider" aria-hidden="true">
          <div class="section-contact-map__divider-crosshair section-contact-map__divider-crosshair--top">
            <?php echo $crosshair; ?>
          </div>
          <div class="section-contact-map__divider-line"></div>
          <div class="section-contact-map__divider-crosshair section-contact-map__divider-crosshair--bottom">
            <?php echo $crosshair; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($map_embed) : ?>
        <div class="section-contact-map__map">
          <?php
          // Ensure the iframe defers loading until near viewport to avoid
          // pulling in the full Maps JS API (~193 KiB) on initial page load.
          $lazy_embed = preg_replace(
              '/(<iframe\b[^>]*?)(?:\s+loading=["\'][^"\']*["\'])?((?:[^>]*?)>)/i',
              '$1 loading="lazy"$2',
              $map_embed
          );
          echo wp_kses($lazy_embed, $allowed_iframe);
          ?>
        </div>
      <?php endif; ?>

    </div>

  </div>
</section>
