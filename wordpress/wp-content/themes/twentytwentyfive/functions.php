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