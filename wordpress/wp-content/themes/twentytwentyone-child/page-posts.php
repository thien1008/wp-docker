<?php
/* Template Name: Page Hiển Thị Bài Post */
get_header(); ?>

<main id="site-content" class="post-list">
    <?php
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <article class="custom-post-item">
                <div class="post-date">
                    <span class="day"><?php echo get_the_date('d'); ?></span>
                    <span class="month"><?php echo strtoupper(get_the_date('M')); ?></span>
                    <span class="year"><?php echo get_the_date('Y'); ?></span>
                </div>
                <div class="post-thumb">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                </div>
                <div class="post-info">
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></div>
                </div>
            </article>
        <?php endwhile;
        wp_reset_postdata();
    else :
        echo '<p>Chưa có bài viết nào.</p>';
    endif; ?>
</main>

<?php get_footer(); ?>
