<?php
/**
 * ACF Options Pages + Site-wide field groups.
 *
 * Registers three Options sub-pages under a "Site Settings" parent:
 *   • General          — contact info, footer tagline & CTA
 *   • Announcement Bar — top-of-page message strip
 *   • Archive Heroes   — hero banner for Blog and Projects archives
 *
 * All values are read with:  get_field( 'field_name', 'option' )
 *
 * @package Globeiron
 */

declare(strict_types=1);

// Bail if ACF Pro is not active.
if ( ! function_exists('acf_add_options_page')) {
    return;
}

// ─── Options pages ────────────────────────────────────────────────────────────

acf_add_options_page([
    'page_title'  => __('Site Settings', 'globeiron'),
    'menu_title'  => __('Site Settings', 'globeiron'),
    'menu_slug'   => 'globeiron-site-settings',
    'capability'  => 'manage_options',
    'redirect'    => true,
    'icon_url'    => 'dashicons-admin-generic',
    'position'    => 60,
]);

acf_add_options_sub_page([
    'page_title'  => __('General', 'globeiron'),
    'menu_title'  => __('General', 'globeiron'),
    'menu_slug'   => 'globeiron-general',
    'parent_slug' => 'globeiron-site-settings',
]);

acf_add_options_sub_page([
    'page_title'  => __('Announcement Bar', 'globeiron'),
    'menu_title'  => __('Announcement Bar', 'globeiron'),
    'menu_slug'   => 'globeiron-announcement',
    'parent_slug' => 'globeiron-site-settings',
]);

acf_add_options_sub_page([
    'page_title'  => __('Archive Heroes', 'globeiron'),
    'menu_title'  => __('Archive Heroes', 'globeiron'),
    'menu_slug'   => 'globeiron-archive-heroes',
    'parent_slug' => 'globeiron-site-settings',
]);

// ─── Field groups ────────────────────────────────────────────────────────────
// Field groups are defined in acf-json/ and auto-loaded by ACF from disk.
// Do NOT register them here via acf_add_local_field_group — that creates a
// timing conflict where ACF's JSON loader fires last on acf/init and wins,
// reverting to the old JSON definition and hiding any PHP-only additions.

// ─── Helper: render an archive hero from options ──────────────────────────────

/**
 * Output the interior hero banner for an archive page using ACF options values.
 *
 * @param string $prefix  Field prefix, e.g. 'blog_hero' or 'projects_hero'.
 */
function globeiron_archive_hero(string $prefix): void {
    $heading  = (string) (get_field("{$prefix}_heading",    'option') ?: '');
    $sub      = (string) (get_field("{$prefix}_subheading", 'option') ?: '');
    $image    = get_field("{$prefix}_image",   'option');
    $opacity  = (int)    (get_field("{$prefix}_overlay",    'option') ?? 65);

    if (empty($heading)) {
        return;
    }

    $bg_url        = $image['url'] ?? '';
    $hero_style    = $bg_url ? "--hero-bg: url('" . esc_url($bg_url) . "');" : '';
    $overlay_style = 'opacity: ' . ($opacity / 100) . ';';
    ?>
    <div class="archive-hero wp-block-globeiron-hero-interior hero-interior is-align-left"<?php if ($hero_style) : ?> style="<?php echo esc_attr($hero_style); ?>"<?php endif; ?>>
        <?php if ($bg_url) : ?>
            <div class="hero-interior__overlay" style="<?php echo esc_attr($overlay_style); ?>"></div>
        <?php endif; ?>
        <div class="hero-interior__inner">
            <div class="hero-interior__content">
                <h1 class="hero-interior__heading"><?php echo esc_html($heading); ?></h1>
                <?php if ($sub) : ?>
                    <p class="hero-interior__subheading"><?php echo wp_kses_post($sub); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
