<?php
/**
 * Plugin Name: WordPress.com Site Helper
 * Description: A helper for connecting WordPress.com sites to external host infrastructure.
 * Version: 2.3.5
 * Author: Automattic
 * Author URI: http://automattic.com/
 */

// Increase version number if you change something in wpcomsh.
define( 'WPCOMSH_VERSION', '2.3.5' );

// If true, Typekit fonts will be available in addition to Google fonts
add_filter( 'jetpack_fonts_enable_typekit', '__return_true' );

require_once( 'constants.php' );

require_once( 'footer-credit/footer-credit.php' );
require_once( 'storefront/storefront.php' );
require_once( 'custom-colors/colors.php' );
require_once( 'class.wpcomsh-log.php' );

/**
 * WP.com Widgets (in alphabetical order)
 */
require_once( 'widgets/aboutme.php' );
require_once( 'widgets/author-grid.php' );
require_once( 'widgets/freshly-pressed.php' );
require_once( 'widgets/gravatar.php' );
if ( is_active_widget( false, false, 'wpcom_instagram_widget' ) ) {
	require_once( 'widgets/instagram/instagram.php' );
}
require_once( 'widgets/i-voted.php' );
require_once( 'widgets/music-player.php' );
require_once( 'widgets/posts-i-like.php' );
require_once( 'widgets/recent-comments-widget.php' );
require_once( 'widgets/reservations.php' );
require_once( 'widgets/tlkio/tlkio.php' );
require_once( 'widgets/top-clicks.php' );
require_once( 'widgets/top-rated.php' );
require_once( 'widgets/twitter.php' );

# autoload composer sourced plugins
require_once( 'vendor/autoload.php' );

// REST API
require_once( 'endpoints/rest-api.php' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once WPCOMSH__PLUGIN_DIR_PATH . '/class.cli-commands.php';
}

require_once WPCOMSH__PLUGIN_DIR_PATH . '/class.jetpack-plugin-compatibility.php';

if ( class_exists( 'Jetpack_Plugin_Compatibility' ) ) {
	$wpcomsh_incompatible_plugins = array(
		// "reset" - break/interfere with provided functionality
		'advanced-database-cleaner/advanced-db-cleaner.php' => '"advanced-database-cleaner" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'advanced-reset-wp/advanced-reset-wp.php' => '"advanced-reset-wp" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'advanced-wp-reset/advanced-wp-reset.php' => '"advanced-wp-reset" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'autoptimize/autoptimize.php' => '"autoptimize" has been deactivated, it interferes with site operation and is not supported on WordPress.com.',
		'better-wp-security/better-wp-security.php' => '"better-wp-security" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'duplicator/duplicator.php' => '"duplicator" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'google-captcha/google-captcha.php' => '"google-captcha" has been deactivated, it interferes with site operation and is not supported on WordPress.com.',
		'file-manager-advanced/file_manager_advanced.php' => '"file-manager-advanced" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'file-manager/file-manager.php' => '"file-manager" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'reset-wp/reset-wp.php' => '"reset-wp" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'wordpress-database-reset/wp-reset.php' => '"wordpress-database-reset" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'wordpress-reset/wordpress-reset.php' => '"wordpress-reset" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'wp-automatic/wp-automatic.php' => '"wp-automatic" has been deactivated, it interferes with site operation and is not supported on WordPress.com.',
		'wp-clone-by-wp-academy/wpclone.php' => '"wp-clone-by-wp-academy" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'wp-file-manager/file-folder-manager.php' => '"wp-file-manager" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'wp-prefix-changer/index.php' => '"wp-prefix-changer" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'wp-reset/wp-reset.php' => '"wp-reset" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'wpmu-database-reset/wpmu-database-reset.php' => '"wpmu-database-reset" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',
		'z-inventory-manager/z-inventory-manager.php' => '"z-inventory-manager" has been deactivated, it deletes data necessary to manage your site and is not supported on WordPress.com.',

		// backup
		'backup-wd/backup-wd.php' => '"backup-wd" has been deactivated, WordPress.com handles managing your site backups for you.',
		'backupwordpress/backupwordpress.php' => '"backupwordpress" has been deactivated, WordPress.com handles managing your site backups for you.',
		'backwpup/backwpup.php' => '"backwpup" has been deactivated, WordPress.com handles managing your site backups for you.',
		'updraftplus/updraftplus.php' => '"updraftplus" has been deactivated, WordPress.com handles managing your site backups for you.',
		'wp-db-backup/wp-db-backup.php' => '"wp-db-backup" has been deactivated, WordPress.com handles managing your site backups for you.',

		// caching
		'comet-cache/comet-cache.php' => '"comet-cache" has been deactivated, WordPress.com automatically handles caching for your site.',
		'hyper-cache/plugin.php' => '"hyper-cache" has been deactivated, WordPress.com automatically handles caching for your site.',
		'quick-cache/quick-cache.php' => '"quick-cache" has been deactivated, WordPress.com automatically handles caching for your site.',
		'w3-total-cache/w3-total-cache.php' => '"w3-total-cache" has been deactivated, WordPress.com automatically handles caching for your site.',
		'wp-cache/wp-cache.php' => '"wp-cache" has been deactivated, WordPress.com automatically handles caching for your site.',
		'wp-fastest-cache/wpFastestCache.php' => '"wp-fastest-cache" has been deactivated, WordPress.com automatically handles caching for your site.',
		'wp-rocket/wp-rocket.php' => '"wp-rocket" has been deactivated, WordPress.com automatically handles caching for your site.',
		'wp-super-cache/wp-cache.php' => '"wp-super-cache" has been deactivated, WordPress.com automatically handles caching for your site.',

		// sql heavy
		'another-wordpress-classifieds-plugin/awpcp.php' => '"another-wordpress-classifieds-plugin" has been deactivated, it is known to cause severe database performance issues and is not supported.',
		'native-ads-adnow/adnow-widget.php' => '"native-ads-now" has been deactivated, it is known to cause severe database performance issues and is not supported.',
		'page-visit-counter/page_visit_counter.php' => '"page-visit-counter" has been deactivated, plugins that insert or update the database on page load can cause severe performance issues for your site and are not supported.',
		'post-views-counter/post-views-counter.php' => '"post-views-counter" has been deactivated, plugins that insert or update the database on page load can cause severe performance issues for your site and are not supported.',
		'tokenad/token-ad.php' => '"tokenad" has been deactivated, it is known to cause severe database performance issues and is not supported.',
		'top-10/top-10.php' => '"top-10" has been deactivated, it is known to cause severe database performance issues and is not supported.',
		'wordpress-popular-posts/wordpress-popular-posts.php' => '"wordpress-popular-posts" has been deactivated, it is known to cause severe database performance issues and is not supported.',
		'wp-cerber/wp-cerber.php' => '"wp-cerber" has been deactivated, it is known to cause severe database performance issues and is not supported.',
		'wp-postviews/wp-postviews.php' => '"wp-postviews" has been deactivated, plugins that insert or update the database on page load can cause severe performance issues for your site and are not supported.',
		'wp-statistics/wp-statistics.php' => '"wp-statistics" has been deactivated, plugins that insert or update the database on page load can cause severe performance issues for your site and are not supported.',
		'wp-ulike/wp-ulike.php' => '"wp-ulike" has been deactivated, plugins that insert or update the database on page load can cause severe performance issues for your site and are not supported.',

		// security
		'wordfence/wordfence.php' => '"wordfence" has been deactivated, "security" related plugins may break your site or cause performance issues for your site and are not supported on WordPress.com.',

		// spam
		'e-mail-broadcasting/e-mail-broadcasting.php' => '"e-mail-broadcasting" has been deactivated, plugins that support sending e-mails in bulk are not supported on WordPress.com.',
		'mailit/mailit.php' => '"mailit"has been deactivated, plugins that support sending e-mails in bulk are not supported on WordPress.com.',
		'send-email-from-admin/send-email-from-admin.php' => '"send-email-from-admin" has been deactivated, plugins that support sending e-mails in bulk are not supported on WordPress.com.',

		// cloning/staging
		'wp-staging/wp-staging.php' => 'wp-staging plugins delete data necessary to manage your site and are not supported on WordPress.com. wp-staging has been deactivated.',

		// misc
		'automatic-video-posts' => '"automatic-video-posts" is not supported on WordPress.com.',
		'bwp-minify/bwp-minify.php' => '"bwp-minify" is not supported on WordPress.com.',
		'nginx-helper/nginx-helper.php' => '"nginx-helper" is not supported on WordPress.com.',
		'patron-button-and-widgets-by-codebard/index.php' => '"patron-button-and-widgets-by-codebard" is not supported on WordPress.com.',
		'porn-embed/Porn-Embed.php' => '"porn-embed" is not supported on WordPress.com.',
		'video-importer/video-importer.php' => '"video-importer" is not supported on WordPress.com.',
		'woozone/plugin.php' => '"woozone" is not supported on WordPress.com.',
		'wp-cleanfix/index.php' => '"wp-cleanfix" is not supported on WordPress.com.',
	);
	new Jetpack_Plugin_Compatibility( $wpcomsh_incompatible_plugins );
}

function wpcomsh_remove_vaultpress_wpadmin_notices() {
	if ( ! class_exists( 'VaultPress' ) ) {
		return;
	}

	$vp_instance = VaultPress::init();

	remove_action( 'user_admin_notices', array( $vp_instance, 'activated_notice' ) );
	remove_action( 'admin_notices', array( $vp_instance, 'activated_notice' ) );

	remove_action( 'user_admin_notices', array( $vp_instance, 'connect_notice' ) );
	remove_action( 'admin_notices', array( $vp_instance, 'connect_notice' ) );

	remove_action( 'user_admin_notices', array( $vp_instance, 'error_notice' ) );
	remove_action( 'admin_notices', array( $vp_instance, 'error_notice' ) );
}
add_action(
	'admin_head',
	'wpcomsh_remove_vaultpress_wpadmin_notices',
	11 // Priority 11 so it runs after VaultPress `admin_head` hook
);

function wpcomsh_register_plugins_action_links() {
	// Hide WPComSH "Deactivate" and "Edit" links on WP Admin Plugins page
	add_filter(
		'plugin_action_links_' . plugin_basename( WPCOMSH__PLUGIN_FILE ),
		'wpcomsh_hide_wpcomsh_plugin_links'
	);

	// If Jetpack is loaded, hide its "Deactivate" and "Edit" links on WP Admin Plugins page
	if ( defined( 'JETPACK__PLUGIN_FILE' ) ) {
		$jetpack_basename = plugin_basename( JETPACK__PLUGIN_FILE );

		add_filter(
			'plugin_action_links_' . $jetpack_basename,
			'wpcomsh_hide_plugin_deactivate_edit_links'
		);
		add_action(
			'after_plugin_row_' . $jetpack_basename,
			'wpcomsh_show_plugin_auto_managed_notice',
		10, 2 );
	}

	$vaultpress_plugin_file = WP_PLUGIN_DIR . '/vaultpress/vaultpress.php';

	// If VaultPress is loaded, hide its "Deactivate" and "Edit" links on WP Admin Plugins page
	if ( file_exists( $vaultpress_plugin_file ) ) {
		$vaultpress_basename = plugin_basename( $vaultpress_plugin_file );

		add_filter(
			'plugin_action_links_' . $vaultpress_basename,
			'wpcomsh_hide_plugin_deactivate_edit_links'
		);

		add_action(
			"after_plugin_row_" . $vaultpress_basename,
			"wpcomsh_show_plugin_auto_managed_notice",
		10, 2 );
	}

	// If Akismet is loaded, hide its "Deactivate" and "Edit" links on WP Admin Plugins page
	if ( defined( 'AKISMET__PLUGIN_DIR' ) ) {
		$akismet_basename = plugin_basename( AKISMET__PLUGIN_DIR . '/akismet.php' );

		add_filter(
			'plugin_action_links_' . $akismet_basename,
			'wpcomsh_hide_plugin_deactivate_edit_links'
		);

		add_action(
			"after_plugin_row_" . $akismet_basename,
			"wpcomsh_show_plugin_auto_managed_notice",
			10, 2 );
	}
}

add_action( 'admin_init', 'wpcomsh_register_plugins_action_links' );

function wpcomsh_hide_wpcomsh_plugin_links() {
	return array();
}

function wpcomsh_hide_plugin_deactivate_edit_links( $links ) {
	if ( ! is_array( $links ) ) {
		return array();
	}

	unset( $links['deactivate'] );
	unset( $links['edit'] );

	return $links;
}

function wpcomsh_show_plugin_auto_managed_notice( $file, $plugin_data ) {
	$plugin_name = 'The plugin';

	if ( array_key_exists( 'Name', $plugin_data ) ) {
		$plugin_name = $plugin_data['Name'];
	}

	$message = sprintf( __( '%s is automatically managed for you.' ), $plugin_name );

	echo
		'<tr class="plugin-update-tr active">' .
			'<td colspan="3" class="plugin-update colspanchange">' .
				'<div class="notice inline notice-warning notice-alt">' .
					"<p>{$message}</p>" .
				'</div>' .
			'</td>' .
		'</tr>';
}

function wpcomsh_register_theme_hooks() {
	add_filter(
		'jetpack_wpcom_theme_skip_download',
		'wpcomsh_jetpack_wpcom_theme_skip_download',
		10,
		2
	);

	add_filter(
		'jetpack_wpcom_theme_delete',
		'wpcomsh_jetpack_wpcom_theme_delete',
		10,
		2
	);
}
add_action( 'init', 'wpcomsh_register_theme_hooks' );

/**
 * Filters a user's capabilities depending on specific context and/or privilege.
 *
 * @param array  $required_caps Returns the user's actual capabilities.
 * @param string $cap           Capability name.
 * @return array Primitive caps.
 */
function wpcomsh_map_caps( $required_caps, $cap ) {
	require_once( 'functions.php' );

	switch ( $cap ) {

		case 'edit_themes':
			// Disallow managing themes for WPCom free plan.
			if ( ! wpcomsh_can_manage_themes() ) {
				$required_caps[] = 'do_not_allow';

				break;
			}

			// Disallow editing 3rd party WPCom premium themes.
			$theme = wp_get_theme();
			if ( wpcomsh_is_wpcom_premium_theme( $theme->get_stylesheet() )
			     && 'Automattic' !== $theme->get( 'Author' ) ) {
				$required_caps[] = 'do_not_allow';
			}
			break;

		// Disallow managing plugins for WPCom free plan.
		case 'activate_plugins':
		case 'install_plugins':
		case 'edit_plugins':
		case 'delete_plugins':
		case 'upload_plugins':
		case 'update_plugins':
			if ( ! wpcomsh_can_manage_plugins() ) {
				$required_caps[] = 'do_not_allow';
			}

			break;

		// Disallow managing themes for WPCom free plan.
		case 'switch_themes':
		case 'install_themes':
		case 'update_themes':
		case 'delete_themes':
		case 'upload_themes':
			if ( ! wpcomsh_can_manage_themes() ) {
				$required_caps[] = 'do_not_allow';
			}

			break;
	}

	return $required_caps;
}
add_action( 'map_meta_cap', 'wpcomsh_map_caps', 10, 2 );

function wpcomsh_remove_theme_delete_button( $prepared_themes ) {
	require_once( 'functions.php' );

	foreach ( $prepared_themes as $theme_slug => $theme_data ) {
		if ( wpcomsh_is_wpcom_theme( $theme_slug ) || wpcomsh_is_symlinked_storefront_theme( $theme_slug ) ) {
			$prepared_themes[ $theme_slug ]['actions']['delete'] = '';
		}
	}

	return $prepared_themes;
}
add_filter( 'wp_prepare_themes_for_js', 'wpcomsh_remove_theme_delete_button' );


function wpcomsh_jetpack_wpcom_theme_skip_download( $result, $theme_slug ) {
	require_once( 'functions.php' );

	$theme_type = wpcomsh_get_wpcom_theme_type( $theme_slug );

	// If we are dealing with a non WPCom theme, don't interfere.
	if ( ! $theme_type ) {
		return false;
	}

	if ( wpcomsh_is_theme_symlinked( $theme_slug ) ) {
		error_log( "WPComSH: WPCom theme with slug: {$theme_slug} is already installed/symlinked." );

		return new WP_Error(
			'wpcom_theme_already_installed',
			'The WPCom theme is already installed/symlinked.'
		);
	}

	$was_theme_symlinked = wpcomsh_symlink_theme( $theme_slug, $theme_type );

	if ( is_wp_error( $was_theme_symlinked ) ) {
		return $was_theme_symlinked;
	}

	wpcomsh_delete_theme_cache( $theme_slug );

	// Skip the theme installation as we've "installed" (symlinked) it manually above.
	add_filter(
		'jetpack_wpcom_theme_install',
		function() use( $was_theme_symlinked ) {
			return $was_theme_symlinked;
		},
		10,
		2
	);

	// If the installed WPCom theme is a child theme, we need to symlink its parent theme
	// as well.
	if ( wpcomsh_is_wpcom_child_theme( $theme_slug ) ) {
		$was_parent_theme_symlinked = wpcomsh_symlink_parent_theme( $theme_slug );

		if ( ! $was_parent_theme_symlinked ) {
			return new WP_Error(
				'wpcom_theme_installation_falied',
				"Can't install specified WPCom theme. Check error log for more details."
			);
		}
	}

	return true;
}

function wpcomsh_jetpack_wpcom_theme_delete( $result, $theme_slug ) {
	require_once( 'functions.php' );

	if (
		! wpcomsh_is_wpcom_theme( $theme_slug ) ||
		! wpcomsh_is_theme_symlinked( $theme_slug )
	) {
		return false;
	}

	// If a theme is a child theme, we first need to unsymlink the parent theme.
	if ( wpcomsh_is_wpcom_child_theme( $theme_slug ) ) {
		$was_parent_theme_unsymlinked = wpcomsh_delete_symlinked_parent_theme( $theme_slug );

		if ( ! $was_parent_theme_unsymlinked ) {
			return new WP_Error(
				'wpcom_theme_deletion_falied',
				"Can't delete specified WPCom theme. Check error log for more details."
			);
		}
	}

	$was_theme_unsymlinked = wpcomsh_delete_symlinked_theme( $theme_slug );

	return $was_theme_unsymlinked;
}

function wpcomsh_remove_dashboard_widgets() {
	remove_meta_box( 'pressable_dashboard_widget', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'wpcomsh_remove_dashboard_widgets' );


/**
 * Filter attachment URLs if the 'wpcom_attachment_subdomain' option is present.
 * Local image files will be unaffected, as they will pass a file_exists check.
 * Files stored remotely will be filtered to have the correct URL.
 *
 * Once the files have been transferred, the 'wpcom_attachment_subdomain' will
 * be removed, preventing further stats.
 *
 * @param string $url The attachment URL
 * @param int $post_id The post id
 * @return string The filtered attachment URL
 */
function wpcomsh_get_attachment_url( $url, $post_id ) {
	$attachment_subdomain = get_option( 'wpcom_attachment_subdomain' );
	if ( $attachment_subdomain ) {
		if ( $file = get_post_meta( $post_id, '_wp_attached_file', true ) ) {
			$local_file = WP_CONTENT_DIR . '/uploads/' . $file;
			if ( ! file_exists( $local_file ) ) {
				return esc_url( 'https://' . $attachment_subdomain . '/' . $file );
			}
		}
	}
	return $url;
}
add_filter( 'wp_get_attachment_url', 'wpcomsh_get_attachment_url', 11, 2 );

/**
 * If a user is logged in to WordPress.com, log him in automatically to wp-login
 */
add_filter( 'jetpack_sso_bypass_login_forward_wpcom', '__return_true' );

/**
 * When a request is made to Jetpack Themes API, we need to distinguish between a WP.com theme
 * and a WP.org theme in the response. This function adds/modifies the `theme_uri` field of a theme
 * changing it to `https://wordpress.com/theme/{$theme_slug}` if a theme is a WP.com one.
 *
 * @param array $formatted_theme Array containing the Jetpack Themes API data to be sent to wpcom
 *
 * @return array The original or modified theme info array
 */
function wpcomsh_add_wpcom_suffix_to_theme_endpoint_response( $formatted_theme ) {
	if ( ! array_key_exists( 'id', $formatted_theme ) ) {
		return $formatted_theme;
	}

	$theme_slug = $formatted_theme['id'];
	$is_storefront = 'storefront' === $theme_slug;

	if ( wpcomsh_is_theme_symlinked( $theme_slug ) && ! $is_storefront ) {
		$formatted_theme['theme_uri'] = "https://wordpress.com/theme/{$theme_slug}";
	}

	return $formatted_theme;
}
add_filter( 'jetpack_format_theme_details', 'wpcomsh_add_wpcom_suffix_to_theme_endpoint_response' );

function wpcomsh_disable_bulk_plugin_deactivation( $actions ) {
	if ( array_key_exists( 'deactivate-selected', $actions ) ) {
		unset( $actions['deactivate-selected'] );
	}

	return $actions;
}
add_filter( 'bulk_actions-plugins', 'wpcomsh_disable_bulk_plugin_deactivation' );

function wpcomsh_admin_enqueue_style() {
	wp_enqueue_style(
		'wpcomsh-admin-style',
		plugins_url( 'assets/admin-style.css', __FILE__ ),
		null,
		WPCOMSH_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'wpcomsh_admin_enqueue_style', 999 );

function wpcomsh_allow_custom_wp_options( $options ) {
	// For storing AT options.
	$options[] = 'at_options';
	$options[] = 'at_options_logging_on';
	$options[] = 'jetpack_fonts';
	$options[] = 'site_logo';
	$options[] = 'footercredit';

	return $options;
}
add_filter( 'jetpack_options_whitelist', 'wpcomsh_allow_custom_wp_options' );


/**
 * Load a WordPress.com theme compat file, if it exists.
 */
function wpcomsh_load_theme_compat_file() {
	if ( ( ! defined( 'WP_INSTALLING' ) || 'wp-activate.php' === $GLOBALS['pagenow'] ) ) {
		// Many wpcom.php files call $themecolors directly. Ease the pain.
		global $themecolors;

		$template_path   = get_template_directory();
		$stylesheet_path = get_stylesheet_directory();
		$file            = '/inc/wpcom.php';

		// Look also in /includes as alternate location, since premium theme partners may use that convention.
		if ( ! file_exists( $template_path . $file ) && ! file_exists( $stylesheet_path . $file ) ) {
			$file = '/includes/wpcom.php';
		}

		// Include 'em. Child themes first, just like core.
		if ( $template_path !== $stylesheet_path && file_exists( $stylesheet_path . $file ) ) {
			include_once( $stylesheet_path . $file );
		}

		if ( file_exists( $template_path . $file ) ) {
			include_once( $template_path . $file );
		}
	}
}

// Hook early so that after_setup_theme can still be used at default priority.
add_action( 'after_setup_theme', 'wpcomsh_load_theme_compat_file', 0 );

/**
 * Filter plugins_url for when __FILE__ is outside of WP_CONTENT_DIR
 */
function wpcomsh_symlinked_plugins_url( $url, $path, $plugin ) {
	$url = preg_replace(
		'#((?<!/)/[^/]+)*/wp-content/plugins/wordpress/plugins/wpcomsh/([^/]+)/#',
		'/wp-content/mu-plugins/wpcomsh/',
		$url
	);
	return $url;
}

add_filter( 'plugins_url', 'wpcomsh_symlinked_plugins_url', 0, 3 );

function wpcomsh_activate_masterbar_module() {
	if ( ! defined( 'JETPACK__VERSION' ) ) {
		return;
	}

	// Masterbar was introduced in Jetpack 4.8
	if ( version_compare( JETPACK__VERSION, '4.8', '<' ) ) {
		return;
	}

	if ( ! Jetpack::is_module_active( 'masterbar' ) ) {
		Jetpack::activate_module( 'masterbar', false, false );
	}
}
add_action( 'init', 'wpcomsh_activate_masterbar_module', 0, 0 );

function require_lib( $slug ) {
	if ( !preg_match( '|^[a-z0-9/_.-]+$|i', $slug ) ) {
		return;
	}

	// these are whitelisted libraries that Jetpack has
	$in_jetpack = array(
		'tonesque',
		'class.color'
	);

	// hand off to `jetpack_require_lib`, if possible.
	if ( in_array( $slug, $in_jetpack ) && function_exists( 'jetpack_require_lib' ) ) {
		return jetpack_require_lib( $slug );
	}


	$basename = basename( $slug );

	$lib_dir = __DIR__ . '/lib';

	/**
	 * Filter the location of the library directory.
	 *
	 * @since 2.5.0
	 *
	 * @param str $lib_dir Path to the library directory.
	 */
	$lib_dir = apply_filters( 'require_lib_dir', $lib_dir );

	$choices = array(
		"$lib_dir/$slug.php",
		"$lib_dir/$slug/0-load.php",
		"$lib_dir/$slug/$basename.php",
	);
	foreach( $choices as $file_name ) {
		if ( is_readable( $file_name ) ) {
			require_once $file_name;
			return;
		}
	}
}

/**
 * Provides a fallback Google Maps API key when otherwise not configured by the
 * user. This is subject to a usage quota.
 *
 * @see https://a8cio.wordpress.com/2016/06/24/google-requiring-api-key-for-all-maps-products/
 *
 * @param string $api_key Google Maps API key
 * @return string Google Maps API key
 */
function wpcomsh_google_maps_api_key( $api_key ) {
	// We don't want to add the fallback API key to the Geocode API call; we'll get "referer restrictions" errors.
	// That call is only made when saving the form, to validate the address.
	if ( is_admin() ) {
		return $api_key;
	}

	// Fall back to the dotcom API key if the user has not set their own.
	return ( empty( $api_key ) ) ? 'AIzaSyCq4vWNv6eCGe2uvhPRGWQlv80IQp8dwTE' : $api_key;
}
add_filter( 'jetpack_google_maps_api_key', 'wpcomsh_google_maps_api_key' );


/**
 * Provides a fallback mofile that uses wpcom locale slugs instead of wporg locale slugs
 * This is needed for WP.COM themes that have their translations bundled with the theme.
 *
 * @see https://parequests.wordpress.com/2017/05/03/theme-translations/
 *
 * @param string $mofile .mo language file being loaded by load_textdomain()
 * @return string $mofile same or alternate mo file
 */
function wpcomsh_wporg_to_wpcom_locale_mo_file( $mofile ) {
	if ( file_exists( $mofile ) ) {
		return $mofile;
	}

	if ( ! class_exists( 'GP_Locales' ) ) {
		if ( ! defined( 'JETPACK__GLOTPRESS_LOCALES_PATH' ) || ! file_exists( JETPACK__GLOTPRESS_LOCALES_PATH ) ) {
			return $mofile;
		}

		require JETPACK__GLOTPRESS_LOCALES_PATH;
	}

	$locale_slug = basename( $mofile, '.mo' );
	$actual_locale_slug = $locale_slug;

	// These locales are not in our GP_Locales file, so rewrite them.
	$locale_mappings = array(
		'de_DE_formal' => 'de_DE', // formal German
	);

	if ( isset( $locale_mappings[ $locale_slug ] ) ) {
		$locale_slug = $locale_mappings[ $locale_slug ];
	}

	$locale_object = GP_Locales::by_field( 'wp_locale', $locale_slug );
	if ( ! $locale_object ) {
		return $mofile;
	}

	$locale_slug = $locale_object->slug;

	// For these languages we have a different slug than WordPress.org.
	$locale_mappings = array(
		'nb' => 'no', // Norwegian Bokmål
	);

	if ( isset( $locale_mappings[ $locale_slug ] ) ) {
		$locale_slug = $locale_mappings[ $locale_slug ];
	}

	$mofile = preg_replace( '/' . preg_quote( $actual_locale_slug ) . '\.mo$/', $locale_slug . '.mo', $mofile );
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'wpcomsh_wporg_to_wpcom_locale_mo_file', 9999 );

/**
 * Links were removed in 3.5 core, but we've kept them active on dotcom.
 * This will expose both the Links section, and the widget.
 */
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/*
 * We have some instances where `track_number` of an audio attachment is `??0` and shows up as type string.
 * However the problem is, that if post has nested property attachments with this track_number, `json_serialize` fails silently.
 * Of course, this should be fixed during audio upload, but we need this fix until we can clean this up properly.
 * More detail here: https://github.com/Automattic/automated-transfer/issues/235
 */
function wpcomsh_jetpack_api_fix_unserializable_track_number( $exif_data ) {
	if ( isset( $exif_data[ 'track_number' ] ) ) {
		$exif_data[ 'track_number' ] = intval( $exif_data[ 'track_number' ] );
	}
	return $exif_data;
}
add_filter( 'wp_get_attachment_metadata', 'wpcomsh_jetpack_api_fix_unserializable_track_number' );

// Initialize REST API
add_action( 'rest_api_init', 'wpcomsh_rest_api_init' );
