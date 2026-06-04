<?php
/**
 * Structured data — schema.org JSON-LD
 *
 * Homepage:    full @graph — WebSite + Organization + RoofingContractor + WebPage.
 * Other pages: lean RoofingContractor that references /#organization by @id pointer.
 *
 * @package Globeiron
 */

declare(strict_types=1);

// ─── Shared data builder ──────────────────────────────────────────────────────

function globeiron_schema_data(): array {
    $phone    = (string) (get_field('site_phone',   'option') ?: '513-371-1841');
    $email    = (string) (get_field('site_email',   'option') ?: '');
    $raw_addr = (string) (get_field('site_address', 'option') ?: "6161 Wiehe Road\nCincinnati, OH 45237");
    $tagline  = (string) (get_bloginfo('description') ?: get_field('footer_serving_tagline', 'option') ?: '');

    $lines  = array_values(array_filter(array_map('trim', explode("\n", $raw_addr))));
    $street = $lines[0] ?? '';
    $city   = 'Cincinnati';
    $state  = 'OH';
    $zip    = '';

    if (isset($lines[1]) && preg_match('/^(.+),\s*([A-Z]{2})\s+(\d{5})$/', $lines[1], $m)) {
        $city  = $m[1];
        $state = $m[2];
        $zip   = $m[3];
    }

    $logo_id  = get_theme_mod('custom_logo');
    $logo_url = $logo_id ? (string) wp_get_attachment_image_url($logo_id, 'full') : '';
    if (! $logo_url) {
        $logo_url = get_template_directory_uri() . '/img/globe-iron-roofing-logo.svg';
    }

    $socials = array_values(array_filter([
        (string) (get_field('social_facebook',  'option') ?: ''),
        (string) (get_field('social_instagram', 'option') ?: ''),
        (string) (get_field('social_linkedin',  'option') ?: ''),
        (string) (get_field('social_youtube',   'option') ?: ''),
        (string) (get_field('social_tiktok',    'option') ?: ''),
    ]));

    $hours_rows    = (array) (get_field('business_hours', 'option') ?: []);
    $opening_hours = [];
    foreach ($hours_rows as $row) {
        $days   = (array)  ($row['hours_days']  ?? []);
        $opens  = (string) ($row['hours_open']  ?? '');
        $closes = (string) ($row['hours_close'] ?? '');
        if ($days && $opens && $closes) {
            $opening_hours[] = [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => $days,
                'opens'     => $opens,
                'closes'    => $closes,
            ];
        }
    }

    $ti_data   = get_option('trustindex-google-page-details');
    $aggregate = null;
    if (
        is_array($ti_data)
        && ! empty($ti_data['rating_score'])
        && ! empty($ti_data['rating_number'])
    ) {
        $aggregate = [
            '@type'       => 'AggregateRating',
            'ratingValue' => round((float) $ti_data['rating_score'], 1),
            'reviewCount' => (int) $ti_data['rating_number'],
            'bestRating'  => 5,
            'worstRating' => 1,
        ];
    }

    $address = array_filter([
        '@type'           => 'PostalAddress',
        'streetAddress'   => $street,
        'addressLocality' => $city,
        'addressRegion'   => $state,
        'postalCode'      => $zip,
        'addressCountry'  => 'US',
    ]);

    $maps_query = rawurlencode(implode(' ', array_filter([$street, $city, $state, $zip])));
    $maps_url   = 'https://maps.google.com/?q=' . $maps_query;

    return compact(
        'phone', 'email', 'tagline',
        'logo_url', 'socials', 'aggregate',
        'address', 'maps_url', 'opening_hours'
    );
}

// ─── 1. Homepage — full @graph ────────────────────────────────────────────────

add_action('wp_head', function (): void {

    if (! is_front_page()) {
        return;
    }

    $d         = globeiron_schema_data();
    $site_name = get_bloginfo('name');
    $home_url  = trailingslashit(home_url('/'));
    $page_name = $site_name . ($d['tagline'] ? ' — ' . $d['tagline'] : '');

    $logo_object = [
        '@type'      => 'ImageObject',
        '@id'        => $home_url . '#logo',
        'url'        => $d['logo_url'],
        'contentUrl' => $d['logo_url'],
        'caption'    => $site_name,
    ];

    // WebSite — enables Sitelinks Search Box in Google results
    $website = [
        '@type'           => 'WebSite',
        '@id'             => $home_url . '#website',
        'name'            => $site_name,
        'url'             => $home_url,
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => $home_url . '?s={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];

    // Organization — feeds Knowledge Panel; cross-referenced by LocalBusiness below
    $organization = array_filter([
        '@type'       => 'Organization',
        '@id'         => $home_url . '#organization',
        'name'        => $site_name,
        'url'         => $home_url,
        'logo'        => $logo_object,
        'image'       => ['@id' => $home_url . '#logo'],
        'description' => $d['tagline'] ?: null,
        'telephone'   => $d['phone'],
        'email'       => $d['email'] ?: null,
        'address'     => $d['address'],
        'sameAs'      => $d['socials'] ?: null,
    ]);

    // RoofingContractor / LocalBusiness — feeds Local Pack + Maps
    $business = array_filter([
        '@type'       => ['RoofingContractor', 'LocalBusiness'],
        '@id'         => $home_url . '#business',
        'name'        => $site_name,
        'url'         => $home_url,
        'logo'        => ['@id' => $home_url . '#logo'],
        'image'       => ['@id' => $home_url . '#logo'],
        'telephone'   => $d['phone'],
        'email'       => $d['email'] ?: null,
        'address'     => $d['address'],
        'geo'         => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => 39.1031,
            'longitude' => -84.5120,
        ],
        'hasMap'             => $d['maps_url'],
        'parentOrganization' => ['@id' => $home_url . '#organization'],
        'areaServed'         => [
            ['@type' => 'City', 'name' => 'Cincinnati',  'containedInPlace' => ['@type' => 'State', 'name' => 'Ohio']],
            ['@type' => 'City', 'name' => 'Columbus',    'containedInPlace' => ['@type' => 'State', 'name' => 'Ohio']],
            ['@type' => 'City', 'name' => 'Indianapolis','containedInPlace' => ['@type' => 'State', 'name' => 'Indiana']],
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
        'openingHoursSpecification' => $d['opening_hours'] ?: null,
        'priceRange'         => '$$',
        'currenciesAccepted' => 'USD',
        'paymentAccepted'    => 'Cash, Check, Credit Card',
        'sameAs'             => $d['socials'] ?: null,
        'aggregateRating'    => $d['aggregate'],
    ]);

    // WebPage — identifies the homepage and connects all entities
    $webpage = array_filter([
        '@type'      => 'WebPage',
        '@id'        => $home_url . '#webpage',
        'url'        => $home_url,
        'name'       => $page_name,
        'description' => $d['tagline'] ?: null,
        'isPartOf'   => ['@id' => $home_url . '#website'],
        'about'      => ['@id' => $home_url . '#business'],
        'breadcrumb' => [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [[
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => 'Home',
                'item'     => $home_url,
            ]],
        ],
    ]);

    $graph = [
        '@context' => 'https://schema.org',
        '@graph'   => [$website, $organization, $business, $webpage],
    ];

    echo "\n<script type=\"application/ld+json\">\n"
        . wp_json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        . "\n</script>\n";

}, 5);


// ─── 2. All other pages — lean RoofingContractor ──────────────────────────────

add_action('wp_head', function (): void {

    if (is_front_page()) {
        return; // full @graph covers this
    }

    $d        = globeiron_schema_data();
    $home_url = trailingslashit(home_url('/'));

    $schema = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => 'RoofingContractor',
        '@id'         => $home_url . '#business',
        'name'        => get_bloginfo('name'),
        'url'         => $home_url,
        'logo'        => $d['logo_url'],
        'telephone'   => $d['phone'],
        'email'       => $d['email'] ?: null,
        'address'     => $d['address'],
        'geo'         => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => 39.1031,
            'longitude' => -84.5120,
        ],
        'parentOrganization' => ['@id' => $home_url . '#organization'],
        'areaServed'         => [
            ['@type' => 'City', 'name' => 'Cincinnati',   'containedInPlace' => ['@type' => 'State', 'name' => 'Ohio']],
            ['@type' => 'City', 'name' => 'Columbus',     'containedInPlace' => ['@type' => 'State', 'name' => 'Ohio']],
            ['@type' => 'City', 'name' => 'Indianapolis', 'containedInPlace' => ['@type' => 'State', 'name' => 'Indiana']],
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
        'priceRange'      => '$$',
        'sameAs'          => $d['socials'] ?: null,
        'aggregateRating' => $d['aggregate'],
    ]);

    echo "\n<script type=\"application/ld+json\">\n"
        . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        . "\n</script>\n";

}, 5);


// ─── 3. Single blog posts — BlogPosting + Person author ───────────────────────

add_action('wp_head', function (): void {

    if (! is_singular('post')) {
        return;
    }

    $post = get_queried_object();
    if (! $post instanceof WP_Post) {
        return;
    }

    $home_url  = trailingslashit(home_url('/'));
    $permalink = (string) get_permalink($post);

    $featured_id = get_post_thumbnail_id($post->ID);
    $image_url   = $featured_id ? (string) wp_get_attachment_image_url($featured_id, 'large') : '';

    // Author Person — linked team_member post
    $author_post = get_field('post_author_override', $post->ID);
    $author_node = null;
    if ($author_post instanceof WP_Post) {
        $headshot    = get_field('headshot', $author_post->ID);
        $author_img  = is_array($headshot) ? ($headshot['url'] ?? '') : '';
        $author_bio  = wp_strip_all_tags((string) (get_field('bio', $author_post->ID) ?: ''));
        $author_node = array_filter([
            '@type'       => 'Person',
            '@id'         => $home_url . '#person-' . $author_post->ID,
            'name'        => get_the_title($author_post),
            'jobTitle'    => (string) (get_field('role', $author_post->ID) ?: '') ?: null,
            'description' => $author_bio ?: null,
            'image'       => $author_img ? ['@type' => 'ImageObject', 'url' => $author_img] : null,
            'worksFor'    => ['@id' => $home_url . '#organization'],
        ]);
    }

    $schema = array_filter([
        '@context'      => 'https://schema.org',
        '@type'         => 'BlogPosting',
        '@id'           => $permalink . '#article',
        'headline'      => get_the_title($post),
        'description'   => wp_strip_all_tags(get_the_excerpt($post)) ?: null,
        'url'           => $permalink,
        'datePublished' => get_the_date('c', $post),
        'dateModified'  => get_the_modified_date('c', $post),
        'image'         => $image_url ? ['@type' => 'ImageObject', 'url' => $image_url] : null,
        'author'        => $author_node,
        'publisher'     => ['@id' => $home_url . '#organization'],
        'isPartOf'      => ['@id' => $home_url . '#website'],
        'inLanguage'    => get_bloginfo('language'),
    ]);

    echo "\n<script type=\"application/ld+json\">\n"
        . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        . "\n</script>\n";

}, 5);
