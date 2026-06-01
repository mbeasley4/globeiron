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

// ─── Field groups ─────────────────────────────────────────────────────────────

add_action('acf/init', function (): void {

    // ── General — contact & footer ────────────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_globeiron_general',
        'title'    => __('Contact & Footer', 'globeiron'),
        'fields'   => [
            [
                'key'           => 'field_site_phone',
                'label'         => __('Office Phone', 'globeiron'),
                'name'          => 'site_phone',
                'type'          => 'text',
                'placeholder'   => '513-371-1841',
                'default_value' => '513-371-1841',
            ],
            [
                'key'           => 'field_site_fax',
                'label'         => __('Fax Number', 'globeiron'),
                'name'          => 'site_fax',
                'type'          => 'text',
                'placeholder'   => '513-563-6444',
                'default_value' => '513-563-6444',
            ],
            [
                'key'           => 'field_site_email',
                'label'         => __('Email Address', 'globeiron'),
                'name'          => 'site_email',
                'type'          => 'email',
                'placeholder'   => 'contact@hkcroofing.com',
                'default_value' => 'contact@hkcroofing.com',
            ],
            [
                'key'           => 'field_site_address',
                'label'         => __('Address', 'globeiron'),
                'name'          => 'site_address',
                'type'          => 'textarea',
                'rows'          => 3,
                'instructions'  => __('Each line will be displayed on its own row.', 'globeiron'),
                'default_value' => "6161 Wiehe Road\nCincinnati, OH 45237",
            ],
            [
                'key'           => 'field_footer_serving_tagline',
                'label'         => __('Footer Serving Tagline', 'globeiron'),
                'name'          => 'footer_serving_tagline',
                'type'          => 'text',
                'instructions'  => __('Displayed in the banner at the top of the footer.', 'globeiron'),
                'placeholder'   => __('Proudly Serving Greater Cincinnati, Columbus and Indianapolis Neighbors', 'globeiron'),
            ],
            [
                'key'           => 'field_footer_cta_label',
                'label'         => __('Footer CTA Button Label', 'globeiron'),
                'name'          => 'footer_cta_label',
                'type'          => 'text',
                'default_value' => __('Get a Free Estimate', 'globeiron'),
            ],
            [
                'key'           => 'field_footer_cta_url',
                'label'         => __('Footer CTA Button URL', 'globeiron'),
                'name'          => 'footer_cta_url',
                'type'          => 'url',
                'default_value' => '/contact',
            ],
            // ── Social media links ──────────────────────────────────────────
            [
                'key'   => 'field_social_facebook',
                'label' => __('Facebook URL', 'globeiron'),
                'name'  => 'social_facebook',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_social_instagram',
                'label' => __('Instagram URL', 'globeiron'),
                'name'  => 'social_instagram',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_social_linkedin',
                'label' => __('LinkedIn URL', 'globeiron'),
                'name'  => 'social_linkedin',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_social_youtube',
                'label' => __('YouTube URL', 'globeiron'),
                'name'  => 'social_youtube',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_social_tiktok',
                'label' => __('TikTok URL', 'globeiron'),
                'name'  => 'social_tiktok',
                'type'  => 'url',
            ],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'globeiron-general']]],
    ]);

    // ── Announcement Bar ──────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_globeiron_announcement',
        'title'  => __('Announcement Bar', 'globeiron'),
        'fields' => [
            [
                'key'           => 'field_ann_enabled',
                'label'         => __('Enable Announcement Bar', 'globeiron'),
                'name'          => 'ann_enabled',
                'type'          => 'true_false',
                'instructions'  => __('Toggle to show or hide the announcement bar sitewide.', 'globeiron'),
                'default_value' => 0,
                'ui'            => 1,
                'ui_on_text'    => __('Enabled', 'globeiron'),
                'ui_off_text'   => __('Disabled', 'globeiron'),
            ],
            [
                'key'          => 'field_ann_text',
                'label'        => __('Announcement Text', 'globeiron'),
                'name'         => 'ann_text',
                'type'         => 'text',
                'instructions' => __('The message displayed across the top of every page.', 'globeiron'),
            ],
            [
                'key'          => 'field_ann_url',
                'label'        => __('Link URL (optional)', 'globeiron'),
                'name'         => 'ann_url',
                'type'         => 'url',
            ],
            [
                'key'           => 'field_ann_link_label',
                'label'         => __('Link Label', 'globeiron'),
                'name'          => 'ann_link_label',
                'type'          => 'text',
                'default_value' => __('Learn more', 'globeiron'),
            ],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'globeiron-announcement']]],
    ]);

    // ── Archive Heroes ────────────────────────────────────────────────────────

    $hero_fields = function (string $prefix, string $heading_default, string $sub_default): array {
        return [
            [
                'key'           => "field_{$prefix}_heading",
                'label'         => __('Heading', 'globeiron'),
                'name'          => "{$prefix}_heading",
                'type'          => 'text',
                'default_value' => $heading_default,
                'required'      => 1,
            ],
            [
                'key'           => "field_{$prefix}_subheading",
                'label'         => __('Subheading', 'globeiron'),
                'name'          => "{$prefix}_subheading",
                'type'          => 'textarea',
                'rows'          => 2,
                'default_value' => $sub_default,
            ],
            [
                'key'          => "field_{$prefix}_image",
                'label'        => __('Background Image', 'globeiron'),
                'name'         => "{$prefix}_image",
                'type'         => 'image',
                'return_format'=> 'array',
                'preview_size' => 'medium',
            ],
            [
                'key'           => "field_{$prefix}_overlay",
                'label'         => __('Overlay Opacity (%)', 'globeiron'),
                'name'          => "{$prefix}_overlay",
                'type'          => 'range',
                'min'           => 0,
                'max'           => 90,
                'step'          => 5,
                'default_value' => 65,
            ],
        ];
    };

    acf_add_local_field_group([
        'key'    => 'group_globeiron_archive_heroes',
        'title'  => __('Archive Heroes', 'globeiron'),
        'fields' => array_merge(
            [
                [
                    'key'     => 'field_archive_heroes_blog_tab',
                    'label'   => __('Blog Archive Hero', 'globeiron'),
                    'name'    => '',
                    'type'    => 'tab',
                    'placement' => 'top',
                ],
            ],
            $hero_fields(
                'blog_hero',
                __('Blog', 'globeiron'),
                __('News, tips, and insights from the Globe Iron Roofing team.', 'globeiron')
            ),
            [
                [
                    'key'     => 'field_archive_heroes_projects_tab',
                    'label'   => __('Projects Archive Hero', 'globeiron'),
                    'name'    => '',
                    'type'    => 'tab',
                    'placement' => 'top',
                ],
            ],
            $hero_fields(
                'projects_hero',
                __('Our Projects', 'globeiron'),
                __('A portfolio of roofing work from across the region.', 'globeiron')
            )
        ),
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'globeiron-archive-heroes']]],
    ]);
});

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
    $style         = $bg_url ? "background-image: url('" . esc_url($bg_url) . "');" : '';
    $overlay_style = 'opacity: ' . ($opacity / 100) . ';';
    ?>
    <div class="archive-hero wp-block-globeiron-hero-interior hero-interior is-align-left">
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
