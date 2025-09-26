<?php get_header(); ?>

<style>
.custom-posts-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #ffffff;
}

.post-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 30px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: box-shadow 0.3s ease;
    overflow: hidden;
}

.post-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.post-content-section {
    flex: 1;
    display: flex;
}

.post-image {
    flex: 0 0 280px;
}

.post-image img {
    width: 100%;
    height: 100%;
    min-height: 200px;
    object-fit: cover;
    display: block;
}

.post-details {
    flex: 1;
    padding: 24px;
}

.post-header {
    display: flex;
    align-items: baseline;
    gap: 20px;
    margin-bottom: 15px;
}

.post-date-display {
    flex: 0 0 auto;
    position: relative;
    padding-right: 20px;
    padding-bottom: 10px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.post-date-display::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    width: 2px;
    height: 100%;
    background-color: #0073aa;
}

.post-date-display::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #0073aa;
}

.post-day {
    font-size: 48px;
    font-weight: bold;
    color: #0073aa;
    line-height: 1;
    margin: 0;
}

.post-month-year {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-top: 5px;
}

.post-month {
    font-size: 14px;
    color: #0073aa;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0;
    line-height: 1.2;
}

.post-year {
    font-size: 14px;
    color: #0073aa;
    font-weight: 500;
    margin: 2px 0 0 0;
    line-height: 1.2;
}

.post-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
    flex: 1;
}

.post-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-title a:hover {
    color: #0073aa;
}

.post-categories {
    margin-bottom: 12px;
}

.post-categories a {
    display: inline-block;
    background: #f8f9fa;
    color: #6c757d;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    text-decoration: none;
    margin-right: 8px;
    margin-bottom: 4px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.post-categories a:hover {
    background: #0073aa;
    color: white;
    border-color: #0073aa;
}

.post-excerpt {
    font-size: 15px;
    line-height: 1.6;
    color: #666;
    margin: 0;
}

.no-posts {
    text-align: center;
    padding: 60px 20px;
    color: #666;
    font-size: 18px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .post-item {
        flex-direction: column;
    }
    
    .post-content-section {
        flex-direction: column;
    }
    
    .post-image {
        flex: none;
        order: -1;
    }
    
    .post-image img {
        height: 200px;
        min-height: 200px;
    }
    
    .post-details {
        padding: 20px;
    }
    
    .post-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .post-day {
        font-size: 36px;
    }
    
    .post-title {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .custom-posts-container {
        padding: 15px;
    }
    
    .post-item {
        margin-bottom: 20px;
    }
    
    .post-details {
        padding: 15px;
    }
    
    .post-title {
        font-size: 18px;
    }
    
    .post-excerpt {
        font-size: 14px;
    }
    
    .post-day {
        font-size: 32px;
    }
}
</style>

<div class="custom-posts-container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article class="post-item">
                <div class="post-content-section">
                    <div class="post-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="https://via.placeholder.com/280x180/f8f9fa/6c757d?text=No+Image" 
                                     alt="<?php the_title(); ?>">
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="post-details">
                        <div class="post-header">
                            <div class="post-date-display">
                                <div class="post-day"><?php echo get_the_date('d'); ?></div>
                                <div class="post-month-year">
                                    <div class="post-month">THÁNG <?php echo get_the_date('n'); ?></div>
                                    <div class="post-year"><?php echo get_the_date('Y'); ?></div>
                                </div>
                            </div>
                            
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </div>
                        
                        <div class="post-categories">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                echo '<span style="color: #6c757d; font-size: 12px; margin-right: 8px;">Categories:</span>';
                                foreach ($categories as $category) {
                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="post-excerpt">
                            <?php 
                            $excerpt = get_the_excerpt();
                            if ($excerpt) {
                                echo wp_trim_words($excerpt, 25, '...');
                            } else {
                                echo wp_trim_words(get_the_content(), 25, '...');
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
        
        <div class="pagination-wrapper">
            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '&larr; Trang trước',
                'next_text' => 'Trang sau &rarr;',
            ));
            ?>
        </div>
        
    <?php else : ?>
        <div class="no-posts">
            <p><?php _e('Không tìm thấy bài viết nào.', 'twentytwentyone'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>