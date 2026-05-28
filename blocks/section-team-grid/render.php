<?php
/**
 * Section: Team Grid
 *
 * ACF fields:
 *   heading          (text)         — optional section headline
 *   body             (wysiwyg)      — optional intro copy
 *   background_color (button_group) — white (default) | grey | blue
 *   team_members     (relationship) — ordered list of team_member posts
 *
 * Each team_member post carries:
 *   role     (text)    — job title
 *   headshot (image)   — professional headshot
 *   bio      (wysiwyg)
 *
 * @package Globeiron
 */

declare(strict_types=1);

$heading  = (string) (get_field('heading')          ?: '');
$body     = (string) (get_field('body')             ?: '');
$bg_color = (string) (get_field('background_color') ?: 'white');
$members  = get_field('team_members')               ?: [];

if (empty($members)) {
    return;
}

$bg_class = match ($bg_color) {
    'grey'  => 'has-bg-grey',
    'blue'  => 'has-bg-blue',
    default => 'has-bg-white',
};

$wrapper_attrs = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-section-team-grid section-team-grid {$bg_class}",
]);

$crosshair = '<svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <circle cx="14" cy="14" r="12" pathLength="1"/>
  <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
  <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
</svg>';

$arrow_icon = '<svg viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <line x1="2" y1="9" x2="16" y2="9"/>
  <polyline points="10,3 16,9 10,15"/>
</svg>';

$close_icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <line x1="18" y1="6"  x2="6"  y2="18"/>
  <line x1="6"  y1="6"  x2="18" y2="18"/>
</svg>';
?>

<section <?php echo $wrapper_attrs; ?>>
  <div class="section-team-grid__inner">

    <?php if ($heading || $body) : ?>
      <header class="section-team-grid__header">

        <?php if ($heading) : ?>
          <h2 class="section-team-grid__heading"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>

        <div class="section-team-grid__header-ornament" aria-hidden="true">
          <div class="section-team-grid__header-crosshair"><?php echo $crosshair; ?></div>
          <div class="section-team-grid__header-line"></div>
        </div>

        <?php if ($body) : ?>
          <div class="section-team-grid__body"><?php echo wp_kses_post($body); ?></div>
        <?php endif; ?>

      </header>
    <?php endif; ?>

    <div class="section-team-grid__grid">
      <?php foreach ($members as $member) :
        $member_id = $member->ID;
        $name      = get_the_title($member);
        $role      = (string) (get_field('role',     $member_id) ?: '');
        $headshot  = get_field('headshot', $member_id) ?: [];
        $bio       = (string) (get_field('bio',      $member_id) ?: '');
        $modal_id  = 'team-modal-' . $member_id;
        $img_src   = $headshot['sizes']['medium_large'] ?? $headshot['sizes']['large'] ?? $headshot['url'] ?? '';
        $img_alt   = $headshot['alt'] ?? $name;
      ?>
        <button
          class="team-card"
          type="button"
          data-team-card="<?php echo esc_attr($modal_id); ?>"
          aria-haspopup="dialog"
          aria-label="<?php echo esc_attr("View bio for {$name}"); ?>">

          <div class="team-card__photo-wrap">

            <div class="team-card__ornament" aria-hidden="true">
              <div class="team-card__crosshair"><?php echo $crosshair; ?></div>
              <div class="team-card__ornament-hline"></div>
              <div class="team-card__ornament-vline"></div>
            </div>

            <?php if ($img_src) : ?>
              <img
                class="team-card__photo"
                src="<?php echo esc_url($img_src); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                loading="lazy"
                decoding="async">
            <?php else : ?>
              <div class="team-card__photo-placeholder" aria-hidden="true"></div>
            <?php endif; ?>

            <div class="team-card__cta" aria-hidden="true">
              <span>Read Bio</span>
              <?php echo $arrow_icon; ?>
            </div>

          </div>

          <div class="team-card__meta">
            <span class="team-card__name"><?php echo esc_html($name); ?></span>
            <?php if ($role) : ?>
              <span class="team-card__role"><?php echo esc_html($role); ?></span>
            <?php endif; ?>
          </div>

        </button>
      <?php endforeach; ?>
    </div>

  </div>

  <?php foreach ($members as $member) :
    $member_id = $member->ID;
    $name      = get_the_title($member);
    $role      = (string) (get_field('role',     $member_id) ?: '');
    $headshot  = get_field('headshot', $member_id) ?: [];
    $bio       = (string) (get_field('bio',      $member_id) ?: '');
    $modal_id  = 'team-modal-' . $member_id;
    $img_src   = $headshot['sizes']['large'] ?? $headshot['url'] ?? '';
    $img_alt   = $headshot['alt'] ?? $name;
  ?>
    <dialog
      class="team-modal"
      id="<?php echo esc_attr($modal_id); ?>"
      aria-label="<?php echo esc_attr("{$name} — {$role}"); ?>">

      <div class="team-modal__panel">

        <button class="team-modal__close" type="button" aria-label="Close">
          <?php echo $close_icon; ?>
        </button>

        <div class="team-modal__layout">

          <?php if ($img_src) : ?>
            <div class="team-modal__media">
              <img
                src="<?php echo esc_url($img_src); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                loading="lazy"
                decoding="async">
            </div>
          <?php endif; ?>

          <div class="team-modal__content">
            <?php if ($role) : ?>
              <p class="team-modal__role"><?php echo esc_html($role); ?></p>
            <?php endif; ?>
            <h2 class="team-modal__name"><?php echo esc_html($name); ?></h2>
            <?php if ($bio) : ?>
              <div class="team-modal__bio"><?php echo wp_kses_post($bio); ?></div>
            <?php endif; ?>
          </div>

        </div>

      </div>
    </dialog>
  <?php endforeach; ?>

</section>
