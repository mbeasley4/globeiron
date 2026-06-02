<?php
/**
 * Partners / Certifications block — server-side render.
 *
 * ACF fields:
 *   display  (button_group) — partners | certifications | both
 *   heading  (text)         — optional headline above logo grid
 *   intro    (textarea)     — optional intro paragraph
 *   bg_style (button_group) — dark | light | brand
 *
 * @package Globeiron
 */

declare(strict_types=1);

$display  = (string) (get_field('display')  ?: 'partners');
$heading  = (string) (get_field('heading')  ?: '');
$intro    = (string) (get_field('intro')    ?: '');
$bg_style = esc_attr(get_field('bg_style')  ?: 'dark');

// ── Build item list based on display mode ─────────────────────────────────────
$query_args_base = [
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
];

$items = [];

if ($display === 'partners' || $display === 'both') {
    $partners = get_posts(array_merge($query_args_base, ['post_type' => 'partner']));
    foreach ($partners as $p) {
        $items[] = [
            'id'         => $p->ID,
            'type'       => 'partner',
            'name'       => get_the_title($p),
            'url'        => (string) get_post_meta($p->ID, '_partner_url', true),
            'menu_order' => (int) $p->menu_order,
        ];
    }
}

if ($display === 'certifications' || $display === 'both') {
    $certs = get_posts(array_merge($query_args_base, ['post_type' => 'certification']));
    foreach ($certs as $c) {
        $items[] = [
            'id'         => $c->ID,
            'type'       => 'certification',
            'name'       => get_the_title($c),
            'url'        => (string) get_post_meta($c->ID, '_certification_url', true),
            'menu_order' => (int) $c->menu_order,
        ];
    }
}

// When showing both, sort the merged list by menu_order so the admin's
// drag-and-drop ordering plugin controls the combined sequence.
if ($display === 'both') {
    usort($items, fn($a, $b) => $a['menu_order'] <=> $b['menu_order']);
}

if (empty($items)) {
    return;
}

$col_setting = max(3, min(5, (int) (get_field('columns') ?: 5)));
$grid_cols   = max(2, min($col_setting, count($items)));
$grid_cols_sm = max(2, min(3, count($items)));
$grid_cols_md = max(2, min(4, count($items)));
$grid_style = sprintf(
    '--partners-cols-sm: %d; --partners-cols-md: %d; --partners-cols: %d;',
    $grid_cols_sm,
    $grid_cols_md,
    $grid_cols
);

$aria_label = match ($display) {
    'certifications' => __('Certifications', 'globeiron'),
    'both'           => __('Partners and certifications', 'globeiron'),
    default          => __('Partner logos', 'globeiron'),
};

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "wp-block-globeiron-section-partnership is-style-{$bg_style} is-display-{$display}",
]);
?>
<section <?php echo $wrapper_attributes; ?>>
  <div class="partners__inner">

    <?php if ($heading || $intro) : ?>
    <header class="partners__header">
      <?php if ($heading) : ?>
        <h2 class="partners__heading"><?php echo esc_html($heading); ?></h2>
      <?php endif; ?>
      <?php if ($intro) : ?>
        <p class="partners__intro"><?php echo esc_html($intro); ?></p>
      <?php endif; ?>
    </header>
    <?php endif; ?>

    <ul class="partners__grid" style="<?php echo esc_attr($grid_style); ?>" aria-label="<?php echo esc_attr($aria_label); ?>">
      <?php foreach ($items as $item) :
        $is_cert = $item['type'] === 'certification';
        $logo_id = get_post_thumbnail_id($item['id']);
        $logo    = $logo_id ? wp_get_attachment_image_src($logo_id, 'medium') : false;
        $item_class = 'partners__item' . ($is_cert ? ' partners__item--cert' : '');
      ?>
      <li class="<?php echo esc_attr($item_class); ?>">
        <?php if ($item['url']) : ?>
          <a href="<?php echo esc_url($item['url']); ?>"
             target="_blank" rel="noopener noreferrer"
             aria-label="<?php echo esc_attr($item['name']); ?>"
             class="partners__tile-link">
        <?php endif; ?>
        <div class="partners__tile">
          <?php if ($logo) : ?>
            <img src="<?php echo esc_url($logo[0]); ?>"
                 alt="<?php echo esc_attr($item['name']); ?>"
                 class="partners__logo"
                 width="<?php echo esc_attr((string) $logo[1]); ?>"
                 height="<?php echo esc_attr((string) $logo[2]); ?>"
                 loading="lazy" decoding="async">
          <?php else : ?>
            <span class="partners__logo-placeholder"><?php echo esc_html($item['name']); ?></span>
          <?php endif; ?>
        </div>
        <?php if ($item['url']) : ?>
          </a>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>

  </div>
</section>
