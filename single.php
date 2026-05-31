<?php
/**
 * Single post template.
 *
 * @package Globeiron
 */

get_header();

$blog_page = get_page_by_path('blog');
$blog_url  = $blog_page ? get_permalink($blog_page->ID) : home_url('/blog/');
?>

<main id="main" class="site-content single-post">
<?php while (have_posts()) : the_post();

    $cats      = get_the_category();
    $cat       = $cats[0] ?? null;
    $date      = get_the_date('F j, Y');
    $has_img   = has_post_thumbnail();
    $crosshair = '<svg viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="14" cy="14" r="12" pathLength="1"/><line x1="14" y1="7" x2="14" y2="21" pathLength="1"/><line x1="7" y1="14" x2="21" y2="14" pathLength="1"/></svg>';
?>

    <div class="single-post-hero<?php echo $has_img ? '' : ' single-post-hero--text-only'; ?>">

        <div class="single-post-hero__topbar">
            <a href="<?php echo esc_url($blog_url); ?>" class="single-post-hero__back">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                    <circle cx="9" cy="9" r="8" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M10.5 6L7.5 9l3 3" stroke="currentColor" stroke-width="1.2"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to Blog
            </a>
        </div>

        <div class="single-post-hero__inner">

            <div class="single-post-hero__content">
                <div class="single-post-hero__meta">
                    <?php if ($cat) : ?>
                        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="single-post-hero__cat"><?php echo esc_html($cat->name); ?></a>
                        <span class="single-post-hero__meta-sep" aria-hidden="true">·</span>
                    <?php endif; ?>
                    <time class="single-post-hero__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html($date); ?></time>
                </div>

                <h1 class="single-post-hero__title"><?php the_title(); ?></h1>
            </div>

            <?php if ($has_img) : ?>
            <div class="single-post-hero__ornament" aria-hidden="true">
                <div class="single-post-hero__ornament-crosshair"><?php echo $crosshair; ?></div>
                <div class="single-post-hero__ornament-line"></div>
                <div class="single-post-hero__ornament-crosshair"><?php echo $crosshair; ?></div>
            </div>
            <div class="single-post-hero__media">
                <?php the_post_thumbnail('large', ['class' => 'single-post-hero__img', 'loading' => 'eager', 'decoding' => 'async']); ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="single-post-body">
        <div class="single-post-body__inner">
            <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
                <?php the_content(); ?>
            </article>
        </div>
    </div>

<?php endwhile; ?>
</main>

<?php get_footer();
