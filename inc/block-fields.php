<?php
/**
 * ACF local field groups for every custom block.
 *
 * Registered programmatically so they live in version control and require
 * no ACF JSON sync or manual admin setup.
 *
 * Blocks covered:
 *   globeiron/hero-home            — adds hero_style, form_title, form_shortcode
 *   globeiron/section-features     — eyebrow, heading, body, features repeater
 *   globeiron/section-services     — eyebrow, heading, tagline, services repeater
 *   globeiron/section-roofing-types — heading, roofing_types repeater
 *   globeiron/section-process      — eyebrow, heading, steps repeater
 *   globeiron/section-testimonials — eyebrow, heading, cta, source toggle, repeater
 *   globeiron/section-contact-map  — eyebrow, heading, cta, map_embed, working_hours
 *
 * @package Globeiron
 */

declare(strict_types=1);

if (! function_exists('acf_add_local_field_group')) {
    return;
}

add_action('acf/init', function (): void {

    // ── hero-home: form-layout fields (added on top of existing bg/heading fields)
    // Only the new fields are registered here so we don't collide with any
    // existing ACF group that already has background_image, heading, etc.
    acf_add_local_field_group([
        'key'    => 'group_block_hero_home_form',
        'title'  => 'Hero — Layout & Form Card',
        'fields' => [
            [
                'key'           => 'field_hero_home_style',
                'label'         => 'Hero Style',
                'name'          => 'hero_style',
                'type'          => 'radio',
                'choices'       => [
                    'standard'  => 'Standard (centred headline + CTA buttons)',
                    'with_form' => 'With Form Card (left headline · right inspection form)',
                ],
                'default_value' => 'standard',
                'layout'        => 'vertical',
            ],
            [
                'key'               => 'field_hero_home_form_title',
                'label'             => 'Form Card Title',
                'name'              => 'form_title',
                'type'              => 'text',
                'default_value'     => 'Get a Free Inspection',
                'conditional_logic' => [[
                    ['field' => 'field_hero_home_style', 'operator' => '==', 'value' => 'with_form'],
                ]],
            ],
            [
                'key'               => 'field_hero_home_form_shortcode',
                'label'             => 'Form Shortcode',
                'name'              => 'form_shortcode',
                'type'              => 'text',
                'instructions'      => 'Paste a CF7 shortcode, e.g. [contact-form-7 id="123"]. Leave blank to use the built-in fallback form.',
                'conditional_logic' => [[
                    ['field' => 'field_hero_home_style', 'operator' => '==', 'value' => 'with_form'],
                ]],
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'globeiron/hero-home']]],
    ]);

    // ── section-features ─────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_section_features',
        'title'  => 'Features Section',
        'fields' => [
            [
                'key'         => 'field_sf_eyebrow',
                'label'       => 'Eyebrow Text',
                'name'        => 'eyebrow',
                'type'        => 'text',
                'placeholder' => 'Why people are choosing us',
            ],
            [
                'key'      => 'field_sf_heading',
                'label'    => 'Heading',
                'name'     => 'heading',
                'type'     => 'text',
                'required' => 1,
            ],
            [
                'key'  => 'field_sf_body',
                'label' => 'Body Paragraph',
                'name'  => 'body',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'          => 'field_sf_features',
                'label'        => 'Feature Items',
                'name'         => 'features',
                'type'         => 'repeater',
                'min'          => 1,
                'max'          => 6,
                'layout'       => 'block',
                'button_label' => 'Add Feature',
                'sub_fields'   => [
                    [
                        'key'      => 'field_sf_feat_title',
                        'label'    => 'Title',
                        'name'     => 'title',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                    [
                        'key'   => 'field_sf_feat_desc',
                        'label' => 'Description',
                        'name'  => 'description',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ],
                ],
            ],
            [
                'key'   => 'field_sf_show_globes',
                'label' => 'Show Globes',
                'name'  => 'show_globes',
                'type'  => 'true_false',
                'ui'    => 1,
            ],
            [
                'key'          => 'field_sf_show_scroll_indicator',
                'label'        => 'Show Scroll Indicator',
                'name'         => 'show_scroll_indicator',
                'type'         => 'true_false',
                'instructions' => 'Adds an animated crosshair + dotted line that crosses into the next section.',
                'ui'           => 1,
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'acf/section-features']]],
    ]);

    // Section Services fields are managed via acf-json/group_block_section_services.json

    // ── section-process ───────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_section_process',
        'title'  => 'Process Steps Section',
        'fields' => [
            [
                'key'         => 'field_sp_eyebrow',
                'label'       => 'Eyebrow Text',
                'name'        => 'eyebrow',
                'type'        => 'text',
                'placeholder' => 'Never wonder what\'s next',
            ],
            [
                'key'      => 'field_sp_heading',
                'label'    => 'Heading',
                'name'     => 'heading',
                'type'     => 'text',
                'required' => 1,
            ],
            [
                'key'          => 'field_sp_steps',
                'label'        => 'Steps',
                'name'         => 'steps',
                'type'         => 'repeater',
                'min'          => 1,
                'max'          => 6,
                'layout'       => 'block',
                'button_label' => 'Add Step',
                'sub_fields'   => [
                    [
                        'key'     => 'field_sp_step_icon_type',
                        'label'   => 'Icon',
                        'name'    => 'icon_type',
                        'type'    => 'select',
                        'choices' => [
                            'reach_out'      => 'Envelope (Reach Out)',
                            'inspection'     => 'Clipboard Check (Inspection)',
                            'project_starts' => 'Gear/Settings (Project Starts)',
                            'custom'         => 'Custom Image',
                        ],
                        'default_value' => 'reach_out',
                    ],
                    [
                        'key'               => 'field_sp_step_icon_img',
                        'label'             => 'Custom Icon Image',
                        'name'              => 'icon',
                        'type'              => 'image',
                        'return_format'     => 'array',
                        'preview_size'      => 'thumbnail',
                        'conditional_logic' => [[
                            ['field' => 'field_sp_step_icon_type', 'operator' => '==', 'value' => 'custom'],
                        ]],
                    ],
                    [
                        'key'      => 'field_sp_step_title',
                        'label'    => 'Step Title',
                        'name'     => 'title',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                    [
                        'key'   => 'field_sp_step_desc',
                        'label' => 'Description',
                        'name'  => 'description',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ],
                ],
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'globeiron/section-process']]],
    ]);

    // ── section-testimonials ─────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_section_testimonials',
        'title'  => 'Testimonials Section',
        'fields' => [
            [
                'key'         => 'field_st_eyebrow',
                'label'       => 'Eyebrow Text',
                'name'        => 'eyebrow',
                'type'        => 'text',
                'placeholder' => 'We provide the best service for you',
            ],
            [
                'key'      => 'field_st_heading',
                'label'    => 'Heading',
                'name'     => 'heading',
                'type'     => 'text',
                'required' => 1,
            ],
            [
                'key'   => 'field_st_cta_label',
                'label' => 'CTA Button Label (optional)',
                'name'  => 'cta_label',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_st_cta_url',
                'label' => 'CTA Button URL',
                'name'  => 'cta_url',
                'type'  => 'url',
            ],
            [
                'key'           => 'field_st_source',
                'label'         => 'Testimonial Source',
                'name'          => 'testimonials_source',
                'type'          => 'radio',
                'choices'       => [
                    'manual' => 'Manual (enter testimonials below)',
                    'cpt'    => 'Automatic (pull from Testimonials post type)',
                ],
                'default_value' => 'manual',
                'layout'        => 'horizontal',
            ],
            [
                'key'               => 'field_st_count',
                'label'             => 'Number of Testimonials to Show',
                'name'              => 'testimonials_count',
                'type'              => 'number',
                'default_value'     => 3,
                'min'               => 1,
                'max'               => 9,
                'conditional_logic' => [[
                    ['field' => 'field_st_source', 'operator' => '==', 'value' => 'cpt'],
                ]],
            ],
            [
                'key'               => 'field_st_testimonials',
                'label'             => 'Testimonials',
                'name'              => 'testimonials',
                'type'              => 'repeater',
                'min'               => 1,
                'layout'            => 'block',
                'button_label'      => 'Add Testimonial',
                'conditional_logic' => [[
                    ['field' => 'field_st_source', 'operator' => '==', 'value' => 'manual'],
                ]],
                'sub_fields' => [
                    [
                        'key'           => 'field_st_t_project_img',
                        'label'         => 'Project / House Photo',
                        'name'          => 'project_image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                    ],
                    [
                        'key'           => 'field_st_t_client_photo',
                        'label'         => 'Client Photo (avatar)',
                        'name'          => 'client_photo',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                    ],
                    [
                        'key'      => 'field_st_t_name',
                        'label'    => 'Client Name',
                        'name'     => 'client_name',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                    [
                        'key'   => 'field_st_t_location',
                        'label' => 'Client Location',
                        'name'  => 'client_location',
                        'type'  => 'text',
                    ],
                    [
                        'key'           => 'field_st_t_rating',
                        'label'         => 'Star Rating',
                        'name'          => 'rating',
                        'type'          => 'range',
                        'min'           => 1,
                        'max'           => 5,
                        'step'          => 1,
                        'default_value' => 5,
                    ],
                    [
                        'key'      => 'field_st_t_review',
                        'label'    => 'Review Text',
                        'name'     => 'review',
                        'type'     => 'textarea',
                        'rows'     => 3,
                        'required' => 1,
                    ],
                ],
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'globeiron/section-testimonials']]],
    ]);

    // ── section-contact-map ───────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_section_contact_map',
        'title'  => 'Contact & Map Section',
        'fields' => [
            [
                'key'         => 'field_scm_eyebrow',
                'label'       => 'Eyebrow Text',
                'name'        => 'eyebrow',
                'type'        => 'text',
                'placeholder' => 'Get in touch',
            ],
            [
                'key'      => 'field_scm_heading',
                'label'    => 'Heading',
                'name'     => 'heading',
                'type'     => 'text',
                'required' => 1,
            ],
            [
                'key'           => 'field_scm_cta_label',
                'label'         => 'CTA Button Label',
                'name'          => 'cta_label',
                'type'          => 'text',
                'default_value' => 'Get a Free Estimate',
            ],
            [
                'key'           => 'field_scm_cta_url',
                'label'         => 'CTA Button URL',
                'name'          => 'cta_url',
                'type'          => 'url',
                'default_value' => '/contact',
            ],
            [
                'key'          => 'field_scm_map_embed',
                'label'        => 'Google Maps Embed Code',
                'name'         => 'map_embed',
                'type'         => 'textarea',
                'rows'         => 4,
                'instructions' => 'Paste the full &lt;iframe&gt; embed code from Google Maps (Share → Embed a map). Only the iframe tag is rendered.',
            ],
            [
                'key'         => 'field_scm_hours',
                'label'       => 'Working Hours',
                'name'        => 'working_hours',
                'type'        => 'text',
                'placeholder' => 'Everyday 8am – 6pm',
                'instructions'=> 'Phone, email, and address are pulled automatically from Site Settings → General.',
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'globeiron/section-contact-map']]],
    ]);

    // ── hero-interior ─────────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_hero_interior',
        'title'  => 'Hero — Interior Page',
        'fields' => [
            [
                'key'           => 'field_hi_layout',
                'label'         => 'Layout',
                'name'          => 'hero_interior_layout',
                'type'          => 'radio',
                'choices'       => [
                    'standard'      => 'Standard — full-width banner with background image',
                    'split_collage' => 'Split Collage — left text / right image grid',
                ],
                'default_value' => 'standard',
                'layout'        => 'vertical',
            ],
            [
                'key'      => 'field_hi_heading',
                'label'    => 'Heading',
                'name'     => 'heading',
                'type'     => 'text',
                'required' => 1,
            ],
            // ── Standard layout fields ────────────────────────────────────────
            [
                'key'               => 'field_hi_bg_image',
                'label'             => 'Background Image',
                'name'              => 'background_image',
                'type'              => 'image',
                'return_format'     => 'array',
                'preview_size'      => 'medium',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'standard'],
                ]],
            ],
            [
                'key'               => 'field_hi_overlay_opacity',
                'label'             => 'Overlay Opacity (%)',
                'name'              => 'overlay_opacity',
                'type'              => 'range',
                'min'               => 0,
                'max'               => 100,
                'step'              => 5,
                'default_value'     => 65,
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'standard'],
                ]],
            ],
            [
                'key'               => 'field_hi_subheading',
                'label'             => 'Subheading',
                'name'              => 'subheading',
                'type'              => 'textarea',
                'rows'              => 2,
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'standard'],
                ]],
            ],
            [
                'key'               => 'field_hi_text_align',
                'label'             => 'Text Alignment',
                'name'              => 'text_align',
                'type'              => 'radio',
                'choices'           => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                'default_value'     => 'left',
                'layout'            => 'horizontal',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'standard'],
                ]],
            ],
            // ── Split Collage layout fields ───────────────────────────────────
            [
                'key'               => 'field_hi_body',
                'label'             => 'Body Paragraph',
                'name'              => 'body',
                'type'              => 'textarea',
                'rows'              => 3,
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'split_collage'],
                ]],
            ],
            [
                'key'               => 'field_hi_cta_label',
                'label'             => 'CTA Button Label',
                'name'              => 'cta_label',
                'type'              => 'text',
                'default_value'     => 'Request a Quote',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'split_collage'],
                ]],
            ],
            [
                'key'               => 'field_hi_cta_url',
                'label'             => 'CTA Button URL',
                'name'              => 'cta_url',
                'type'              => 'url',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'split_collage'],
                ]],
            ],
            [
                'key'               => 'field_hi_cta_secondary_label',
                'label'             => 'Secondary CTA Label',
                'name'              => 'cta_secondary_label',
                'type'              => 'text',
                'instructions'      => 'Optional. Renders as a ghost button next to the primary CTA.',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'split_collage'],
                ]],
            ],
            [
                'key'               => 'field_hi_cta_secondary_url',
                'label'             => 'Secondary CTA URL',
                'name'              => 'cta_secondary_url',
                'type'              => 'url',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'split_collage'],
                ]],
            ],
            [
                'key'               => 'field_hi_collage_img_1',
                'label'             => 'Collage Image — Top',
                'name'              => 'collage_image_1',
                'type'              => 'image',
                'return_format'     => 'array',
                'preview_size'      => 'medium',
                'instructions'      => 'Landscape orientation. Recommended: 900 × 350 px.',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'split_collage'],
                ]],
            ],
            [
                'key'               => 'field_hi_collage_img_2',
                'label'             => 'Collage Image — Bottom',
                'name'              => 'collage_image_2',
                'type'              => 'image',
                'return_format'     => 'array',
                'preview_size'      => 'medium',
                'instructions'      => 'Portrait orientation. Recommended: 450 × 350 px.',
                'conditional_logic' => [[
                    ['field' => 'field_hi_layout', 'operator' => '==', 'value' => 'split_collage'],
                ]],
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'globeiron/hero-interior']]],
    ]);

    // ── section-post-listing ──────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_section_post_listing',
        'title'  => 'Post Listing',
        'fields' => [
            [
                'key'           => 'field_spl_post_type',
                'label'         => 'Post Type',
                'name'          => 'listing_post_type',
                'type'          => 'select',
                'choices'       => [
                    'post'    => 'Blog Posts',
                    'project' => 'Projects',
                ],
                'default_value' => 'post',
                'ui'            => 1,
            ],
            [
                'key'          => 'field_spl_grid_title',
                'label'        => 'Section Heading',
                'name'         => 'grid_title',
                'type'         => 'text',
                'instructions' => 'Optional. Displayed above the grid.',
            ],
            [
                'key'          => 'field_spl_grid_content',
                'label'        => 'Section Description',
                'name'         => 'grid_content',
                'type'         => 'wysiwyg',
                'media_upload' => 0,
                'toolbar'      => 'basic',
                'tabs'         => 'visual',
                'instructions' => 'Optional. Displayed below the heading.',
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'acf/section-post-listing']]],
    ]);

    // ── section-reviews ───────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_section_reviews',
        'title'  => 'Customer Reviews',
        'fields' => [
            [
                'key'           => 'field_sr_heading',
                'label'         => 'Section Heading',
                'name'          => 'heading',
                'type'          => 'text',
                'default_value' => 'Customer Reviews',
            ],
            [
                'key'           => 'field_sr_overall_rating',
                'label'         => 'Overall Star Rating',
                'name'          => 'overall_rating',
                'type'          => 'number',
                'default_value' => 5,
                'min'           => 1,
                'max'           => 5,
                'step'          => 1,
                'instructions'  => 'Aggregate rating displayed as large stars at the top right (1–5).',
            ],
            [
                'key'           => 'field_sr_rating_label',
                'label'         => 'Rating Label',
                'name'          => 'rating_label',
                'type'          => 'text',
                'default_value' => 'Excellent',
                'instructions'  => 'Short label shown below the stars, e.g. "Excellent".',
            ],
            [
                'key'           => 'field_sr_reviews_count',
                'label'         => 'Number of Reviews to Show',
                'name'          => 'reviews_count',
                'type'          => 'number',
                'default_value' => 12,
                'min'           => 1,
                'max'           => 50,
                'step'          => 1,
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'acf/section-reviews']]],
    ]);

    // Project Header fields are managed via acf-json/group_project_header.json

    // ── project-details ───────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_block_project_details',
        'title'  => 'Project Details',
        'fields' => [
            [
                'key'      => 'field_pd_heading',
                'label'    => 'Heading',
                'name'     => 'heading',
                'type'     => 'text',
                'required' => 1,
            ],
            [
                'key'          => 'field_pd_image_pairs',
                'label'        => 'Before / After Image Pairs',
                'name'         => 'image_pairs',
                'type'         => 'repeater',
                'instructions' => 'Add one row per before/after comparison. Arrows navigate between pairs.',
                'min'          => 0,
                'max'          => 20,
                'layout'       => 'block',
                'button_label' => 'Add Image Pair',
                'sub_fields'   => [
                    [
                        'key'           => 'field_pd_pair_before',
                        'label'         => 'Before Image',
                        'name'          => 'before_image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                    ],
                    [
                        'key'           => 'field_pd_pair_after',
                        'label'         => 'After Image',
                        'name'          => 'after_image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                    ],
                ],
            ],
            [
                'key'          => 'field_pd_highlights',
                'label'        => 'Highlights',
                'name'         => 'highlights',
                'type'         => 'repeater',
                'min'          => 0,
                'max'          => 10,
                'layout'       => 'block',
                'button_label' => 'Add Highlight',
                'sub_fields'   => [
                    [
                        'key'      => 'field_pd_highlight_heading',
                        'label'    => 'Heading',
                        'name'     => 'highlight_heading',
                        'type'     => 'text',
                        'required' => 1,
                    ],
                    [
                        'key'          => 'field_pd_highlight_body',
                        'label'        => 'Body',
                        'name'         => 'highlight_body',
                        'type'         => 'wysiwyg',
                        'tabs'         => 'visual',
                        'toolbar'      => 'basic',
                        'media_upload' => 0,
                        'delay'        => 0,
                    ],
                ],
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'acf/project-details']]],
    ]);

}); // end acf/init
