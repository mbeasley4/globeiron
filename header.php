<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site" id="page">
    <a class="skip-to-content" href="#main"><?php esc_html_e('Skip to content', 'globeiron'); ?></a>

    <!-- ─── Sticky header bar ─────────────────────────────────────────────────── -->
    <div class="tw-sticky tw-top-0 tw-z-[100]">
        <header id="masthead">
            <div class="tw-bg-brand-blue">
                <nav class="tw-h-[70px] sm:tw-h-[85px] lg:tw-h-[100px] tw-flex tw-items-center tw-justify-between tw-px-4 sm:tw-px-6 lg:tw-px-10 tw-max-w-[1440px] tw-mx-auto"
                     aria-label="<?php esc_attr_e('Primary navigation', 'globeiron'); ?>">

                    <!-- Logo -->
                    <div class="header-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url( home_url('/') ); ?>" rel="home"
                               class="tw-flex tw-items-center tw-shrink-0 tw-no-underline">
                                <img src="<?php echo esc_url( get_template_directory_uri() . '/img/globe-iron-roofing-logo.svg' ); ?>"
                                     alt="<?php bloginfo('name'); ?>"
                                     width="176" height="73"
                                     class="site-logo tw-w-auto">
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- ─── Slideout nav panel ─────────────────────────────────── -->
                    <div class="site-header__nav" id="primary-nav-panel"
                         data-nav-panel aria-hidden="true" inert>

                        <!-- Panel header: logo + close (mobile only) -->
                        <div class="nav-panel__header">
                            <a href="<?php echo esc_url( home_url('/') ); ?>" rel="home" class="nav-panel__logo-link">
                                <img src="<?php echo esc_url( get_template_directory_uri() . '/img/globe-iron-roofing-logo.svg' ); ?>"
                                     alt="<?php bloginfo('name'); ?>"
                                     class="nav-panel__logo">
                            </a>
                            <button class="nav-panel__close" data-nav-close
                                    aria-label="<?php esc_attr_e('Close menu', 'globeiron'); ?>">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <line x1="4" y1="4" x2="16" y2="16"/>
                                    <line x1="16" y1="4" x2="4" y2="16"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Nav menu (single render, shown in panel on mobile / inline on desktop) -->
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'nav nav--light',
                            'items_wrap'     => '<ul id="%1$s" class="%2$s" data-nav-menu>%3$s</ul>',
                            'fallback_cb'    => false,
                        ]);
                        ?>

                        <!-- Panel CTA: contact link (mobile only) -->
                        <div class="nav-panel__cta">
                            <a href="<?php echo esc_url( home_url('/contact') ); ?>"
                               class="btn btn--brand">
                                <?php esc_html_e('Contact', 'globeiron'); ?>
                            </a>
                        </div>
                    </div><!-- /.site-header__nav -->

                    <!-- Right side: mobile hamburger -->
                    <div>
                        <button class="nav-toggle nav-toggle--light" data-nav-toggle
                                aria-expanded="false" aria-controls="primary-nav-panel"
                                aria-label="<?php esc_attr_e('Toggle menu', 'globeiron'); ?>">
                            <span></span><span></span><span></span>
                        </button>
                    </div>

                </nav>
            </div>
        </header>
    </div><!-- /.sticky wrapper -->

    <!-- Overlay backdrop (outside sticky stacking context, z-index below header) -->
    <div class="nav-overlay" data-nav-overlay aria-hidden="true"></div>
