<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Customize comment form for logged-in users
// function custom_comment_form_args($args) {
//     if (is_user_logged_in()) {
//         $args['title_reply'] = 'Make a Post';
//         $args['label_submit'] = 'Share';
//         $args['comment_field'] = '
//             <p class="comment-form-comment">
//                 <textarea id="comment" name="comment" placeholder="What are you thinking..." required></textarea>
//             </p>';
//         $args['class_submit'] = 'btn-share';
//     }
//     return $args;
// }
// add_filter('comment_form_defaults', 'custom_comment_form_args');

// Đăng ký menu
function my_theme_setup() {
  register_nav_menus([
    'primary' => __( 'Main Menu', 'mytheme' ),
	 'secondary' => __('Footer Menu', 'mytheme'),
  ]);
}
add_shortcode('search_title', function() {
    if (is_search()) {
        $query = get_search_query();

        // Kiểm tra nếu không có kết quả
        if (!have_posts()) {
            return '
                <div class="search-result-header">
                    <h4 class="search-heading" style="display: flex; justify-content: center;"> <span style="color:hsl(343.48deg 76.72% 45.49%);">Search: </span>  “ ' . esc_html($query) . '”</h4>
                </div>
            ';
        } else {
            // Có kết quả
            return '
				<div class="search-result-header">
                    <h4 class="search-heading" style="display: flex; justify-content: center;"> <span style="color:hsl(343.48deg 76.72% 45.49%);">Search: </span>  “ ' . esc_html($query) . '”</h4>
                </div>
				';
        }
    }
    return '';
});
add_action('after_setup_theme', 'my_theme_setup');

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			//wp_get_theme()->get( 'Version' )
			time() // Xóa cache CSS khi chỉnh sửa
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;


// Ghi đè form bình luận giống giao diện "Make a Post"
// function custom_comment_form($args) {
//     // Nếu người dùng đã đăng nhập
//     if (is_user_logged_in()) {
//         $args['title_reply'] = ''; // bỏ tiêu đề mặc định
//         $args['label_submit'] = 'Share';
//         $args['comment_field'] = '
//             <div class="make-post-box">
//                 <div class="make-post-header">Make a Post</div>
//                 <textarea id="comment" name="comment" placeholder="What are you thinking..." required></textarea>
//                 <button type="submit" class="btn-share">Share</button>
//             </div>';
//         $args['comment_notes_before'] = '';
//         $args['comment_notes_after'] = '';
//         $args['class_submit'] = 'hidden-submit'; // ẩn submit mặc định
//     } else {
//         // Chưa login → hiển thị link đăng nhập
//         $args['title_reply'] = '';
//         $args['comment_field'] = '<p>You need to <a href="' . wp_login_url() . '">log in</a> to make a post.</p>';
//     }
//     return $args;
// }
// add_filter('comment_form_defaults', 'custom_comment_form');

// ---------- CUSTOM COMMENT FORM (Make a Post look) ----------
function tt25_custom_comment_form_defaults( $defaults ) {
	// Nếu đang đăng nhập thì thay giao diện
	if ( is_user_logged_in() ) {
		$defaults['title_reply'] = '';                 // ẩn title mặc định
		$defaults['comment_notes_before'] = '';       // ẩn note trước
		$defaults['comment_notes_after']  = '';       // ẩn note sau
		$defaults['label_submit'] = 'share';          // text nút
		// Thay toàn bộ field comment bằng markup tùy chỉnh
		$defaults['comment_field'] = '
			<div class="make-post-wrap">
				<div class="make-post-inner">
					<div class="make-post-tab">Make a Post</div>
					<div class="make-post-body">
						<textarea id="comment" name="comment" placeholder="What are you thinking..." required aria-required="true"></textarea>
						<div class="make-post-actions">
							<button type="submit" class="btn-share">share</button>
						</div>
					</div>
				</div>
			</div>';
		// ẩn nút submit mặc định bằng lớp (cách an toàn)
		$defaults['class_submit'] = 'hidden-submit';
	}

	return $defaults;
}
add_filter( 'comment_form_defaults', 'tt25_custom_comment_form_defaults' );


















/* Shortcode: [latest_timeline count="3" title="Latest News"]
   - count: số bài (mặc định 3)
   - title: tiêu đề (mặc định "Latest News")
*/
function tt_latest_timeline_sc($atts = [])
{
    $atts = shortcode_atts([
        'count' => 3,
        'title' => 'Latest News',
    ], $atts, 'latest_timeline');

    $posts = get_posts([
        'numberposts' => (int) $atts['count'],
        'post_status' => 'publish',
    ]);
    if (!$posts)
        return '';

    ob_start(); ?>
    <div class="latepost-main">
        <div class="timeline-wrapper">
            <h3 class="timeline-title"><?php echo esc_html($atts['title']); ?></h3>
            <ul class="timeline-list">
                <?php foreach ($posts as $p):
                    $link = get_permalink($p);
                    $title = get_the_title($p);
                    $date = get_the_date('j F, Y', $p);
                    $excerpt = wp_trim_words(strip_tags(get_the_excerpt($p) ?: get_the_content(null, false, $p)), 20, '...');
                    ?>
                    <li class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <a class="timeline-link"
                                    href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
                                <span class="timeline-date"><?php echo esc_html($date); ?></span>
                            </div>
                            <p class="timeline-excerpt"><?php echo esc_html($excerpt); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('latest_timeline', 'tt_latest_timeline_sc');

/* CSS cho timeline */
add_action('wp_head', function () { ?>
    <style>
        .latepost-main {
            background: #fff !important;
            padding: 30px 40px !important;
            border-radius: 6px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05) !important;
        }

        .latepost-main .timeline-title {
            font-size: 24px !important;
            font-weight: 700 !important;
            color: #333 !important;
            margin: 0 0 20px !important;
        }

        .latepost-main .timeline-list {
            position: relative;
            list-style: none;
            margin: 0;
            padding: 0px;
            border-left: 2px solid #cfe8f7;
        }

        .latepost-main .timeline-item {
            position: relative;
            padding: 0 0 28px 25px;
        }

        /* make sure dots line up perfectly */
        .latepost-main .timeline-dot {
            position: absolute !important;
            left: -8px !important;
            top: 6px !important;
            transform: translateY(0) !important;
/* fine-tune vertical alignment */
width: 14px !important;
            height: 14px !important;
            background: #fff;
            border: 3px solid #2b6cb0;
            border-radius: 50%;
            box-sizing: border-box;
        }

        .latepost-main .timeline-content {
            margin-left: 0 !important;
            padding-top: 2px !important;
        }

        .latepost-main .timeline-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: baseline !important;
            gap: 12px !important;
            flex-wrap: wrap !important;
        }

        .latepost-main .timeline-link {
            font-weight: 600;
            color: #2b6cb0;
            text-decoration: none;
            line-height: 1.4;
        }

        .latepost-main .timeline-link:hover {
            color: #1a5fd8;
            text-decoration: underline;
        }

        .latepost-main .timeline-date {
            font-size: 14px;
            color: #1a5fd8;
            white-space: nowrap;
        }

        .latepost-main .timeline-excerpt {
            margin: 6px 0 0;
            font-size: 15px;
            color: #555;
            line-height: 1.6;
        }
    </style>
<?php });



function render_custom_comment_list() {
    // Lấy tất cả comment cha (parent = 0)
    $comments = get_comments([
        'status' => 'approve',
        'number' => 3,
        'parent' => 0,
        'orderby' => 'comment_date_gmt',
        'order' => 'DESC',
        'post_id' => get_the_ID(),
    ]);

    if (empty($comments)) {
        return '<p>Chưa có bình luận nào.</p>';
    }

    ob_start();
    echo '<div class="comment-box">';


    foreach ($comments as $comment) {
        custom_render_comment_tree($comment);
    }

    echo '</div>';
    return ob_get_clean();
}
add_shortcode('custom_comment_list', 'render_custom_comment_list');


// Hàm đệ quy render comment cha + con
function custom_render_comment_tree($comment, $depth = 0) {
    ?>
    <div class="media comment-level-<?php echo $depth; ?>">
        <div class="media-left">
            <?php echo get_avatar($comment, 50); ?>
        </div>

        <div class="media-body">
            <div class="media-heading">
                <strong><?php echo esc_html(get_comment_author($comment)); ?></strong>
<!--                <span class="comment-date">(--><?php //echo get_comment_date('', $comment); ?><!--)</span>-->
            </div>

            <p><?php echo esc_html(get_comment_text($comment)); ?></p>

            <?php
            // Lấy các comment con (reply)
            $replies = get_comments([
                'status' => 'approve',
                'parent' => $comment->comment_ID,
                'orderby' => 'comment_date_gmt',
                'order' => 'ASC',

            ]);

            if ($replies) {
                echo '<div class="comment-replies">';
                foreach ($replies as $reply) {
                    custom_render_comment_tree($reply, $depth + 1);
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <?php
}