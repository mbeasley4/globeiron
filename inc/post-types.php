<?php
declare(strict_types=1);

add_action('init', function (): void {

    // ─────────────────────────────────────────
    // TAXONOMIES (PROJECTS)
    // ─────────────────────────────────────────

    register_taxonomy('project_category', 'project', [
        'label'             => __('Project Categories', 'globeiron'),
        'labels'            => [
            'name'          => __('Project Categories', 'globeiron'),
            'singular_name' => __('Project Category',  'globeiron'),
            'add_new_item'  => __('Add New Category',  'globeiron'),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'project-category'],
    ]);

    // ─────────────────────────────────────────
    // PROJECT CPT
    // ─────────────────────────────────────────

    register_post_type('project', [
        'label' => __('Projects', 'globeiron'),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'revisions',
            'custom-fields'
        ],
        'taxonomies' => ['project_category'],
        'has_archive' => false,
        'rewrite' => ['slug' => 'projects', 'with_front' => false],
    ]);

    // ─────────────────────────────────────────
    // PARTNER CPT
    // ─────────────────────────────────────────

    register_post_type('partner', [
        'label'         => __('Partners', 'globeiron'),
        'labels'        => [
            'name'                  => __('Partners', 'globeiron'),
            'singular_name'         => __('Partner', 'globeiron'),
            'add_new'               => __('Add Partner', 'globeiron'),
            'add_new_item'          => __('Add New Partner', 'globeiron'),
            'edit_item'             => __('Edit Partner', 'globeiron'),
            'new_item'              => __('New Partner', 'globeiron'),
            'search_items'          => __('Search Partners', 'globeiron'),
            'not_found'             => __('No partners found.', 'globeiron'),
            'not_found_in_trash'    => __('No partners found in trash.', 'globeiron'),
            'featured_image'        => __('Partner Logo', 'globeiron'),
            'set_featured_image'    => __('Set partner logo', 'globeiron'),
            'remove_featured_image' => __('Remove partner logo', 'globeiron'),
            'use_featured_image'    => __('Use as partner logo', 'globeiron'),
        ],
        'public'        => false,   // no front-end single pages
        'show_ui'       => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-groups',
        'menu_position' => 25,
        'supports'      => [
            'title',      // partner / organisation name
            'thumbnail',  // partner logo — the featured image IS the logo
        ],
        'has_archive'   => false,
        'rewrite'       => false,
    ]);

    // ─────────────────────────────────────────
    // CERTIFICATION CPT
    // ─────────────────────────────────────────

    register_post_type('certification', [
        'label'         => __('Certifications', 'globeiron'),
        'labels'        => [
            'name'                  => __('Certifications',          'globeiron'),
            'singular_name'         => __('Certification',           'globeiron'),
            'add_new'               => __('Add Certification',       'globeiron'),
            'add_new_item'          => __('Add New Certification',   'globeiron'),
            'edit_item'             => __('Edit Certification',      'globeiron'),
            'new_item'              => __('New Certification',       'globeiron'),
            'search_items'          => __('Search Certifications',   'globeiron'),
            'not_found'             => __('No certifications found.',            'globeiron'),
            'not_found_in_trash'    => __('No certifications found in trash.',   'globeiron'),
            'featured_image'        => __('Certification Badge',     'globeiron'),
            'set_featured_image'    => __('Set certification badge', 'globeiron'),
            'remove_featured_image' => __('Remove badge',            'globeiron'),
            'use_featured_image'    => __('Use as certification badge', 'globeiron'),
        ],
        'public'        => false,   // no front-end single pages
        'show_ui'       => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-awards',
        'menu_position' => 27,
        'supports'      => [
            'title',      // certification / organisation name
            'thumbnail',  // badge / seal — the featured image IS the badge
        ],
        'has_archive'   => false,
        'rewrite'       => false,
    ]);

    // ─────────────────────────────────────────
    // TEAM MEMBER CPT
    // ─────────────────────────────────────────

    register_post_type('team_member', [
        'label'  => __('Team Members', 'globeiron'),
        'labels' => [
            'name'               => __('Team Members',       'globeiron'),
            'singular_name'      => __('Team Member',        'globeiron'),
            'add_new'            => __('Add Team Member',    'globeiron'),
            'add_new_item'       => __('Add New Team Member','globeiron'),
            'edit_item'          => __('Edit Team Member',   'globeiron'),
            'new_item'           => __('New Team Member',    'globeiron'),
            'search_items'       => __('Search Team Members','globeiron'),
            'not_found'          => __('No team members found.',       'globeiron'),
            'not_found_in_trash' => __('No team members found in trash.','globeiron'),
            'featured_image'     => __('Headshot',           'globeiron'),
            'set_featured_image' => __('Set headshot',       'globeiron'),
        ],
        'public'         => false,  // no single-page URLs
        'show_ui'        => true,
        'show_in_rest'   => true,   // required for ACF relationship field in block editor
        'show_in_menu'   => true,
        'menu_icon'      => 'dashicons-id-alt',
        'menu_position'  => 24,
        'supports'       => ['title', 'revisions'],  // name = post title; fields via ACF
        'has_archive'    => false,
        'rewrite'        => false,
    ]);

    // ─────────────────────────────────────────
    // REVIEW CPT
    // ─────────────────────────────────────────

    register_post_type('globeiron_review', [
        'label'         => __('Reviews', 'globeiron'),
        'labels'        => [
            'name'               => __('Reviews',          'globeiron'),
            'singular_name'      => __('Review',           'globeiron'),
            'add_new'            => __('Add Review',       'globeiron'),
            'add_new_item'       => __('Add New Review',   'globeiron'),
            'edit_item'          => __('Edit Review',      'globeiron'),
            'new_item'           => __('New Review',       'globeiron'),
            'view_item'          => __('View Review',      'globeiron'),
            'search_items'       => __('Search Reviews',   'globeiron'),
            'not_found'          => __('No reviews found.',              'globeiron'),
            'not_found_in_trash' => __('No reviews found in trash.',     'globeiron'),
        ],
        'public'        => false,   // no front-end single pages
        'show_ui'       => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-star-filled',
        'menu_position' => 26,
        'supports'      => [
            'title',    // reviewer name
            'editor',   // review body
            'thumbnail', // optional reviewer photo
        ],
        'has_archive'   => false,
        'rewrite'       => false,
    ]);

}, 0);

// ─────────────────────────────────────────
// PARTNER META + REST FIELDS
// ─────────────────────────────────────────

// Expose the partner logo URL as a top-level REST field so the block
// editor can display a live preview without a separate media request.
add_action('rest_api_init', function (): void {
    register_rest_field('partner', 'logo_url', [
        'get_callback' => function (array $post_arr): string {
            $thumb_id = get_post_thumbnail_id($post_arr['id']);
            if ( ! $thumb_id) {
                return '';
            }
            $src = wp_get_attachment_image_src($thumb_id, 'medium');
            return $src ? (string) $src[0] : '';
        },
        'schema' => [
            'type'        => 'string',
            'description' => 'URL of the partner logo (featured image, medium size).',
        ],
    ]);
});

register_post_meta('partner', '_partner_url', [
    'type'              => 'string',
    'single'            => true,
    'show_in_rest'      => true,
    'sanitize_callback' => 'esc_url_raw',
]);

// ─────────────────────────────────────────
// CERTIFICATION META + REST FIELDS
// ─────────────────────────────────────────

add_action('rest_api_init', function (): void {
    register_rest_field('certification', 'logo_url', [
        'get_callback' => function (array $post_arr): string {
            $thumb_id = get_post_thumbnail_id($post_arr['id']);
            if ( ! $thumb_id) {
                return '';
            }
            $src = wp_get_attachment_image_src($thumb_id, 'medium');
            return $src ? (string) $src[0] : '';
        },
        'schema' => [
            'type'        => 'string',
            'description' => 'URL of the certification badge (featured image, medium size).',
        ],
    ]);
});

register_post_meta('certification', '_certification_url', [
    'type'              => 'string',
    'single'            => true,
    'show_in_rest'      => true,
    'sanitize_callback' => 'esc_url_raw',
]);

// ─────────────────────────────────────────
// PROJECT META
// ─────────────────────────────────────────

register_post_meta('project', 'location', [
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
]);

register_post_meta('project', 'project_summary', [
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
]);

// Store as JSON string of attachment IDs
register_post_meta('project', 'project_gallery', [
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
]);

register_post_meta('project', 'project_specs', [
    'single' => true,
    'type'   => 'object',
    'show_in_rest' => [
        'schema' => [
            'type' => 'object',
            'properties' => [
                'material' => ['type' => 'string'],
                'system'   => ['type' => 'string'],
                'scope'    => ['type' => 'string'],
            ],
        ],
    ],
]);

// Review fields are managed by ACF — see acf-json/group_globeiron_review.json

// ─────────────────────────────────────────
// TESTIMONIAL RELATIONSHIP
// ─────────────────────────────────────────

register_post_meta('globeiron_testimonial', 'related_projects', [
    'single' => true,
    'type'   => 'array',
    'show_in_rest' => [
        'schema' => [
            'type'  => 'array',
            'items' => [
                'type' => 'integer'
            ],
        ],
    ],
]);