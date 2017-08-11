<?php

add_action( 'add_meta_boxes', 'ac_add_meta_boxes' );
add_action( 'admin_menu', 'ac_admin_menu' );
add_action( 'admin_enqueue_scripts', 'ac_admin_enqueue_scripts' );
add_action( 'pre_post_update', 'ac_pre_post_update' );
add_action( 'wp_enqueue_scripts', 'ac_wp_enqueue_scripts' );

add_shortcode( 'ac_calendar', 'ac_calendar' );
add_shortcode( 'ac_price_table', 'ac_price_table' );
