<?php
/**
 * 404 template.
 *
 * @package Globeiron
 */

get_header(); ?>

<main id="main" class="site-content">
    <section class="page-404" aria-labelledby="page-404-heading">

        <div class="page-404__inner">

            <!-- Animated crosshair + dotted line -->
            <div class="page-404__indicator" aria-hidden="true">
                <svg class="page-404__crosshair"
                     viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5"
                     xmlns="http://www.w3.org/2000/svg">
                    <circle cx="14" cy="14" r="12" pathLength="1"/>
                    <line x1="14" y1="7" x2="14" y2="21" pathLength="1"/>
                    <line x1="7" y1="14" x2="21" y2="14" pathLength="1"/>
                </svg>
                <div class="page-404__line"></div>
            </div>

            <!-- Decorative 404 number -->
            <p class="page-404__number" aria-hidden="true">404</p>

            <h1 class="page-404__heading" id="page-404-heading">
                <?php esc_html_e( 'This page has gone missing.', 'globeiron' ); ?>
            </h1>

            <p class="page-404__sub">
                <?php esc_html_e( 'One partner, zero gaps — let\'s get you back on track.', 'globeiron' ); ?>
            </p>

            <div class="page-404__cta">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--brand">
                    <?php esc_html_e( 'Return Home', 'globeiron' ); ?>
                </a>
            </div>

        </div>

    </section>
</main>

<?php get_footer();
