    <footer class="site-footer" id="colophon" aria-label="<?php esc_attr_e('Site footer', 'globeiron'); ?>">

        <!-- Serving banner -->
        <div class="footer-banner">
            <p class="footer-banner__text">
                <?php echo esc_html((string)(get_field('footer_serving_tagline', 'option') ?: 'Proudly Serving Greater Cincinnati, Columbus and Indianapolis Neighbors')); ?>
            </p>
        </div>

        <!-- Main grid -->
        <div class="footer-main">
            <div class="footer-main__inner">

                <!-- Col 1: Contact -->
                <div class="footer-col footer-col--contact">
                    <h4 class="footer-col__heading"><?php esc_html_e('Contact', 'globeiron'); ?></h4>
                    <div class="footer-contact-info">
                        <?php
                        $address = (string)(get_field('site_address', 'option') ?: "Globe Iron Headquarters\n6161 Wiehe Road\nCincinnati, OH 45237");
                        $phone   = (string)(get_field('site_phone',   'option') ?: '513-371-1841');
                        $fax     = (string)(get_field('site_fax',     'option') ?: '');
                        $email   = (string)(get_field('site_email',   'option') ?: '');
                        ?>
                        <p><?php echo nl2br(esc_html($address)); ?></p>
                        <p>
                            <?php echo esc_html($phone); ?>
                            <?php if ($fax) : ?>
                                <br><?php esc_html_e('Fax:', 'globeiron'); ?> <?php echo esc_html($fax); ?>
                            <?php endif; ?>
                            <?php if ($email) : ?>
                                <br><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <!-- Col 2: Commercial + Maintenance -->
                <div class="footer-col footer-col--nav">
                    <h4 class="footer-col__heading">
                        <a href="<?php echo esc_url(home_url('/commercial')); ?>"><?php esc_html_e('Commercial', 'globeiron'); ?></a>
                    </h4>
                    <h4 class="footer-col__heading">
                        <a href="<?php echo esc_url(home_url('/maintenance')); ?>"><?php esc_html_e('Maintenance', 'globeiron'); ?></a>
                    </h4>
                </div>

                <!-- Col 3: Residential + Our Work -->
                <div class="footer-col footer-col--nav">
                    <h4 class="footer-col__heading">
                        <a href="<?php echo esc_url(home_url('/residential')); ?>"><?php esc_html_e('Residential', 'globeiron'); ?></a>
                    </h4>
                    <h4 class="footer-col__heading">
                        <a href="<?php echo esc_url(home_url('/our-work')); ?>"><?php esc_html_e('Our Work', 'globeiron'); ?></a>
                    </h4>
                </div>

                <!-- Col 4: Historic Restoration + About -->
                <div class="footer-col footer-col--nav">
                    <h4 class="footer-col__heading">
                        <a href="<?php echo esc_url(home_url('/historic-restoration')); ?>"><?php esc_html_e('Historic Restoration', 'globeiron'); ?></a>
                    </h4>
                    <h4 class="footer-col__heading">
                        <a href="<?php echo esc_url(home_url('/about')); ?>"><?php esc_html_e('About', 'globeiron'); ?></a>
                    </h4>
                </div>

                <!-- Col 5: Social icons + Certifications -->
                <div class="footer-col footer-col--social">
                    <div class="footer-social">
                        <?php
                        $social = [
                            ['url' => (string)(get_field('social_facebook',  'option') ?: '#'), 'label' => __('Facebook',  'globeiron'), 'icon' => 'facebook.svg'],
                            ['url' => (string)(get_field('social_instagram', 'option') ?: '#'), 'label' => __('Instagram', 'globeiron'), 'icon' => 'insta.svg'],
                            ['url' => (string)(get_field('social_linkedin',  'option') ?: '#'), 'label' => __('LinkedIn',  'globeiron'), 'icon' => 'linkedin.svg'],
                            ['url' => (string)(get_field('social_youtube',   'option') ?: '#'), 'label' => __('YouTube',   'globeiron'), 'icon' => 'youtube.svg'],
                            ['url' => (string)(get_field('social_tiktok',    'option') ?: '#'), 'label' => __('TikTok',    'globeiron'), 'icon' => 'tictoc.svg'],
                        ];
                        foreach ($social as $item) : ?>
                        <a href="<?php echo esc_url($item['url']); ?>"
                           aria-label="<?php echo esc_attr($item['label']); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="footer-social__link">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/social/' . $item['icon']); ?>"
                                 alt="<?php echo esc_attr($item['label']); ?>"
                                 width="39" height="39">
                        </a>
                        <?php endforeach; ?>
                    </div>

                    <div class="footer-certs">
                        <a href="https://nrca.net/" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/footer/nrca-logo.png'); ?>"
                                 alt="<?php esc_attr_e('National Roofing Contractors Association Member', 'globeiron'); ?>"
                                 width="80" height="80">
                        </a>
                        <a href="https://www.bbb.org/" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/footer/bbb-logo.png'); ?>"
                                 alt="<?php esc_attr_e('BBB Accredited Business A+ Rating', 'globeiron'); ?>"
                                 width="100" height="60">
                        </a>
                    </div>
                </div>

            </div>
        </div><!-- /footer-main -->

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <div class="footer-bottom__inner">
                <p class="footer-copyright">
                    &copy; <?php echo esc_html(date('Y')); ?>
                    <?php bloginfo('name'); ?> |
                    <?php esc_html_e('Time-Tested Technicians.', 'globeiron'); ?>
                </p>
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'footer-legal-links',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]);
                ?>
            </div>
        </div>

    </footer>

</div><!-- /.site -->

<?php wp_footer(); ?>
</body>
</html>
