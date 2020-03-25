<?php /*

**************************************************************************

Plugin Name:  Instagram Widget
Description:  Display some Instagram photos via a widget.
Author:       Automattic Inc.
Author URI:   http://automattic.com/

**************************************************************************/

/**
 * This is the actual Instagram widget along with other code that only applies to the widget.
 */

use Automattic\Jetpack\Connection\Client;

class WPcom_Instagram_Widget extends WP_Widget {

	const ID_BASE = 'wpcom_instagram_widget';

	public $valid_options;
	public $defaults;

	/**
	 * Sets the widget properties in WordPress, hooks a few functions, and sets some widget options.
	 */
	function __construct() {
		parent::__construct(
			self::ID_BASE,
			__( 'Instagram', 'wpcomsh' ),
			array(
				'description' => __( 'Display your latest Instagram photos.', 'wpcomsh' ),
			)
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ) );
		add_action( 'wp_ajax_wpcom_instagram_widget_update_widget_token_id', array( $this, 'ajax_update_widget_token_id' ) );

		$this->valid_options = array(
			'max_columns' => 3,
			'max_count'   => 20,
		);

		$this->defaults = array(
			'token_id' => null,
			'title'    => __( 'Instagram', 'wpcomsh' ),
			'columns'  => 2,
			'count'    => 6,
		);
	}

	/**
	 * Enqueues the widget's frontend CSS but only if the widget is currently in use.
	 */
	public function enqueue_css() {
		if ( ! is_active_widget( false, false, self::ID_BASE ) )
			return;

		wp_enqueue_style( self::ID_BASE, plugins_url( 'instagram.css', __FILE__ ) );
	}

	/**
	 * Updates the widget's option in the database to have the passed Keyring token ID.
	 * This is so the user doesn't have to click the "Save" button when we want to set it.
	 *
	 * @param int $token_id A Keyring token ID.
	 */
	public function update_widget_token_id( $token_id ) {
		$widget_options = $this->get_settings();

		if ( ! is_array( $widget_options[ $this->number ] ) )
			$widget_options[ $this->number ] = $this->defaults;

		$widget_options[ $this->number ]['token_id'] = (int) $token_id;

		$this->save_settings( $widget_options );
	}

	/**
	 * Updates the widget's option in the database to have the passed Keyring token ID.
	 *
	 * Sends a json success or error response.
	 */
	public function ajax_update_widget_token_id() {
		check_ajax_referer( 'instagram-widget-save-token', 'savetoken' );

		$token_id = (int) $_POST['keyring_id'];

		// From Atomic sites, this check is done via the api: wpcom/v2/instagram/<token_id>.
		// https://wpcom.trac.automattic.com/browser/trunk/wp-content/rest-api-plugins/endpoints/sites-instagram.php?rev=204654#L88
		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			$token = Keyring::init()->get_token_store()->get_token( array( 'type' => 'access', 'id' => $token_id ) );
			if ( get_current_user_id() !== (int) $token->meta['user_id'] ) {
				return wp_send_json_error( array( 'message' => 'not_authorized' ), 403 );
			}
		}

		$this->update_widget_token_id( $token_id );
		$this->update_widget_token_legacy_status( false );

		return wp_send_json_success( null, 200 );
	}

	/**
	 * Updates the widget's option in the database to show if it is for legacy API or not.
	 *
	 * @param bool $is_legacy_token A flag to indicate if a token is for the legacy Instagram API.
	 */
	public function update_widget_token_legacy_status( $is_legacy_token ) {
		$widget_options = $this->get_settings();

		if ( ! is_array( $widget_options[ $this->number ] ) )
			$widget_options[ $this->number ] = $this->defaults;

		$widget_options[ $this->number ]['is_legacy_token'] = $is_legacy_token;
		$this->save_settings( $widget_options );

		return $is_legacy_token;
	}

	private function is_legacy_token( $token_id ) {
		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			$token = Keyring::init()->get_token_store()->get_token( array( 'type' => 'access', 'id' => $token_id ) );
			return $token && 'instagram' === $token->name;
		}

		$site = Jetpack_Options::get_option( 'id' );
		$path = sprintf( '/sites/%s/instagram/%s/check-legacy', $site, $token_id );
		$result = $this->wpcom_json_api_request_as_blog( $path, 2, array( 'headers' => array( 'content-type' => 'application/json' ) ), null, 'wpcom' );
		$response_code = wp_remote_retrieve_response_code( $result );
		if ( 200 !== $response_code ) {
			do_action( 'wpcomsh_log', 'Instagram widget: failed to verify if token is for legacy API: API returned code ' . $response_code );
			return 'ERROR';
		}
		$body = json_decode( $result['body'] );
		return 'true' === $body->legacy;
	}

	/**
	 * Validates the widget instance's token ID and then uses it to fetch images from Instagram.
	 * It then caches the result which it will use on subsequent pageviews.
	 * Keyring is not loaded nor is a remote request is not made in the event of a cache hit.
	 *
	 * @param array $instance A widget $instance, as passed to a widget's widget() method.
	 * @return string|array A string on error, an array of images on success.
	 */
	public function get_data( $instance ) {
		if ( empty( $instance['token_id'] ) ) {
			do_action( 'wpcomsh_log', 'Instagram widget: failed to get images: no token_id present' );
			return 'ERROR';
		}

		$cache_time = MINUTE_IN_SECONDS;
		$transient_key = implode( '|', array( 'instagram-widget', $instance['token_id'], $instance['count'] ) );
		$cached_images = get_transient( $transient_key );
		if ( $cached_images ) {
			return $cached_images;
		}

		$site = Jetpack_Options::get_option( 'id' );
		$path = sprintf( '/sites/%s/instagram/%s?count=%s', $site, $instance['token_id'], $instance['count'] );
		$result = $this->wpcom_json_api_request_as_blog( $path, 2, array( 'headers' => array( 'content-type' => 'application/json' ) ), null, 'wpcom' );

		$response_code = wp_remote_retrieve_response_code( $result );
		if ( 200 !== $response_code ) {
			do_action( 'wpcomsh_log', 'Instagram widget: failed to get images: API returned code ' . $response_code );
			set_transient( $transient_key, 'ERROR', $cache_time );
			return 'ERROR';
		}

		$data = json_decode( wp_remote_retrieve_body( $result ), true );
		if ( ! isset( $data['images'] ) || ! is_array( $data['images'] ) ) {
			do_action( 'wpcomsh_log', 'Instagram widget: failed to get images: API returned no images; got this instead: ' . json_encode( $data ) );
			set_transient( $transient_key, 'ERROR', $cache_time );
			return 'ERROR';
		}

		$cache_time = 20 * MINUTE_IN_SECONDS;
		set_transient( $transient_key, $data, $cache_time );
		return $data;
	}

	private function wpcom_json_api_request_as_blog( $path, $version = 1, $args = array(), $body = null, $base_api_path = 'rest' ) {
		if ( ! class_exists( 'Automattic\Jetpack\Connection\Client' ) ) {
			return new WP_Error( 'missing_jetpack', 'The `Automattic\Jetpack\Connection\Client` class is missing' );
		}
		$filtered_args = array_intersect_key( $args, array(
			'headers'     => 'array',
			'method'      => 'string',
			'timeout'     => 'int',
			'redirection' => 'int',
			'stream'      => 'boolean',
			'filename'    => 'string',
			'sslverify'   => 'boolean',
		) );
		/**
		 * Determines whether Jetpack can send outbound https requests to the WPCOM api.
		 *
		 * @since 3.6.0
		 *
		 * @param bool $proto Defaults to true.
		 */
		$proto = apply_filters( 'jetpack_can_make_outbound_https', true ) ? 'https' : 'http';
		// unprecedingslashit
		$_path = preg_replace( '/^\//', '', $path );
		// Use GET by default whereas `remote_request` uses POST
		$request_method = ( isset( $filtered_args['method'] ) ) ? $filtered_args['method'] : 'GET';
		$validated_args = array_merge( $filtered_args, array(
			'url'     => sprintf( '%s://%s/%s/v%s/%s', $proto, JETPACK__WPCOM_JSON_API_HOST, $base_api_path, $version, $_path ),
			'blog_id' => (int) Jetpack_Options::get_option( 'id' ),
			'method'  => $request_method,
		) );
		return Client::remote_request( $validated_args, $body );
	}

	/**
	 * Outputs the contents of the widget on the front end.
	 *
	 * If the widget is unconfigured, a configuration message is displayed to users with admin access
	 * and the entire widget is hidden from everyone else to avoid displaying an empty widget.
	 *
	 * @param array $args The sidebar arguments that control the wrapping HTML.
	 * @param array $instance The widget instance (configuration options).
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		$data   = $this->get_data( $instance );
		$images = $data['images'];
		// Don't display anything to non-blog admins if the widgets is unconfigured or API call fails
		if ( ( ! $instance['token_id'] || ! is_array( $images ) ) && ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		echo $args['before_widget'];

		// Always show a title on an unconfigured widget
		if ( ! $instance['token_id'] && empty( $instance['title'] ) )
			$instance['title'] = $this->defaults['title'];

		if ( ! empty( $instance['title'] ) )
			echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];

		if ( $instance['token_id'] && current_user_can( 'edit_theme_options' ) && $this->is_legacy_token( $instance['token_id'] ) ) {
			/* translators: Variable is a formatted date string representing 31 March 2020. */
			echo '<p><em>' . sprintf(
				__( 'In order to continue using this Instagram widget after %s, you must <a href="%s">re-connect</a>.', 'wpcomsh' ),
				date_i18n('l, d F Y', mktime( 12, 0, 0, 3, 31, 2020 ), true ),
				add_query_arg( 'instagram_widget_id', $this->number, admin_url( 'widgets.php' ) )
			) . '</em></p>';
		}

		if ( ! $instance['token_id'] ) {
			echo '<p><em>' . sprintf( __( 'In order to use this Instagram widget, you must <a href="%s">configure it</a> first.', 'wpcomsh' ), add_query_arg( 'instagram_widget_id', $this->number, admin_url( 'widgets.php' ) ) ) . '</em></p>';
		} else {
			if ( ! is_array( $images ) ) {
				echo '<p>' . __( 'There was an error retrieving images from Instagram. An attempt will be remade in a few minutes.', 'wpcomsh' ) . '</p>';
			}
			elseif ( ! $images ) {
				echo '<p>' . __( 'No Instagram images were found.', 'wpcomsh' ) . '</p>';
			} else {

				echo '<div class="' . esc_attr( 'wpcom-instagram-images wpcom-instagram-columns-' . (int) $instance['columns'] ) . '">' . "\n";
				foreach ( $images as $image ) {
					echo '<a href="' . esc_url( $image['link'] ) . '" target="' . esc_attr( apply_filters( 'wpcom_instagram_widget_target', '_self' ) ) . '"><div class="sq-bg-image" style="background-image: url(' . esc_url( set_url_scheme( $image['url'] ) ) . ')"><span class="screen-reader-text">' . esc_attr( $image['title'] ) . '</span></div></a>' . "\n";
				}
				echo "</div>\n";
			}
		}

		echo $args['after_widget'];
	}

	private function get_connect_url() {
		$connect_url = '';

		if ( defined( 'IS_WPCOM' ) && IS_WPCOM && function_exists( 'wpcom_keyring_get_connect_URL' ) ) {
			$connect_url = wpcom_keyring_get_connect_URL( 'instagram-basic-display', 'instagram-widget' );
		} else {
			$jetpack_blog_id = Jetpack_Options::get_option( 'id' );
			$response = Client::wpcom_json_api_request_as_user(
				sprintf( '/sites/%d/external-services', $jetpack_blog_id )
			);

			if ( is_wp_error( $response ) ) {
				do_action( 'wpcomsh_log', 'Instagram widget: failed to connect to API via wpcom api.' );

				return $response;
			}

			$body = json_decode( $response['body'] );
			$connect_url = $body->services->instagram->connect_URL ?? '';
		}

		return $connect_url;
	}

	/**
	 * Outputs the widget configuration form for the widget administration page.
	 * Allows the user to add new Instagram Keyring tokens and more.
	 *
	 * @param array $instance The widget instance (configuration options).
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		// If coming back to the widgets page from an action, expand this widget
		if ( isset( $_GET['instagram_widget_id'] ) && $_GET['instagram_widget_id'] == $this->number ) {
			echo '<script type="text/javascript">jQuery(document).ready(function($){ $(\'.widget[id$="wpcom_instagram_widget-' . esc_js( $this->number ) . '"] .widget-inside\').slideDown(\'fast\'); });</script>';
		}

		// If removing the widget's stored token ID
		if ( $instance['token_id'] && isset( $_GET['instagram_widget_id'] ) && $_GET['instagram_widget_id'] == $this->number && ! empty( $_GET['instagram_widget'] ) && 'remove_token' === $_GET['instagram_widget'] ) {
			if ( empty( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'instagram-widget-remove-token-' . $this->number . '-' . $instance['token_id'] ) ) {
				wp_die( __( 'Missing or invalid security nonce.', 'wpcomsh' ) );
			}

			if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
				Keyring::init()->get_token_store()->delete( array( 'type' => 'access', 'id' => $instance['token_id'] ) );
			} else {
				$site = Jetpack_Options::get_option( 'id' );
				$path = sprintf( '/sites/%s/instagram/%s', $site, $instance['token_id'] );

				$result = $this->wpcom_json_api_request_as_blog( $path, 2, array(
					'headers' => array( 'content-type' => 'application/json' ),
					'method' => 'DELETE'
				), null, 'wpcom' );

				$response_code = wp_remote_retrieve_response_code( $result );

				if ( 200 !== $response_code ) {
					do_action( 'wpcomsh_log', 'Instagram widget: failed to remove keyring token: API returned code ' . $response_code );
					return 'ERROR';
				}
			}

			$instance['token_id'] = $this->defaults['token_id'];

			$this->update_widget_token_id( $instance['token_id'] );
			$this->update_widget_token_legacy_status( false );
		}
		// If a token ID is stored, make sure it's still valid, and if we know if it is a legacy API token or not
		elseif ( $instance['token_id'] ) {
			if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
				$token = Keyring::init()->get_token_store()->get_token( array( 'type' => 'access', 'id' => $instance['token_id'] ) );

				if ( ! $token ) {
					$instance['token_id'] = $this->defaults['token_id'];
				}
			}

			if ( ! isset( $instance['is_legacy_token'] ) || 'ERROR' === $instance['is_legacy_token'] ) {
				$instance['is_legacy_token'] = $this->update_widget_token_legacy_status( $this->is_legacy_token( $instance['token_id'] ) );
			}
			$this->update_widget_token_id( $instance['token_id'] );
		}

		// No connection, or a legacy API token? Display a connection link.
		$is_legacy_token = ( isset( $instance['is_legacy_token'] ) && $instance['is_legacy_token'] === true );

		if ( $is_legacy_token ) {
			echo '<p><strong>' . __( 'Your current connection will stop working on 31 March 2020 due to changes in Instagram\'s service. <br /><br />Please reconnect to Instagram in order to continue using this widget', 'wpcomsh' ) . '</strong></p>';
		}

		if ( is_customize_preview() && ! $instance['token_id'] ) {
			echo '<p>' . __( '<strong>Important: You must first click Save to activate this widget <em>before</em> connecting your account.</strong> After saving the widget, click the button below to connect your Instagram account.', 'wpcomsh' ) . '</p>';
		}

		if ( ! $instance['token_id'] || $is_legacy_token ) {
			?>
			<script type="text/javascript">
				function getScreenCenterSpecs( width, height ) {
					const screenTop = typeof window.screenTop !== 'undefined' ? window.screenTop : window.screenY,
						screenLeft = typeof window.screenLeft !== 'undefined' ? window.screenLeft : window.screenX;

					return [
						'width=' + width,
						'height=' + height,
						'top=' + ( screenTop + window.innerHeight / 2 - height / 2 ),
						'left=' + ( screenLeft + window.innerWidth / 2 - width / 2 ),
					].join();
				};
				function openWindow( button ) {
					// let's just double check that we aren't getting an unknown random domain injected in here somehow
					if (! /^https:\/\/public-api.wordpress.com\/connect\//.test(button.dataset.connecturl) ) {
						return;
					}
					window.open(
						button.dataset.connecturl, //TODO: Check if this needs validation it could be a XSS problem. Check the domain maybe?
						'_blank',
						'toolbar=0,location=0,menubar=0,' + getScreenCenterSpecs( 700, 700 )
					);
					button.innerText = '<?php _e( 'Connecting…', 'wpcomsh' ); ?>';
					button.disabled = true;
					window.onmessage = function( { data } ) {
						if ( !! data.keyring_id ) {
							var payload = {
								action: 'wpcom_instagram_widget_update_widget_token_id',
								savetoken: '<?php echo esc_js( wp_create_nonce( 'instagram-widget-save-token' ) ); ?>',
								keyring_id: data.keyring_id,
							};
							jQuery.post( ajaxurl, payload, function( response ) {
								var widget = jQuery(button).closest('div.widget');
								if ( ! window.wpWidgets ) {
									window.location = '<?php echo esc_js( add_query_arg( array( 'autofocus[panel]' => 'widgets' ), admin_url( 'customize.php' ) ) ); ?>';
								} else {
									wpWidgets.save( widget, 0, 1, 1 );
								}
							} );
						}
					};
				}
			</script>
			<?php
			$connect_url = $this->get_connect_url();
			if ( is_wp_error( $connect_url ) ) {
				echo '<p>' . __( 'Instagram is currently experiencing connectivity issues, please try again later to connect.', 'wpcomsh' ) . '</p>';
				return;
			}
			?>
			<p style="text-align:center"><button class="button-primary" onclick="openWindow(this); return false;" data-connecturl="<?php echo esc_attr( $connect_url ); ?>"><?php echo esc_html( __( 'Connect Instagram Account', 'wpcomsh' ) ); ?></button></p>

			<?php // Include hidden fields for the widget settings before a connection is made, otherwise the default settings are lost after connecting ?>
			<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $instance['count'] ); ?>" />
			<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" value="<?php echo esc_attr( $instance['columns'] ); ?>" />

			<?php
			echo '<p><small>' . sprintf( __( 'Having trouble? Try <a href="%s" target="_blank">logging into the correct account</a> on Instagram.com first.', 'wpcomsh' ), 'https://instagram.com/accounts/login/' ) . '</small></p>';
			return;
		}

		// Connected account
		$page = ( is_customize_preview() ) ? 'customize.php' : 'widgets.php';
		$query_args = array(
			'instagram_widget_id' => $this->number,
			'instagram_widget'    => 'remove_token',
			'nonce'               => wp_create_nonce( 'instagram-widget-remove-token-' . $this->number . '-' . $instance['token_id'] ),
		);

		if ( is_customize_preview() ) {
			$query_args['autofocus[panel]'] = 'widgets';
		}

		$remove_token_id_url = add_query_arg( $query_args, admin_url( $page ) );

		$data = $this->get_data( $instance );
		// TODO: Revisit the error handling. I think we should be using WP_Error here and
		// Jetpack::Client is the legacy check
		if ( 'ERROR' === $data || 'ERROR' === $instance['is_legacy_token'] ) {
			echo '<p>' . __( 'Instagram is currently experiencing connectivity issues, please try again later to connect.', 'wpcomsh' ) . '</p>';
			return;
		}
		echo '<p>' . sprintf( __( '<strong>Connected Instagram Account</strong><br /> <a href="%1$s">%2$s</a> | <a href="%3$s">remove</a>', 'wpcomsh' ), esc_url( 'http://instagram.com/' . $data['external_name'] ), esc_html( $data['external_name'] ), esc_url( $remove_token_id_url ) ) . '</p>';

		// Title
		echo '<p><label><strong>' . __( 'Widget Title', 'wpcomsh' ) . '</strong> <input type="text" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" value="' . esc_attr( $instance['title'] ) . '" class="widefat" /></label></p>';

		// Number of images to show
		echo '<p><label>';
		echo '<strong>' . __( 'Images', 'wpcomsh' ) . '</strong><br />';
		echo __( 'Number to display:', 'wpcomsh' ) . ' ';
		echo '<select name="' . esc_attr( $this->get_field_name( 'count' ) ) . '">';
		for ( $i = 1; $i <= $this->valid_options['max_count']; $i++ ) {
			echo '<option value="' . esc_attr( $i ) . '"' . selected( $i, $instance['count'], false ) . '>' . $i . '</option>';
		}
		echo '</select>';
		echo '</label></p>';

		// Columns
		echo '<p><label>';
		echo '<strong>' . __( 'Layout', 'wpcomsh' ) . '</strong><br />';
		echo __( 'Number of columns:', 'wpcomsh' ) . ' ';
		echo '<select name="' . esc_attr( $this->get_field_name( 'columns' ) ) . '">';
		for ( $i = 1; $i <= $this->valid_options['max_columns']; $i++ ) {
			echo '<option value="' . esc_attr( $i ) . '"' . selected( $i, $instance['columns'], false ) . '>' . $i . '</option>';
		}
		echo '</select>';
		echo '</label></p>';

		echo '<p><small>' . sprintf( __( 'New images may take up to %d minutes to show up on your site.', 'wpcomsh' ), 15 ) . '</small></p>';
	}

	/**
	 * Validates and sanitizes the user-supplied widget options.
	 *
	 * @param array $new_instance The user-supplied values.
	 * @param array $old_instance The existing widget options.
	 * @return array A validated and sanitized version of $new_instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $this->defaults;

		$instance['token_id'] = $old_instance['token_id'];

		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['columns'] = max( 1, min( $this->valid_options['max_columns'], (int) $new_instance['columns'] ) );

		$instance['count'] = max( 1, min( $this->valid_options['max_count'], (int) $new_instance['count'] ) );

		return $instance;
	}
}

add_action( 'widgets_init', function() {
	register_widget( 'WPcom_Instagram_Widget' );
} );
