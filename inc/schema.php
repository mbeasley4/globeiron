<?php
/**
 * Structured data — schema.org JSON-LD
 *
 * Outputs a RoofingContractor (LocalBusiness) JSON-LD block on every frontend
 * page. Includes AggregateRating when the Trustindex Google Reviews plugin has
 * data stored in the database.
 *
 * @package Globeiron
 */

declare(strict_types=1);

// ─── 1. RoofingContractor / LocalBusiness ─────────────────────────────────────
add_action('wp_head', function (): void {

    $phone    = (string) (get_field('site_phone',   'option') ?: '513-371-1841');
    $email    = (string) (get_field('site_email',   'option') ?: '');
    $raw_addr = (string) (get_field('site_address', 'option') ?: "6161 Wiehe Road\nCincinnati, OH 45237");

    // Parse "Street\nCity, ST ZIP"
    $lines  = array_values(array_filter(array_map('trim', explode("\n", $raw_addr))));
    $street = $lines[0] ?? '';
    $city   = '';
    $state  = '';
    $zip    = '';

    if (isset($lines[1]) && preg_match('/^(.+),\s*([A-Z]{2})\s+(\d{5})$/', $lines[1], $m)) {
        $city  = $m[1];
        $state = $m[2];
        $zip   = $m[3];
    }

    // Social profiles — skip empty values
    $socials = array_values(array_filter([
        (string) (get_field('social_facebook',  'option') ?: ''),
        (string) (get_field('social_instagram', 'option') ?: ''),
        (string) (get_field('social_linkedin',  'option') ?: ''),
        (string) (get_field('social_youtube',   'option') ?: ''),
        (string) (get_field('social_tiktok',    'option') ?: ''),
    ]));

    // Site logo
    $logo_id  = get_theme_mod('custom_logo');
    $logo_url = $logo_id ? (string) wp_get_attachment_image_url($logo_id, 'full') : '';
    if (! $logo_url) {
        $logo_url = get_template_directory_uri() . '/img/globe-iron-roofing-logo.svg';
    }

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'RoofingContractor',
        '@id'         => home_url('/#business'),
        'name'        => get_bloginfo('name'),
        'url'         => home_url('/'),
        'logo'        => $logo_url,
        'telephone'   => $phone,
        'address'     => array_filter([
            '@type'           => 'PostalAddress',
            'streetAddress'   => $street,
            'addressLocality' => $city,
            'addressRegion'   => $state,
            'postalCode'      => $zip,
            'addressCountry'  => 'US',
        ]),
        'geo'         => [
            '@type'     => 'GeoCoordinates',
            // Approximate centre of Cincinnati — update with precise coords if needed
            'latitude'  => 39.1031,
            'longitude' => -84.5120,
        ],
        'areaServed'  => [
            [
                '@type'             => 'City',
                'name'              => 'Cincinnati',
                'containedInPlace'  => ['@type' => 'State', 'name' => 'Ohio'],
            ],
            [
                '@type'             => 'City',
                'name'              => 'Columbus',
                'containedInPlace'  => ['@type' => 'State', 'name' => 'Ohio'],
            ],
            [
                '@type'             => 'City',
                'name'              => 'Indianapolis',
                'containedInPlace'  => ['@type' => 'State', 'name' => 'Indiana'],
            ],
        ],
        'serviceType' => [
            'Commercial Roofing',
            'Residential Roofing',
            'Metal Roofing',
            'Flat Roofing',
            'Roof Repair',
            'Roof Replacement',
            'Roof Inspection',
        ],
        'priceRange'  => '$$',
    ];

    if ($email) {
        $schema['email'] = $email;
    }

    if ($socials) {
        $schema['sameAs'] = $socials;
    }

    // AggregateRating from Trustindex Google Reviews plugin
    // Option key: trustindex-google-page-details → ['rating_score' => float, 'rating_number' => int]
    $ti_data = get_option('trustindex-google-page-details');
    if (
        is_array($ti_data)
        && ! empty($ti_data['rating_score'])
        && ! empty($ti_data['rating_number'])
    ) {
        $schema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => round((float) $ti_data['rating_score'], 1),
            'reviewCount' => (int) $ti_data['rating_number'],
            'bestRating'  => 5,
            'worstRating' => 1,
        ];
    }

    echo "\n<script type=\"application/ld+json\">\n"
        . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        . "\n</script>\n";

}, 5);


