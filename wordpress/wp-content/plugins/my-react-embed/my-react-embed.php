<?php
/**
 * Plugin Name: My React Embed
 * Description: Nhúng React app vào WordPress qua shortcode [my_react_app]. Hỗ trợ cả Vite (dist/assets) và CRA (build/static).
 * Version: 1.1.0
 * Author: QCV
 */

if (!defined('ABSPATH')) exit;

/** Tiện ích: lấy file mới nhất theo pattern và trả về URL */
function qcv_latest_file_url($abs_dir, $base_url, $pattern) {
  $files = glob(rtrim($abs_dir, '/\\') . '/' . $pattern);
  if (!$files) return null;
  usort($files, function($a,$b){ return filemtime($b) - filemtime($a); });
  return rtrim($base_url, '/').'/'.basename($files[0]);
}

/** Tự phát hiện Vite hay CRA và trả về ['type'=>'vite|cra','js'=>..., 'css'=>...] */
function qcv_locate_assets() {
  $plug_dir = plugin_dir_path(__FILE__);
  $plug_url = plugin_dir_url(__FILE__);

  // --- Thử Vite: dist/assets/index-*.{js,css}
  $vite_dir = $plug_dir . 'dist/assets';
  if (is_dir($vite_dir)) {
    $js  = qcv_latest_file_url($vite_dir, $plug_url.'dist/assets', 'index-*.js');
    $css = qcv_latest_file_url($vite_dir, $plug_url.'dist/assets', 'index-*.css');
    if ($js) return ['type'=>'vite', 'js'=>$js, 'css'=>$css];
  }

  // --- Thử CRA: build/static/js/*, build/static/css/*
  $cra_js_dir  = $plug_dir . 'build/static/js';
  $cra_css_dir = $plug_dir . 'build/static/css';
  if (is_dir($cra_js_dir)) {
    // ưu tiên main.*.js, fallback bất kỳ *.js
    $js  = qcv_latest_file_url($cra_js_dir,  $plug_url.'build/static/js',  'main*.js');
    if (!$js) $js = qcv_latest_file_url($cra_js_dir,  $plug_url.'build/static/js',  '*.js');
    $css = is_dir($cra_css_dir) ? qcv_latest_file_url($cra_css_dir, $plug_url.'build/static/css', 'main*.css') : null;
    if ($js) return ['type'=>'cra', 'js'=>$js, 'css'=>$css];
  }

  return null;
}

/** Enqueue theo loại bundle */
function qcv_enqueue_react_assets() {
  $assets = qcv_locate_assets();
  if (!$assets) return;

  if (!empty($assets['css'])) {
    wp_enqueue_style('qcv-react-css', $assets['css'], [], null);
  }

  wp_enqueue_script('qcv-react-js', $assets['js'], [], null, true);

  // Vite build là ES Module → cần type="module"
  if ($assets['type'] === 'vite') {
    // cách 1 (WP mới): 
    if (function_exists('wp_script_add_data')) {
      wp_script_add_data('qcv-react-js', 'type', 'module');
    }
    // cách 2 (phổ quát): filter đảm bảo có type="module"
    add_filter('script_loader_tag', function($tag, $handle, $src){
      if ($handle === 'qcv-react-js' && strpos($tag, 'type=') === false) {
        $tag = str_replace(' src=', ' type="module" src=', $tag);
      }
      return $tag;
    }, 10, 3);
  }
}

/** Shortcode mount vào #root (CRA & Vite mặc định đều dùng #root) */
function qcv_react_shortcode($atts = []) {
  qcv_enqueue_react_assets();
  return '<div id="root"></div>';
}
add_shortcode('my_react_app', 'qcv_react_shortcode');
