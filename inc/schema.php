<?php
/**
 * Structured data — schema.org JSON-LD
 *
 * Homepage:       full @graph — WebSite + Organization + RoofingContractor + WebPage + Reviews.
 * Other pages:    lean RoofingContractor/LocalBusiness that references /#organization by @id.
 * Service pages:  Service schema keyed by page slug.
 * Any page:       FAQPage schema when the `faq_items` ACF repeater is populated.
 *
 * @package Globeiron
 */

declare(strict_types=1);

// ─── Shared helpers ───────────────────────────────────────────────────────────

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

/**
 * All service areas — shared across homepage, inner pages, and service schema.
 */
function globeiron_schema_area_served(): array {
    return [
        ['@type' => 'City', 'name' => 'Cincinnati',   'containedInPlace' => ['@type' => 'State', 'name' => 'Ohio']],
        ['@type' => 'City', 'name' => 'Columbus',     'containedInPlace' => ['@type' => 'State', 'name' => 'Ohio']],
        ['@type' => 'City', 'name' => 'Indianapolis', 'containedInPlace' => ['@type' => 'State', 'name' => 'Indiana']],
        ['@type' => 'City', 'name' => 'Dayton',       'containedInPlace' => ['@type' => 'State', 'name' => 'Ohio']],
        ['@type' => 'City', 'name' => 'Lexington',    'containedInPlace' => ['@type' => 'State', 'name' => 'Kentucky']],
        ['@type' => 'City', 'name' => 'Somerset',     'containedInPlace' => ['@type' => 'State', 'name' => 'Kentucky']],
        ['@type' => 'City', 'name' => 'Pikeville',    'containedInPlace' => ['@type' => 'State', 'name' => 'Kentucky']],
    ];
}

/**
 * Individual Review nodes from the globeiron_review CPT.
 */
function globeiron_schema_reviews(int $limit = 6): array {
    $posts = get_posts([
        'post_type'      => 'globeiron_review',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);

    if (empty($posts)) {
        return [];
    }

    $home_url = trailingslashit(home_url('/'));
    $reviews  = [];

    foreach ($posts as $post) {
        $body   = wp_strip_all_tags(get_the_content(null, false, $post));
        $name   = get_the_title($post);
        $rating = (int) (
            get_field('review_rating', $post->ID)
            ?: get_post_meta($post->ID, '_review_rating', true)
            ?: 5
        );
        $date = (string) (
            get_field('review_date', $post->ID)
            ?: get_post_meta($post->ID, '_review_date', true)
            ?: get_the_date('Y-m-d', $post)
        );

        if (! $body || ! $name) {
            continue;
        }

        $reviews[] = [
            '@type'         => 'Review',
            'author'        => ['@type' => 'Person', 'name' => $name],
            'datePublished' => $date,
            'reviewBody'    => $body,
            'reviewRating'  => [
                '@type'       => 'Rating',
                'ratingValue' => $rating,
                'bestRating'  => 5,
                'worstRating' => 1,
            ],
            'itemReviewed'  => ['@id' => $home_url . '#business'],
        ];
    }

    return $reviews;
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
    $reviews  = globeiron_schema_reviews();
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
        'areaServed'         => globeiron_schema_area_served(),
        'serviceType' => [
            'Commercial Roofing',
            'Residential Roofing',
            'Metal Roofing',
            'Flat Roofing',
            'Roof Repair',
            'Roof Replacement',
            'Roof Inspection',
            'Historic Restoration Roofing',
        ],
        'openingHoursSpecification' => $d['opening_hours'] ?: null,
        'priceRange'         => '$$',
        'currenciesAccepted' => 'USD',
        'paymentAccepted'    => 'Cash, Check, Credit Card',
        'sameAs'             => $d['socials'] ?: null,
        'aggregateRating'    => $d['aggregate'],
        'review'             => $reviews ?: null,
    ]);

    // WebPage — identifies the homepage and connects all entities
    $webpage = array_filter([
        '@type'       => 'WebPage',
        '@id'         => $home_url . '#webpage',
        'url'         => $home_url,
        'name'        => $page_name,
        'description' => $d['tagline'] ?: null,
        'isPartOf'    => ['@id' => $home_url . '#website'],
        'about'       => ['@id' => $home_url . '#business'],
        'speakable'   => [
            '@type'       => 'SpeakableSpecification',
            'cssSelector' => ['.hero-home__heading', '.hero-home__content-body'],
        ],
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


// ─── 2. All other pages — lean RoofingContractor + LocalBusiness ───────────────

add_action('wp_head', function (): void {

    if (is_front_page()) {
        return;
    }

    $d        = globeiron_schema_data();
    $home_url = trailingslashit(home_url('/'));

    $schema = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => ['RoofingContractor', 'LocalBusiness'],
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
        'areaServed'  => globeiron_schema_area_served(),
        'serviceType' => [
            'Commercial Roofing',
            'Residential Roofing',
            'Metal Roofing',
            'Flat Roofing',
            'Roof Repair',
            'Roof Replacement',
            'Roof Inspection',
            'Historic Restoration Roofing',
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

    $author_post = get_field('post_author_override', $post->ID);
    $author_node = null;
    if ($author_post instanceof WP_Post) {
        $headshot    = get_field('headshot', $author_post->ID);
        $author_img  = is_array($headshot) ? ($headshot['url'] ?? '') : '';
        $author_bio  = wp_strip_all_tags((string) (get_field('bio', $author_post->ID) ?: ''));
        $linkedin    = (string) (get_field('linkedin_url', $author_post->ID) ?: '');
        $same_as     = array_values(array_filter([$linkedin]));

        $author_node = array_filter([
            '@type'       => 'Person',
            '@id'         => $home_url . '#person-' . $author_post->ID,
            'name'        => get_the_title($author_post),
            'jobTitle'    => (string) (get_field('role', $author_post->ID) ?: '') ?: null,
            'description' => $author_bio ?: null,
            'image'       => $author_img ? ['@type' => 'ImageObject', 'url' => $author_img] : null,
            'worksFor'    => ['@id' => $home_url . '#organization'],
            'sameAs'      => $same_as ?: null,
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
        'speakable'     => [
            '@type'       => 'SpeakableSpecification',
            'cssSelector' => ['.single-post-hero__title', '.single-post-body .entry > p'],
        ],
    ]);

    echo "\n<script type=\"application/ld+json\">\n"
        . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        . "\n</script>\n";

}, 5);


// ─── 4. Service pages — Service schema ────────────────────────────────────────

add_action('wp_head', function (): void {

    if (! is_page()) {
        return;
    }

    $slug = get_post_field('post_name', get_queried_object_id());

    $service_map = [
        'commercial' => [
            'name' => 'Commercial Roofing',
            'desc' => 'Full-service commercial roofing including flat roofing, TPO, EPDM, modified bitumen, and metal systems for businesses, institutions, and industrial facilities.',
        ],
        'residential' => [
            'name' => 'Residential Roofing',
            'desc' => 'Expert residential roofing installation, repair, and full replacement using premium materials and time-tested craftsmanship.',
        ],
        'historic-restoration' => [
            'name' => 'Historic Restoration Roofing',
            'desc' => 'Specialized historic and architectural roofing restoration using period-appropriate materials — slate, copper, clay tile, and standing-seam metal — with the precision the work demands.',
        ],
        'metal-roofing' => [
            'name' => 'Metal Roofing',
            'desc' => 'Standing-seam, corrugated, and architectural metal roofing for commercial and residential properties.',
        ],
        'roof-repair' => [
            'name' => 'Roof Repair',
            'desc' => 'Rapid-response roof repair for leaks, storm damage, flashing failures, and membrane defects across all roofing types.',
        ],
        'roof-replacement' => [
            'name' => 'Roof Replacement',
            'desc' => 'Complete roof replacement with full tear-off, deck inspection, and quality material installation backed by manufacturer warranties.',
        ],
        'roof-inspection' => [
            'name' => 'Roof Inspection',
            'desc' => 'Detailed roof inspections with written reports for property purchases, insurance claims, and preventive maintenance.',
        ],
    ];

    if (! isset($service_map[$slug])) {
        return;
    }

    $svc      = $service_map[$slug];
    $home_url = trailingslashit(home_url('/'));
    $page_url = trailingslashit(home_url('/' . $slug . '/'));

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        '@id'         => $page_url . '#service',
        'name'        => $svc['name'],
        'description' => $svc['desc'],
        'url'         => $page_url,
        'provider'    => ['@id' => $home_url . '#business'],
        'areaServed'  => globeiron_schema_area_served(),
        'serviceType' => $svc['name'],
        'serviceOutput' => 'Roofing installation, repair, or restoration',
    ];

    echo "\n<script type=\"application/ld+json\">\n"
        . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        . "\n</script>\n";

}, 5);


