<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPDesk_Flexible_Product_Fields_Tracker' ) ) {
	class WPDesk_Flexible_Product_Fields_Tracker {

		public static $script_version = '11';

		public function __construct() {
			$this->hooks();
		}

		public function hooks() {
			add_filter( 'wpdesk_tracker_data', array( $this, 'wpdesk_tracker_data' ), 11 );
			add_filter( 'wpdesk_tracker_notice_screens', array( $this, 'wpdesk_tracker_notice_screens' ) );
			add_filter( 'wpdesk_track_plugin_deactivation', array( $this, 'wpdesk_track_plugin_deactivation' ) );

			add_filter( 'plugin_action_links_flexible-product-fields/flexible-product-fields.php', array( $this, 'plugin_action_links' ), 1 );
			add_action( 'activated_plugin', array( $this, 'activated_plugin' ), 10, 2 );
		}

		public function wpdesk_track_plugin_deactivation( $plugins ) {
			$plugins['flexible-product-fields/flexible-product-fields.php'] = 'flexible-product-fields/flexible-product-fields.php';
			return $plugins;
		}

		public function wpdesk_tracker_data( $data ) {
			$plugin_data = array(
				'field_types'       => array(),
				'assign_to'         => array(),
				'sections'          => array(),
				'price_count'       => 0,
			);
			
			if ( is_flexible_products_fields_pro_active() ) {
				$plugin_data['pro'] = 'yes';
			}
			else {
				$plugin_data['pro'] = 'no';
			}

			$plugin_data['groups'] = array();
			$all_parcels = 0;
			global $wpdb;
			$sql = "
				SELECT count(p.ID) AS count, p.post_status AS post_status, min(p.post_date) AS min, max(p.post_date) AS max
				FROM {$wpdb->posts} p 
				WHERE p.post_type = 'fpf_fields'
				GROUP BY p.post_status
			";
			$query = $wpdb->get_results( $sql );
			if ( $query ) {
				foreach ( $query as $row ) {
					$plugin_data['groups'][$row->post_status] = $row->count;
				}
			}

			$posts = get_posts( array(
				'post_type'         => 'fpf_fields',
				'post_status'       => 'publish',
				'posts_per_page'    => -1,
			));
			foreach ( $posts as $post ) {
				$assign_to = get_post_meta( $post->ID, '_assign_to', true );
				if ( !isset( $plugin_data['assign_to'][$assign_to] ) ) {
					$plugin_data['assign_to'][$assign_to] = 0;
				}
				$plugin_data['assign_to'][$assign_to]++;

				$section = get_post_meta( $post->ID, '_section', true );
				if ( !isset( $plugin_data['sections'][$section] ) ) {
					$plugin_data['sections'][$section] = 0;
				}
				$plugin_data['sections'][$section]++;

				$fields = get_post_meta( $post->ID, '_fields', true );
				if ( is_array( $fields ) ) {
					foreach ( $fields as $field ) {
						if ( !isset( $plugin_data['field_types'][$field['type']] ) ) {
							$plugin_data['field_types'][$field['type']] = 0;
						}
						$plugin_data['field_types'][$field['type']]++;
						if ( !empty($field['price']) ) {
							$plugin_data['price_count']++;
						}
					}
				}
			}

			$data['flexible_product_fields'] = $plugin_data;

			return $data;
		}

		public function wpdesk_tracker_notice_screens( $screens ) {
			$current_screen = get_current_screen();
			if ( in_array( $current_screen->id, array( 'fpf_fields', 'edit-fpf_fields' ) ) ) {
				$screens[] = $current_screen->id;
			}
			return $screens;
		}

		public function plugin_action_links( $links ) {
			if ( !wpdesk_tracker_enabled() || apply_filters( 'wpdesk_tracker_do_not_ask', false ) ) {
				return $links;
			}
			$options = get_option('wpdesk_helper_options', array() );
			if ( empty( $options['wpdesk_tracker_agree'] ) ) {
				$options['wpdesk_tracker_agree'] = '0';
			}
			$plugin_links = array();
			if ( $options['wpdesk_tracker_agree'] == '0' ) {
				$opt_in_link = admin_url( 'admin.php?page=wpdesk_tracker&plugin=flexible-product-fields/flexible-product-fields.php' );
				$plugin_links[] = '<a href="' . $opt_in_link . '">' . __( 'Opt-in', 'flexible-product-fields' ) . '</a>';
			}
			else {
				$opt_in_link = admin_url( 'plugins.php?wpdesk_tracker_opt_out=1&plugin=flexible-product-fields/flexible-product-fields.php' );
				$plugin_links[] = '<a href="' . $opt_in_link . '">' . __( 'Opt-out', 'flexible-product-fields' ) . '</a>';
			}
			return array_merge( $plugin_links, $links );
		}


		public function activated_plugin( $plugin, $network_wide ) {
			if ( !wpdesk_tracker_enabled() ) {
				return;
			}
			if ( $plugin == 'flexible-product-fields/flexible-product-fields.php' ) {
				$options = get_option('wpdesk_helper_options', array() );

				if ( empty( $options ) ) {
					$options = array();
				}
				if ( empty( $options['wpdesk_tracker_agree'] ) ) {
					$options['wpdesk_tracker_agree'] = '0';
				}
				$wpdesk_tracker_skip_plugin = get_option( 'wpdesk_tracker_skip_flexible_product_fields', '0' );
				if ( $options['wpdesk_tracker_agree'] == '0' && $wpdesk_tracker_skip_plugin == '0' ) {
					update_option( 'wpdesk_tracker_notice', '1' );
					update_option( 'wpdesk_tracker_skip_flexible_product_fields', '1' );
					if ( !apply_filters( 'wpdesk_tracker_do_not_ask', false ) ) {
						wp_redirect( admin_url( 'admin.php?page=wpdesk_tracker&plugin=flexible-product-fields/flexible-product-fields.php' ) );
						exit;
					}
				}
			}
		}

	}

	new WPDesk_Flexible_Product_Fields_Tracker();

}

if ( !function_exists( 'wpdesk_activated_plugin_activation_date' ) ) {
	function wpdesk_activated_plugin_activation_date( $plugin, $network_wide ) {
		$option_name = 'plugin_activation_' . $plugin;
		$activation_date = get_option( $option_name, '' );
		if ( $activation_date == '' ) {
			$activation_date = current_time( 'mysql' );
			update_option( $option_name, $activation_date );
		}
	}
	add_action( 'activated_plugin', 'wpdesk_activated_plugin_activation_date', 10, 2 );
}

if ( !function_exists( 'wpdesk_tracker_enabled' ) ) {
	function wpdesk_tracker_enabled() {
		$tracker_enabled = true;
		if ( !empty( $_SERVER['SERVER_ADDR'] ) && $_SERVER['SERVER_ADDR'] == '127.0.0.1' ) {
			$tracker_enabled = false;
		}
		return apply_filters( 'wpdesk_tracker_enabled', $tracker_enabled );
		// add_filter( 'wpdesk_tracker_enabled', '__return_true' );
		// add_filter( 'wpdesk_tracker_do_not_ask', '__return_true' );
	}
}
