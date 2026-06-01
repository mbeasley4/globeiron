<?php

/**
 * ACF Block Registration Only (JSON-driven fields)
 *
 * @package Globeiron
 */

declare(strict_types=1);

// Bail if ACF not active
if (! function_exists('acf_register_block_type')) {
    return;
}

add_action('acf/init', function (): void {

    $blocks = [
        'project-outcome' => [
            'title'       => 'Project Outcome',
            'description' => 'Full-width outcome section — headline, body copy, optional background image, and grey/white/blue colour theme.',
            'icon'        => 'awards',
            'mode'        => 'preview',
        ],
        'project-details' => [
            'title'       => 'Project Details',
            'description' => 'Before/After image stack with stacked visual and a highlights repeater. Use inside single project pages.',
            'icon'        => 'images-alt2',
            'mode'        => 'preview',
        ],
        'project-header' => [
            'title'       => 'Project Header',
            'description' => 'Full-width project hero — background image, eyebrow, headline, technical snapshot card, and description.',
            'icon'        => 'building',
            'mode'        => 'preview',
            'supports'    => ['align' => ['full'], 'anchor' => true],
        ],
        'hero-home' => [
            'title'       => 'Hero — Home Page',
            'description' => 'Full-viewport homepage hero — standard (centred CTAs) or split layout with inline inspection-form card. Use on the home page only.',
            'icon'        => 'cover-image',
            'mode'        => 'preview',
        ],
        'section-process' => [
            'title'       => 'Process Steps',
            'description' => 'Our Process — eyebrow, heading, 3-step process with yellow icon squares.',
            'icon'        => 'list-view',
            'mode'        => 'preview',
        ],
        'section-testimonials' => [
            'title'       => 'Testimonials',
            'description' => 'Customer testimonials — manual repeater or pulls from Testimonials CPT.',
            'icon'        => 'format-quote',
            'mode'        => 'preview',
        ],
        'hero-interior' => [
            'title'       => 'Hero — Interior Page',
            'description' => 'Page-level header for interior pages (About, Services, Contact, etc.) — supports standard or split-collage image layout.',
            'icon'        => 'align-center',
            'mode'        => 'preview',
        ],
        'section-map' => [
            'title'       => 'Section - Map',
            'description' => 'White section with Google Maps',
            'icon'        => 'location',
            'mode'        => 'preview',
        ],
        'section-features' => [
            'title'       => 'Section - Features',
            'description' => 'Why Choose Us — heading, body, 3 columns.',
            'icon'        => 'star-filled',
            'mode'        => 'preview',
        ],
        'section-partnership' => [
            'title'       => 'Section - Partnership',
            'description' => 'Partner logo grid.',
            'icon'        => 'groups',
            'mode'        => 'preview',
        ],
        'section-certifications' => [
            'title'       => 'Section - Certifications',
            'description' => 'Certification badge grid — pulls from the Certifications CPT.',
            'icon'        => 'awards',
            'mode'        => 'preview',
        ],
        'section-services' => [
            'title'       => 'Section - Services',
            'description' => 'Dark navy header with crosshair ornaments and a 3-column photo card grid of service/roofing types.',
            'icon'        => 'hammer',
            'mode'        => 'preview',
        ],
        'section-work' => [
            'title'       => 'Section - Our Work',
            'description' => 'Numbered project slider — pulls from the Projects CPT.',
            'icon'        => 'hammer',
            'mode'        => 'preview',
        ],
        'section-cta' => [
            'title'       => 'Section - Call to Action',
            'description' => 'Headline, content and CTAs for users.',
            'icon'        => 'megaphone',
            'mode'        => 'preview',
        ],
        'service-hubs' => [
            'title'       => 'Service Hubs',
            'description' => '2–4 column hub cards — image, title, content and optional CTA link. Supports white, grey and blue backgrounds.',
            'icon'        => 'grid-view',
            'mode'        => 'preview',
        ],
        'section-border-columns' => [
            'title'       => 'Section - Border Columns',
            'description' => '3–4 icon columns inside an animated dotted-border frame. Blue default; also supports white and grey backgrounds.',
            'icon'        => 'table-col-after',
            'mode'        => 'preview',
        ],
        'section-page-hero' => [
            'title'       => 'Hero — Featured Content',
            'description' => 'Dynamic spotlight block — displays a latest blog post, latest project, or static headline/image. Reusable across any page.',
            'icon'        => 'align-pull-right',
            'mode'        => 'preview',
        ],
        'section-post-listing' => [
            'title'       => 'Section - Post Listing',
            'description' => 'Filterable, paginated post grid. Choose between Blog Posts or Projects.',
            'icon'        => 'grid-view',
            'mode'        => 'preview',
        ],
        'section-reviews' => [
            'title'       => 'Section - Customer Reviews',
            'description' => 'Splide slider of reviews pulled from the Reviews CPT with star rating header.',
            'icon'        => 'star-filled',
            'mode'        => 'preview',
        ],
        'section-team-grid' => [
            'title'       => 'Section - Team Grid',
            'description' => 'Heading, intro copy, and a selection of team members displayed as a headshot grid with bio pop-ups.',
            'icon'        => 'id-alt',
            'mode'        => 'preview',
        ],
        'section-content-image-split' => [
            'title'       => 'Section - Content / Image Split',
            'description' => '50/50 split — heading, body copy, and CTA on the left; photo on the right. Supports white, grey, and blue backgrounds.',
            'icon'        => 'align-pull-right',
            'mode'        => 'preview',
        ],
    ];

    foreach ($blocks as $name => $config) {
        acf_register_block_type([
            'name'            => $name,
            'title'           => __($config['title'], 'globeiron'),
            'description'     => __($config['description'], 'globeiron'),
            'render_template' => GLOBEIRON_DIR . "/blocks/{$name}/render.php",
            'category'        => 'globeiron',
            'icon'            => $config['icon'],
            'keywords'        => explode('-', $name),
            'supports'        => array_merge([
                'align'           => ['full'],
                'html'            => false,
                'anchor'          => true,
                'customClassName' => true,
            ], $config['supports'] ?? []),
            'align'           => 'full',
            'mode'            => $config['mode'],
        ]);
    }
});
