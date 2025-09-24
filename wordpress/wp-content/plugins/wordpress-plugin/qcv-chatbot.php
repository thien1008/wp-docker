<?php
/**
 * Plugin Name: QCV Chatbot
 * Description: Shortcode [qcv_chatbot] để nhúng chatbot báo giá vật liệu.
 * Version: 1.0.0
 * Author: Quang Cao Viet
 */

if (!defined('ABSPATH')) exit;

define('QCV_API_URL', getenv('QCV_API_URL') ? getenv('QCV_API_URL') : 'http://localhost:8080/api/chat');

function qcv_chatbot_assets(){
  wp_enqueue_style('qcv-chatbot-style', plugin_dir_url(__FILE__) . 'widget.css', array(), '1.0');
  wp_enqueue_script('qcv-chatbot', plugin_dir_url(__FILE__) . 'widget.js', array(), '1.0', true);
  wp_localize_script('qcv-chatbot', 'QCV_CFG', array(
    'API_URL' => QCV_API_URL,
  ));
}
add_action('wp_enqueue_scripts','qcv_chatbot_assets');

function qcv_chatbot_shortcode($atts){
  $atts = shortcode_atts(array(), $atts, 'qcv_chatbot');
  ob_start(); ?>
    <div id="qcv-chatbot">
      <div class="qcv-box">
        <div class="qcv-messages"></div>
        <div class="qcv-input">
          <input id="qcv-text" placeholder="Hỏi báo giá vật liệu..."/>
          <button id="qcv-send" type="button">Gửi</button>
        </div>
      </div>
    </div>
  <?php
  return ob_get_clean();
}
add_shortcode('qcv_chatbot','qcv_chatbot_shortcode');
