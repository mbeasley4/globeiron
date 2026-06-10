<?php
/**
 * Partners / Certifications block — server-side render.
 *
 * ACF fields:
 *   display   (button_group) — partners | certifications | both
 *   heading   (text)         — optional headline above logo grid
 *   intro     (textarea)     — optional intro paragraph
 *   columns   (button_group) — 3 | 4 | 5
 *   bg_style  (button_group) — light (default) | dark | brand
 *
 * @package Globeiron
 */

declare(strict_types=1);

$display  = (string) (get_field('display')  ?: 'partners');
$heading  = (string) (get_field('heading')  ?: '');
$intro    = (string) (get_field('intro')    ?: '');
$bg_style = esc_attr(get_field('bg_style')  ?: 'light');

$query_args_base = [
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
];

$items = [];

if ($display === 'partners' || $display === 'both') {
    foreach (get_posts(array_merge($query_args_base, ['post_type' => 'partner'])) as $p) {
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
    foreach (get_posts(array_merge($query_args_base, ['post_type' => 'certification'])) as $c) {
        $items[] = [
            'id'         => $c->ID,
            'type'       => 'certification',
            'name'       => get_the_title($c),
            'url'        => (string) get_post_meta($c->ID, '_certification_url', true),
            'menu_order' => (int) $c->menu_order,
        ];
    }
}

if ($display === 'both') {
    usort($items, fn($a, $b) => $a['menu_order'] <=> $b['menu_order']);
}

if (empty($items)) {
    return;
}

$col_count  = max(2, min((int) (get_field('columns') ?: 5), count($items)));
$grid_style = sprintf(
    '--partners-cols-sm: %d; --partners-cols-md: %d; --partners-cols: %d;',
    max(2, min(3, $col_count)),
    max(2, min(4, $col_count)),
    $col_count
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
        $logo_id    = get_post_thumbnail_id($item['id']);
        $item_class = 'partners__item' . ($item['type'] === 'certification' ? ' partners__item--cert' : '');
      ?>
      <li class="<?php echo esc_attr($item_class); ?>">
        <?php if ($item['url']) : ?>
          <a href="<?php echo esc_url($item['url']); ?>"
             target="_blank" rel="noopener noreferrer"
             aria-label="<?php echo esc_attr($item['name']); ?>"
             class="partners__tile-link">
        <?php endif; ?>
        <div class="partners__tile">
          <?php if ($logo_id) : ?>
            <?php echo wp_get_attachment_image($logo_id, 'medium', false, [
                'class'   => 'partners__logo',
                'loading' => 'lazy',
                'decoding'=> 'async',
                'alt'     => esc_attr($item['name']),
            ]); ?>
          <?php else : ?>
            <span class="partners__logo-placeholder"><?php echo esc_html($item['name']); ?></span>
          <?php endif; ?>
          <?php if ($item['url']) : ?>
            <span class="partners__tile-external" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></span>
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
