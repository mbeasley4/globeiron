<?php
/**
 * Meta boxes for custom post types.
 *
 * @package Globeiron
 */

declare(strict_types=1);

// ─────────────────────────────────────────────────────────────────────────────
//  Project meta box
// ─────────────────────────────────────────────────────────────────────────────

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'globeiron_project_details',
        __('Project Details', 'globeiron'),
        'globeiron_project_meta_box_html',
        'globeiron_project',
        'normal',
        'high'
    );
});

function globeiron_project_meta_box_html(WP_Post $post): void {
    wp_nonce_field('globeiron_project_save', 'globeiron_project_nonce');

    $fields = [
        '_project_location'       => get_post_meta($post->ID, '_project_location',       true),
        '_project_year'           => get_post_meta($post->ID, '_project_year',           true),
        '_project_sq_footage'     => get_post_meta($post->ID, '_project_sq_footage',     true),
        '_project_services'       => get_post_meta($post->ID, '_project_services',       true),
        '_project_materials'      => get_post_meta($post->ID, '_project_materials',      true),
        '_project_client_name'    => get_post_meta($post->ID, '_project_client_name',    true),
        '_project_before_img_id'  => (int) get_post_meta($post->ID, '_project_before_img_id', true),
        '_project_after_img_id'   => (int) get_post_meta($post->ID, '_project_after_img_id',  true),
    ];

    $before_src = $fields['_project_before_img_id']
        ? wp_get_attachment_image_src($fields['_project_before_img_id'], 'thumbnail')
        : false;
    $after_src  = $fields['_project_after_img_id']
        ? wp_get_attachment_image_src($fields['_project_after_img_id'],  'thumbnail')
        : false;

    $service_options = [
        'Shingle Replacement'       => __('Shingle Replacement', 'globeiron'),
        'Flat / Single-Ply Roofing' => __('Flat / Single-Ply Roofing', 'globeiron'),
        'Asphalt BUR'               => __('Asphalt BUR (Built-Up)', 'globeiron'),
        'Modified Bitumen'          => __('Modified Bitumen', 'globeiron'),
        'Metal Roofing'             => __('Metal Roofing', 'globeiron'),
        'Historic Restoration'      => __('Historic Restoration', 'globeiron'),
        'Gutters'                   => __('Gutters', 'globeiron'),
        'Roof Maintenance'          => __('Roof Maintenance', 'globeiron'),
        'Roof Inspection'           => __('Roof Inspection', 'globeiron'),
        'Emergency Repair'          => __('Emergency Repair', 'globeiron'),
    ];

    $selected_services = $fields['_project_services']
        ? array_map('trim', explode(',', $fields['_project_services']))
        : [];

    ?>
    <style>
        .globeiron-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .globeiron-meta .full { grid-column: 1 / -1; }
        .globeiron-meta label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: #555; }
        .globeiron-meta input[type="text"],
        .globeiron-meta input[type="number"],
        .globeiron-meta select { width: 100%; }
        .globeiron-meta .checkboxes { display: flex; flex-wrap: wrap; gap: 6px 16px; }
        .globeiron-meta .checkboxes label { font-weight: 400; text-transform: none; letter-spacing: 0; color: #333; display: flex; align-items: center; gap: 5px; }
        .globeiron-image-picker { display: flex; gap: 16px; }
        .globeiron-image-picker .picker { flex: 1; }
        .globeiron-image-picker img { max-width: 100%; border-radius: 4px; margin-bottom: 6px; display: block; }
        .globeiron-image-picker .buttons { display: flex; gap: 6px; flex-wrap: wrap; }
    </style>

    <div class="globeiron-meta">

        <!-- Location -->
        <div>
            <label for="project_location"><?php esc_html_e('Location (City, State)', 'globeiron'); ?></label>
            <input type="text" id="project_location" name="_project_location"
                value="<?php echo esc_attr($fields['_project_location']); ?>"
                placeholder="e.g. Pittsburgh, PA">
        </div>

        <!-- Year -->
        <div>
            <label for="project_year"><?php esc_html_e('Year Completed', 'globeiron'); ?></label>
            <input type="number" id="project_year" name="_project_year"
                value="<?php echo esc_attr($fields['_project_year']); ?>"
                min="1950" max="2099" placeholder="<?php echo esc_attr((string) date('Y')); ?>">
        </div>

        <!-- Square footage -->
        <div>
            <label for="project_sq_footage"><?php esc_html_e('Square Footage', 'globeiron'); ?></label>
            <input type="number" id="project_sq_footage" name="_project_sq_footage"
                value="<?php echo esc_attr($fields['_project_sq_footage']); ?>"
                min="0" placeholder="e.g. 2400">
        </div>

        <!-- Client name -->
        <div>
            <label for="project_client_name"><?php esc_html_e('Client Name (optional)', 'globeiron'); ?></label>
            <input type="text" id="project_client_name" name="_project_client_name"
                value="<?php echo esc_attr($fields['_project_client_name']); ?>"
                placeholder="e.g. Smith Family">
        </div>

        <!-- Materials -->
        <div class="full">
            <label for="project_materials"><?php esc_html_e('Materials Used', 'globeiron'); ?></label>
            <input type="text" id="project_materials" name="_project_materials"
                value="<?php echo esc_attr($fields['_project_materials']); ?>"
                placeholder="e.g. GAF Timberline HDZ Shingles, Ice & Water Shield">
        </div>

        <!-- Services (checkboxes) -->
        <div class="full">
            <label><?php esc_html_e('Services Performed', 'globeiron'); ?></label>
            <div class="checkboxes">
                <?php foreach ($service_options as $value => $label) : ?>
                    <label>
                        <input type="checkbox" name="_project_services[]"
                            value="<?php echo esc_attr($value); ?>"
                            <?php checked(in_array($value, $selected_services, true)); ?>>
                        <?php echo esc_html($label); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Before / After images -->
        <div class="full">
            <label><?php esc_html_e('Before / After Images', 'globeiron'); ?></label>
            <div class="globeiron-image-picker">

                <!-- Before -->
                <div class="picker">
                    <p style="margin:0 0 6px;font-size:12px;color:#555;"><?php esc_html_e('Before', 'globeiron'); ?></p>
                    <?php if ($before_src) : ?>
                        <img id="project_before_preview" src="<?php echo esc_url($before_src[0]); ?>">
                    <?php else : ?>
                        <img id="project_before_preview" src="" style="display:none;">
                    <?php endif; ?>
                    <input type="hidden" id="project_before_img_id" name="_project_before_img_id"
                        value="<?php echo esc_attr((string) $fields['_project_before_img_id']); ?>">
                    <div class="buttons">
                        <button type="button" class="button globeiron-pick-image"
                            data-target="project_before_img_id"
                            data-preview="project_before_preview">
                            <?php echo $before_src
                                ? esc_html__('Replace', 'globeiron')
                                : esc_html__('Choose Image', 'globeiron'); ?>
                        </button>
                        <?php if ($before_src) : ?>
                            <button type="button" class="button globeiron-remove-image"
                                data-target="project_before_img_id"
                                data-preview="project_before_preview">
                                <?php esc_html_e('Remove', 'globeiron'); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- After -->
                <div class="picker">
                    <p style="margin:0 0 6px;font-size:12px;color:#555;"><?php esc_html_e('After', 'globeiron'); ?></p>
                    <?php if ($after_src) : ?>
                        <img id="project_after_preview" src="<?php echo esc_url($after_src[0]); ?>">
                    <?php else : ?>
                        <img id="project_after_preview" src="" style="display:none;">
                    <?php endif; ?>
                    <input type="hidden" id="project_after_img_id" name="_project_after_img_id"
                        value="<?php echo esc_attr((string) $fields['_project_after_img_id']); ?>">
                    <div class="buttons">
                        <button type="button" class="button globeiron-pick-image"
                            data-target="project_after_img_id"
                            data-preview="project_after_preview">
                            <?php echo $after_src
                                ? esc_html__('Replace', 'globeiron')
                                : esc_html__('Choose Image', 'globeiron'); ?>
                        </button>
                        <?php if ($after_src) : ?>
                            <button type="button" class="button globeiron-remove-image"
                                data-target="project_after_img_id"
                                data-preview="project_after_preview">
                                <?php esc_html_e('Remove', 'globeiron'); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div><!-- /.full -->

    </div><!-- /.globeiron-meta -->

    <?php globeiron_enqueue_media_picker_script(); ?>
    <?php
}

// Review fields are managed by ACF — see acf-json/group_globeiron_review.json

// ─────────────────────────────────────────────────────────────────────────────
//  Testimonial meta box
// ─────────────────────────────────────────────────────────────────────────────

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'globeiron_testimonial_details',
        __('Testimonial Details', 'globeiron'),
        'globeiron_testimonial_meta_box_html',
        'globeiron_testimonial',
        'normal',
        'high'
    );
});

function globeiron_testimonial_meta_box_html(WP_Post $post): void {
    wp_nonce_field('globeiron_testimonial_save', 'globeiron_testimonial_nonce');

    $fields = [
        '_testimonial_quote'        => get_post_meta($post->ID, '_testimonial_quote',        true),
        '_testimonial_location'     => get_post_meta($post->ID, '_testimonial_location',     true),
        '_testimonial_rating'       => get_post_meta($post->ID, '_testimonial_rating',       true) ?: '5',
        '_testimonial_service_type' => get_post_meta($post->ID, '_testimonial_service_type', true),
        '_testimonial_date'         => get_post_meta($post->ID, '_testimonial_date',         true),
        '_testimonial_photo_id'     => (int) get_post_meta($post->ID, '_testimonial_photo_id', true),
        '_testimonial_source'       => get_post_meta($post->ID, '_testimonial_source',       true) ?: 'direct',
    ];

    $photo_src = $fields['_testimonial_photo_id']
        ? wp_get_attachment_image_src($fields['_testimonial_photo_id'], 'thumbnail')
        : false;

    $service_options = [
        ''                          => __('— Select service —', 'globeiron'),
        'Residential Roofing'       => __('Residential Roofing', 'globeiron'),
        'Commercial Roofing'        => __('Commercial Roofing', 'globeiron'),
        'Metal Roofing'             => __('Metal Roofing', 'globeiron'),
        'Historic Restoration'      => __('Historic Restoration', 'globeiron'),
        'Flat / Single-Ply Roofing' => __('Flat / Single-Ply Roofing', 'globeiron'),
        'Gutters'                   => __('Gutters', 'globeiron'),
        'Roof Inspection'           => __('Roof Inspection', 'globeiron'),
        'Emergency Repair'          => __('Emergency Repair', 'globeiron'),
    ];

    $source_options = [
        'direct'    => __('Direct / Word of mouth', 'globeiron'),
        'google'    => __('Google', 'globeiron'),
        'facebook'  => __('Facebook', 'globeiron'),
        'yelp'      => __('Yelp', 'globeiron'),
        'bbb'       => __('Better Business Bureau', 'globeiron'),
        'houzz'     => __('Houzz', 'globeiron'),
        'other'     => __('Other', 'globeiron'),
    ];
    ?>

    <p style="color:#555;margin-bottom:12px;font-size:13px;">
        <?php esc_html_e('The post title is the client\'s name. Fill in the details below.', 'globeiron'); ?>
    </p>

    <div class="globeiron-meta">

        <!-- Full quote -->
        <div class="full">
            <label for="testimonial_quote"><?php esc_html_e('Full Testimonial / Quote', 'globeiron'); ?></label>
            <textarea id="testimonial_quote" name="_testimonial_quote"
                rows="5" style="width:100%;"
                placeholder="<?php esc_attr_e('Enter the full testimonial text…', 'globeiron'); ?>"
            ><?php echo esc_textarea($fields['_testimonial_quote']); ?></textarea>
        </div>

        <!-- Location -->
        <div>
            <label for="testimonial_location"><?php esc_html_e('Client Location (City, State)', 'globeiron'); ?></label>
            <input type="text" id="testimonial_location" name="_testimonial_location"
                value="<?php echo esc_attr($fields['_testimonial_location']); ?>"
                placeholder="e.g. Pittsburgh, PA">
        </div>

        <!-- Date of service -->
        <div>
            <label for="testimonial_date"><?php esc_html_e('Date of Service', 'globeiron'); ?></label>
            <input type="month" id="testimonial_date" name="_testimonial_date"
                value="<?php echo esc_attr($fields['_testimonial_date']); ?>">
        </div>

        <!-- Rating -->
        <div>
            <label for="testimonial_rating"><?php esc_html_e('Star Rating', 'globeiron'); ?></label>
            <select id="testimonial_rating" name="_testimonial_rating">
                <?php for ($i = 5; $i >= 1; $i--) : ?>
                    <option value="<?php echo $i; ?>"
                        <?php selected($fields['_testimonial_rating'], (string) $i); ?>>
                        <?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i) . " ({$i})"; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Service type -->
        <div>
            <label for="testimonial_service_type"><?php esc_html_e('Service Type', 'globeiron'); ?></label>
            <select id="testimonial_service_type" name="_testimonial_service_type">
                <?php foreach ($service_options as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>"
                        <?php selected($fields['_testimonial_service_type'], $value); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Source -->
        <div>
            <label for="testimonial_source"><?php esc_html_e('Review Source', 'globeiron'); ?></label>
            <select id="testimonial_source" name="_testimonial_source">
                <?php foreach ($source_options as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>"
                        <?php selected($fields['_testimonial_source'], $value); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Client photo -->
        <div class="full">
            <label><?php esc_html_e('Client Photo (optional)', 'globeiron'); ?></label>
            <div style="display:flex;align-items:flex-start;gap:12px;margin-top:4px;">
                <?php if ($photo_src) : ?>
                    <img id="testimonial_photo_preview"
                        src="<?php echo esc_url($photo_src[0]); ?>"
                        style="width:64px;height:64px;border-radius:50%;object-fit:cover;">
                <?php else : ?>
                    <img id="testimonial_photo_preview" src=""
                        style="width:64px;height:64px;border-radius:50%;object-fit:cover;display:none;">
                <?php endif; ?>
                <div>
                    <input type="hidden" id="testimonial_photo_id" name="_testimonial_photo_id"
                        value="<?php echo esc_attr((string) $fields['_testimonial_photo_id']); ?>">
                    <button type="button" class="button globeiron-pick-image"
                        data-target="testimonial_photo_id"
                        data-preview="testimonial_photo_preview">
                        <?php echo $photo_src
                            ? esc_html__('Replace Photo', 'globeiron')
                            : esc_html__('Upload Photo', 'globeiron'); ?>
                    </button>
                    <?php if ($photo_src) : ?>
                        <button type="button" class="button globeiron-remove-image"
                            data-target="testimonial_photo_id"
                            data-preview="testimonial_photo_preview"
                            style="margin-left:6px;">
                            <?php esc_html_e('Remove', 'globeiron'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- /.globeiron-meta -->

    <?php globeiron_enqueue_media_picker_script(); ?>
    <?php
}

// ─────────────────────────────────────────────────────────────────────────────
//  Partner meta box
// ─────────────────────────────────────────────────────────────────────────────

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'globeiron_partner_details',
        __('Partner Details', 'globeiron'),
        'globeiron_partner_meta_box_html',
        'partner',
        'normal',
        'high'
    );
});

function globeiron_partner_meta_box_html(WP_Post $post): void {
    wp_nonce_field('globeiron_partner_save', 'globeiron_partner_nonce');

    $url = get_post_meta($post->ID, '_partner_url', true);
    ?>
    <style>
        .globeiron-partner-meta {
            padding: 4px 0;
        }
        .globeiron-partner-meta label {
            display: block;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #555;
            margin-bottom: 6px;
        }
        .globeiron-partner-meta .field-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .globeiron-partner-meta input[type="url"] {
            flex: 1;
        }
        .globeiron-partner-meta .hint {
            margin-top: 6px;
            font-size: 12px;
            color: #888;
            font-style: italic;
        }
        .globeiron-partner-meta .link-indicator {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #2271b1;
            padding: 4px 8px;
            border: 1px solid #c3d9f0;
            border-radius: 4px;
            background: #f0f6fc;
            white-space: nowrap;
        }
        .globeiron-partner-meta .link-indicator svg {
            flex-shrink: 0;
        }
        #partner-url-status { margin-top: 8px; }
    </style>

    <div class="globeiron-partner-meta">

        <p style="color:#555;margin-top:0;font-size:13px;">
            <?php esc_html_e('The post title is the partner\'s name. Set the featured image (Partner Logo) in the right-hand panel.', 'globeiron'); ?>
        </p>

        <!-- Website URL -->
        <div style="margin-bottom: 12px;">
            <label for="partner_url"><?php esc_html_e('Website URL', 'globeiron'); ?></label>
            <div class="field-row">
                <input
                    type="url"
                    id="partner_url"
                    name="_partner_url"
                    value="<?php echo esc_attr($url); ?>"
                    placeholder="https://example.com"
                    class="regular-text"
                >
            </div>
            <p class="hint">
                <?php esc_html_e('Optional. If provided, the logo will link to this URL in a new window. A link icon will be shown on the card.', 'globeiron'); ?>
            </p>
            <div id="partner-url-status">
                <?php if ($url) : ?>
                    <span class="link-indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <?php esc_html_e('Opens in new window', 'globeiron'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script>
    (function() {
        var input  = document.getElementById('partner_url');
        var status = document.getElementById('partner-url-status');
        if (!input || !status) return;

        input.addEventListener('input', function() {
            var val = input.value.trim();
            if (val) {
                status.innerHTML = '<span class="link-indicator">'
                    + '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">'
                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>'
                    + '</svg>'
                    + '<?php echo esc_js(__('Opens in new window', 'globeiron')); ?>'
                    + '</span>';
            } else {
                status.innerHTML = '';
            }
        });
    }());
    </script>
    <?php
}

// ─────────────────────────────────────────────────────────────────────────────
//  Save: Partner
// ─────────────────────────────────────────────────────────────────────────────

add_action('save_post_partner', function (int $post_id): void {
    if (
        ! isset($_POST['globeiron_partner_nonce']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['globeiron_partner_nonce'])), 'globeiron_partner_save') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        ! current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $url = isset($_POST['_partner_url']) ? esc_url_raw(wp_unslash($_POST['_partner_url'])) : '';

    if ($url) {
        update_post_meta($post_id, '_partner_url', $url);
    } else {
        delete_post_meta($post_id, '_partner_url');
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  Register: Certification details meta box
// ─────────────────────────────────────────────────────────────────────────────

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'globeiron_certification_details',
        __('Certification Details', 'globeiron'),
        'globeiron_certification_meta_box_html',
        'certification',
        'normal',
        'high'
    );
});

function globeiron_certification_meta_box_html(WP_Post $post): void {
    wp_nonce_field('globeiron_certification_save', 'globeiron_certification_nonce');

    $url = get_post_meta($post->ID, '_certification_url', true);
    ?>
    <style>
        .globeiron-cert-meta {
            padding: 4px 0;
        }
        .globeiron-cert-meta label {
            display: block;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #555;
            margin-bottom: 6px;
        }
        .globeiron-cert-meta .field-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .globeiron-cert-meta input[type="url"] {
            flex: 1;
        }
        .globeiron-cert-meta .hint {
            margin-top: 6px;
            font-size: 12px;
            color: #888;
            font-style: italic;
        }
        .globeiron-cert-meta .link-indicator {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #2271b1;
            padding: 4px 8px;
            border: 1px solid #c3d9f0;
            border-radius: 4px;
            background: #f0f6fc;
            white-space: nowrap;
        }
        .globeiron-cert-meta .link-indicator svg {
            flex-shrink: 0;
        }
        #certification-url-status { margin-top: 8px; }
    </style>

    <div class="globeiron-cert-meta">

        <p style="color:#555;margin-top:0;font-size:13px;">
            <?php esc_html_e('The post title is the certification name. Set the featured image (Badge / Logo) in the right-hand panel.', 'globeiron'); ?>
        </p>

        <!-- Website URL -->
        <div style="margin-bottom: 12px;">
            <label for="certification_url"><?php esc_html_e('Website URL', 'globeiron'); ?></label>
            <div class="field-row">
                <input
                    type="url"
                    id="certification_url"
                    name="_certification_url"
                    value="<?php echo esc_attr($url); ?>"
                    placeholder="https://example.com"
                    class="regular-text"
                >
            </div>
            <p class="hint">
                <?php esc_html_e('Optional. If provided, the badge will link to this URL in a new window. A link icon will be shown on the card.', 'globeiron'); ?>
            </p>
            <div id="certification-url-status">
                <?php if ($url) : ?>
                    <span class="link-indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <?php esc_html_e('Opens in new window', 'globeiron'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script>
    (function() {
        var input  = document.getElementById('certification_url');
        var status = document.getElementById('certification-url-status');
        if (!input || !status) return;

        input.addEventListener('input', function() {
            var val = input.value.trim();
            if (val) {
                status.innerHTML = '<span class="link-indicator">'
                    + '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">'
                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>'
                    + '</svg>'
                    + '<?php echo esc_js(__('Opens in new window', 'globeiron')); ?>'
                    + '</span>';
            } else {
                status.innerHTML = '';
            }
        });
    }());
    </script>
    <?php
}

// ─────────────────────────────────────────────────────────────────────────────
//  Save: Certification
// ─────────────────────────────────────────────────────────────────────────────

add_action('save_post_certification', function (int $post_id): void {
    if (
        ! isset($_POST['globeiron_certification_nonce']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['globeiron_certification_nonce'])), 'globeiron_certification_save') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        ! current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $url = isset($_POST['_certification_url']) ? esc_url_raw(wp_unslash($_POST['_certification_url'])) : '';

    if ($url) {
        update_post_meta($post_id, '_certification_url', $url);
    } else {
        delete_post_meta($post_id, '_certification_url');
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  Shared: media picker script (enqueued once per page)
// ─────────────────────────────────────────────────────────────────────────────

function globeiron_enqueue_media_picker_script(): void {
    static $enqueued = false;
    if ($enqueued) return;
    $enqueued = true;

    wp_enqueue_media();
    ?>
    <script>
    (function($) {
        $(function() {
            // Open media library
            $(document).on('click', '.globeiron-pick-image', function(e) {
                e.preventDefault();
                var btn      = $(this);
                var targetId = btn.data('target');
                var previewId = btn.data('preview');

                var frame = wp.media({
                    title:    '<?php echo esc_js(__('Select Image', 'globeiron')); ?>',
                    button:   { text: '<?php echo esc_js(__('Use this image', 'globeiron')); ?>' },
                    multiple: false,
                    library:  { type: 'image' },
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#' + targetId).val(attachment.id);
                    var src = attachment.sizes && attachment.sizes.thumbnail
                        ? attachment.sizes.thumbnail.url
                        : attachment.url;
                    $('#' + previewId).attr('src', src).show();
                    btn.text('<?php echo esc_js(__('Replace', 'globeiron')); ?>');
                    btn.siblings('.globeiron-remove-image').show();
                });

                frame.open();
            });

            // Remove image
            $(document).on('click', '.globeiron-remove-image', function(e) {
                e.preventDefault();
                var btn      = $(this);
                var targetId = btn.data('target');
                var previewId = btn.data('preview');
                $('#' + targetId).val('');
                $('#' + previewId).attr('src', '').hide();
                btn.siblings('.globeiron-pick-image')
                   .text('<?php echo esc_js(__('Choose Image', 'globeiron')); ?>');
                btn.hide();
            });
        });
    }(jQuery));
    </script>
    <?php
}

// ─────────────────────────────────────────────────────────────────────────────
//  Save: Project
// ─────────────────────────────────────────────────────────────────────────────

add_action('save_post_globeiron_project', function (int $post_id): void {
    if (
        ! isset($_POST['globeiron_project_nonce']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['globeiron_project_nonce'])), 'globeiron_project_save') ||
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        ! current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    // Simple text / number fields
    $text_fields = [
        '_project_location',
        '_project_year',
        '_project_sq_footage',
        '_project_materials',
        '_project_client_name',
    ];

    foreach ($text_fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key])));
        } else {
            delete_post_meta($post_id, $key);
        }
    }

    // Services — stored as comma-separated string
    $services = isset($_POST['_project_services']) && is_array($_POST['_project_services'])
        ? implode(', ', array_map('sanitize_text_field', wp_unslash($_POST['_project_services'])))
        : '';
    update_post_meta($post_id, '_project_services', $services);

    // Image IDs
    foreach (['_project_before_img_id', '_project_after_img_id'] as $key) {
        $img_id = isset($_POST[$key]) ? absint($_POST[$key]) : 0;
        if ($img_id) {
            update_post_meta($post_id, $key, $img_id);
        } else {
            delete_post_meta($post_id, $key);
        }
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  Save: Testimonial
// ─────────────────────────────────────────────────────────────────────────────

add_action('save_post_globeiron_testimonial', function (int $post_id): void {
    if (
        ! isset($_POST['globeiron_testimonial_nonce']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['globeiron_testimonial_nonce'])), 'globeiron_testimonial_save') ||
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        ! current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $text_fields = [
        '_testimonial_location',
        '_testimonial_date',
        '_testimonial_service_type',
        '_testimonial_source',
    ];

    foreach ($text_fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key])));
        } else {
            delete_post_meta($post_id, $key);
        }
    }

    // Quote — allow basic HTML (p, em, strong)
    if (isset($_POST['_testimonial_quote'])) {
        update_post_meta(
            $post_id,
            '_testimonial_quote',
            wp_kses($_POST['_testimonial_quote'], ['p' => [], 'em' => [], 'strong' => [], 'br' => []])
        );
    }

    // Rating: must be 1–5
    $rating = isset($_POST['_testimonial_rating']) ? (int) $_POST['_testimonial_rating'] : 5;
    update_post_meta($post_id, '_testimonial_rating', max(1, min(5, $rating)));

    // Photo
    $photo_id = isset($_POST['_testimonial_photo_id']) ? absint($_POST['_testimonial_photo_id']) : 0;
    if ($photo_id) {
        update_post_meta($post_id, '_testimonial_photo_id', $photo_id);
    } else {
        delete_post_meta($post_id, '_testimonial_photo_id');
    }
});
