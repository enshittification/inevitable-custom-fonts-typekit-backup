<?php
/**
 * Twitter widget class
 * Display the latest N tweets from a Twitter screenname as a widget
 * Customize screenname, maximum number of tweets displayed, show or hide @replies, and text displayed between tweet text and a timestamp
 */

/**
 * Register the widget for use in Appearance -> Widgets
 */
add_action( 'widgets_init', 'jetpack_twitter_widget_init' );

function jetpack_twitter_widget_init() {
	// This widget is retired; don't load it for users that don't already have an active instance.
	if ( ! is_active_widget( false, false, 'twitter' ) ) {
		return;
	}
	register_widget( 'Jetpack_Widget_Twitter' );
}

class Jetpack_Widget_Twitter extends WP_Widget {
	function __construct() {
		parent::__construct(
			'twitter',
			apply_filters( 'jetpack_widget_name', __( 'Twitter', 'wpcomsh' ) ),
			array(
				'classname'   => 'widget_twitter',
				'description' => __( 'Display your Tweets from Twitter', 'wpcomsh' ),
			)
		);
		add_action( 'wp_head', array( $this, 'style' ) );
	}

	function style() {
		?>
<style type="text/css">
.widget_twitter li {
	word-wrap: break-word;
}
</style>
		<?php
	}

	function widget( $args, $instance ) {
		$account = isset( $instance['account'] ) ? trim( urlencode( $instance['account'] ) ) : '';

		if ( empty( $account ) ) {
			if ( current_user_can( 'edit_theme_options' ) ) {
				echo $args['before_widget'];
				echo '<p>' . sprintf( __( 'Please configure your Twitter username for the <a href="%s">Twitter Widget</a>.', 'wpcomsh' ), admin_url( 'widgets.php' ) ) . '</p>';
				echo $args['after_widget'];
			}

			return;
		}

		$title = apply_filters( 'widget_title', $instance['title'] );

		if ( empty( $title ) ) {
			$title = __( 'Twitter Updates', 'wpcomsh' );
		}

		$show = absint( $instance['show'] );  // # of Updates to show

		// Twitter paginates at 200 max tweets. update() should not have accepted greater than 20
		if ( $show > 200 ) {
			$show = 200;
		}

		$hidereplies      = (bool) $instance['hidereplies'];
		$hidepublicized   = (bool) $instance['hidepublicized'];
		$include_retweets = (bool) $instance['includeretweets'];
		$follow_button    = (bool) $instance['followbutton'];

		echo "{$args['before_widget']}{$args['before_title']}<a href='" . esc_url( "http://twitter.com/{$account}" ) . "'>" . esc_html( $title ) . "</a>{$args['after_title']}";

		$tweets = $this->fetch_twitter_user_stream( $account, $hidereplies, $show, $include_retweets );

		if ( isset( $tweets['error'] ) && ( isset( $tweets['data']['tweets'] ) && ! empty( $tweets['data']['tweets'] ) ) ) {
			$tweets['error'] = '';
		}

		if ( empty( $tweets['error'] ) ) {
			$before_tweet     = isset( $instance['beforetweet'] ) ? stripslashes( wp_filter_post_kses( $instance['beforetweet'] ) ) : '';
			$before_timesince = ( isset( $instance['beforetimesince'] ) && ! empty( $instance['beforetimesince'] ) ) ? esc_html( $instance['beforetimesince'] ) : ' ';

			$this->display_tweets( $show, $tweets['data']['tweets'], $hidepublicized, $before_tweet, $before_timesince, $account );

			if ( $follow_button ) {
				$this->display_follow_button( $account );
			}

			add_action( 'wp_footer', array( $this, 'twitter_widget_script' ) );
		} else {
			echo $tweets['error'];
		}

		echo $args['after_widget'];

		/** This action is documented in modules/widgets/gravatar-profile.php */
		do_action( 'jetpack_stats_extra', 'widget_view', 'twitter' );
	}

	function display_tweets( $show, $tweets, $hidepublicized, $before_tweet, $before_timesince, $account ) {
		$tweets_out = 0;
		?>
		<ul class='tweets'>
		<?php

		foreach ( (array) $tweets as $tweet ) {
			if ( $tweets_out >= $show ) {
				break;
			}

			if ( empty( $tweet['text'] ) ) {
				continue;
			}

			if ( $hidepublicized && false !== strstr( $tweet['source'], 'http://publicize.wp.com/' ) ) {
				continue;
			}

			$tweet['text'] = esc_html( $tweet['text'] ); // escape here so that Twitter handles in Tweets don't get mangled
			$tweet         = $this->expand_tco_links( $tweet );
			$tweet['text'] = make_clickable( $tweet['text'] );

			/*
			* Create links from plain text based on Twitter patterns
			* @link http://github.com/mzsanford/twitter-text-rb/blob/master/lib/regex.rb Official Twitter regex
			*/
			$tweet['text'] = preg_replace_callback( '/(^|[^0-9A-Z&\/]+)(#|\xef\xbc\x83)([0-9A-Z_]*[A-Z_]+[a-z0-9_\xc0-\xd6\xd8-\xf6\xf8\xff]*)/iu', array( $this, '_jetpack_widget_twitter_hashtag' ), $tweet['text'] );
			$tweet['text'] = preg_replace_callback( '/([^a-zA-Z0-9_]|^)([@\xef\xbc\xa0]+)([a-zA-Z0-9_]{1,20})(\/[a-zA-Z][a-zA-Z0-9\x80-\xff-]{0,79})?/u', array( $this, '_jetpack_widget_twitter_username' ), $tweet['text'] );

			if ( isset( $tweet['id_str'] ) ) {
				$tweet_id = urlencode( $tweet['id_str'] );
			} else {
				$tweet_id = urlencode( $tweet['id'] );
			}

			?>

			<li>
			<?php echo esc_attr( $before_tweet ) . $tweet['text'] . esc_attr( $before_timesince ); ?>
				<a href="<?php echo esc_url( "http://twitter.com/{$account}/statuses/{$tweet_id}" ); ?>" class="timesince"><?php echo esc_html( str_replace( ' ', '&nbsp;', $this->time_since( strtotime( $tweet['created_at'] ) ) ) ); ?>&nbsp;ago</a>
			</li>

			<?php

			unset( $tweet_it );
			$tweets_out++;
		}

		?>
		</ul>
		<?php
	}

	function display_follow_button( $account ) {
		global $themecolors;

		$follow_colors       = isset( $themecolors['link'] ) ? " data-link-color='#{$themecolors['link']}'" : '';
		$follow_colors      .= isset( $themecolors['text'] ) ? " data-text-color='#{$themecolors['text']}'" : '';
		$follow_button_attrs = " class='twitter-follow-button' data-show-count='false'{$follow_colors}";

		?>
		<a href="http://twitter.com/<?php echo esc_attr( $account ); ?>" <?php echo $follow_button_attrs; ?>>Follow @<?php echo esc_attr( $account ); ?></a>
		<?php
	}

	function expand_tco_links( $tweet ) {
		if ( ! empty( $tweet['entities']['urls'] ) && is_array( $tweet['entities']['urls'] ) ) {
			foreach ( $tweet['entities']['urls'] as $entity_url ) {
				if ( ! empty( $entity_url['expanded_url'] ) ) {
					$tweet['text'] = str_replace(
						$entity_url['url'],
						'<a href="' . esc_url( $entity_url['expanded_url'] ) . '"> ' . esc_html( $entity_url['display_url'] ) . '</a>',
						$tweet['text']
					);
				}
			}
		}

		return $tweet;
	}

	/**
	 * Query the WordPress.com REST API using the blog token
	 *
	 * Based on `Jetpack_Client::wpcom_json_api_request_as_blog()` but modified
	 * to allow working with v2 wpcom endpoints via the $base_api_path param.
	 *
	 * See https://github.com/Automattic/jetpack/pull/6813
	 *
	 * Also allows any HTTP verb (not just GET and POST).
	 *
	 * @param string $path
	 * @param string $version
	 * @param array  $args
	 * @param string $body
	 * @param string $base_api_path Determines the base API path for jetpack requests; defaults to 'rest'
	 * @return array|WP_Error $response Data.
	 */
	public static function wpcom_json_api_request_as_blog( $path, $version = 1, $args = array(), $body = null, $base_api_path = 'rest' ) {
		$filtered_args = array_intersect_key(
			$args,
			array(
				'headers'     => 'array',
				'method'      => 'string',
				'timeout'     => 'int',
				'redirection' => 'int',
				'stream'      => 'boolean',
				'filename'    => 'string',
				'sslverify'   => 'boolean',
			)
		);

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

		$validated_args = array_merge(
			$filtered_args,
			array(
				'url'     => sprintf( '%s://%s/%s/v%s/%s', $proto, JETPACK__WPCOM_JSON_API_HOST, $base_api_path, $version, $_path ),
				'blog_id' => (int) Jetpack_Options::get_option( 'id' ),
				'method'  => $request_method,
			)
		);

		return Jetpack_Client::remote_request( $validated_args, $body );
	}

	function fetch_twitter_user_stream( $account, $hidereplies, $show, $include_retweets ) {
		$tweets    = get_transient( 'widget-twitter-' . $this->number );
		$the_error = get_transient( 'widget-twitter-error-' . $this->number );

		if ( $tweets ) {
			return array(
				'data'  => $tweets,
				'error' => $the_error,
			);
		}

		$params = array(
			'screen_name'      => $account, // Twitter account name
			'trim_user'        => true,     // only basic user data (slims the result)
			'include_entities' => true,
		);

		// If combined with $count, $exclude_replies only filters that number of tweets (not all tweets up to the requested count).
		if ( $hidereplies ) {
			$params['exclude_replies'] = true;
		} else {
			$params['count'] = $show;
		}

		$params['include_rts'] = $include_retweets;

		$url           = esc_url_raw( '/twitter?' . http_build_query( $params ), [ 'http', 'https' ] );
		$response      = self::wpcom_json_api_request_as_blog(
			$url,
			2,
			[
				'method'  => 'GET',
				'headers' => [ 'content-type' => 'application/json' ],
			],
			null,
			'wpcom'
		);
		$response_code = ( isset( $response['response']['code'] ) ) ? $response['response']['code'] : false;
		if ( is_wp_error( $response ) || 200 !== $response_code || ! isset( $response['body'] ) ) {
			do_action( 'jetpack_bump_stats_extras', 'twitter_widget', "request-fail-{$response_code}" );
			$tweets             = get_transient( 'widget-twitter-backup-' . $this->number );
			$the_error          = '<p>' . esc_html__( 'Error: Twitter did not respond. Please wait a few minutes and refresh this page.', 'wpcomsh' ) . '</p>';
			$tweet_cache_expire = 300;
			set_transient( 'widget-twitter-' . $this->number, $tweets, $tweet_cache_expire );
			set_transient( 'widget-twitter-error-' . $this->number, $the_error, $tweet_cache_expire );
			return array(
				'data'  => $tweets,
				'error' => $the_error,
			);
		}

		switch ( $response_code ) {
			case 200: // process tweets and display
				$tweets = json_decode( $response['body'], true );

				if ( ! is_array( $tweets ) || isset( $tweets['error'] ) ) {
					do_action( 'jetpack_bump_stats_extras', 'twitter_widget', "request-fail-{$response_code}-bad-data" );
					$the_error          = '<p>' . esc_html__( 'Error: Twitter did not respond. Please wait a few minutes and refresh this page.', 'wpcomsh' ) . '</p>';
					$tweet_cache_expire = 300;
					break;
				} else {
					set_transient( 'widget-twitter-backup-' . $this->number, $tweets, 86400 ); // A one day backup in case there is trouble talking to Twitter
				}

				do_action( 'jetpack_bump_stats_extras', 'twitter_widget', 'request-success' );
				$tweet_cache_expire = 900;
				break;
			case 401: // display private stream notice
				do_action( 'jetpack_bump_stats_extras', 'twitter_widget', "request-fail-{$response_code}" );

				$tweets             = array();
				$the_error          = '<p>' . sprintf( esc_html__( 'Error: Please make sure the Twitter account is %1$spublic%2$s.', 'wpcomsh' ), '<a href="http://support.twitter.com/forums/10711/entries/14016">', '</a>' ) . '</p>';
				$tweet_cache_expire = 300;
				break;
			default:  // display an error message
				do_action( 'jetpack_bump_stats_extras', 'twitter_widget', "request-fail-{$response_code}" );

				$tweets             = get_transient( 'widget-twitter-backup-' . $this->number );
				$the_error          = '<p>' . esc_html__( 'Error: Twitter did not respond. Please wait a few minutes and refresh this page.', 'wpcomsh' ) . '</p>';
				$tweet_cache_expire = 300;
				break;
		}

		set_transient( 'widget-twitter-' . $this->number, $tweets, $tweet_cache_expire );
		set_transient( 'widget-twitter-error-' . $this->number, $the_error, $tweet_cache_expire );

		return array(
			'data'  => $tweets,
			'error' => $the_error,
		);
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['account'] = trim( wp_kses( $new_instance['account'], array() ) );
		$instance['account'] = str_replace( array( 'http://twitter.com/', '/', '@', '#!' ), array( '', '', '', '' ), $instance['account'] );

		$instance['title']           = wp_kses( $new_instance['title'], array() );
		$instance['show']            = absint( $new_instance['show'] );
		$instance['hidereplies']     = isset( $new_instance['hidereplies'] );
		$instance['hidepublicized']  = isset( $new_instance['hidepublicized'] );
		$instance['includeretweets'] = isset( $new_instance['includeretweets'] );

		if ( $old_instance['followbutton'] != $new_instance['followbutton'] ) {
			if ( $new_instance['followbutton'] ) {
				do_action( 'jetpack_bump_stats_extras', 'twitter_widget', 'follow_button_enabled' );
			} else {
				do_action( 'jetpack_bump_stats_extras', 'twitter_widget', 'follow_button_disabled' );
			}
		}

		$instance['followbutton']    = ! isset( $new_instance['followbutton'] ) ? 0 : 1;
		$instance['beforetimesince'] = $new_instance['beforetimesince'];

		delete_transient( 'widget-twitter-' . $this->number );
		delete_transient( 'widget-twitter-error-' . $this->number );

		return $instance;
	}

	function form( $instance ) {
		// Defaults
		$account          = isset( $instance['account'] ) ? wp_kses( $instance['account'], array() ) : '';
		$title            = isset( $instance['title'] ) ? $instance['title'] : '';
		$show             = isset( $instance['show'] ) ? absint( $instance['show'] ) : 5;
		$show             = ( $show < 1 || 20 < $show ) ? 5 : $show;
		$hidereplies      = isset( $instance['hidereplies'] ) && ! empty( $instance['hidereplies'] ) ? (bool) $instance['hidereplies'] : false;
		$hidepublicized   = isset( $instance['hidepublicized'] ) && ! empty( $instance['hidepublicized'] ) ? (bool) $instance['hidepublicized'] : false;
		$include_retweets = isset( $instance['includeretweets'] ) && ! empty( $instance['includeretweets'] ) ? (bool) $instance['includeretweets'] : false;
		$follow_button    = isset( $instance['followbutton'] ) && ! empty( $instance['followbutton'] ) ? 1 : 0;
		$before_timesince = isset( $instance['beforetimesince'] ) && ! empty( $instance['beforetimesince'] ) ? esc_attr( $instance['beforetimesince'] ) : '';

		/**
		* Urge people to upgrade to the new twitter timeline widget. While this widget will continue working, we may totally remove it in the future.
		 *
		* @see http://socialp2.wordpress.com/2013/04/19/following-on-from-justins-previous-post-its-time/
		*/
		?>
		<p><em><?php printf( __( "Please switch to the 'Twitter Timeline' widget. This widget will be going away in the future and the new widget allows for more customization.", 'wpcomsh' ) ); ?></em></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Title:', 'wpcomsh' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'account' ); ?>">
				<?php esc_html_e( 'Twitter username:', 'wpcomsh' ); ?> <a href="http://support.wordpress.com/widgets/twitter-widget/#twitter-username" target="_blank">( ? )</a>
				<input class="widefat" id="<?php echo $this->get_field_id( 'account' ); ?>" name="<?php echo $this->get_field_name( 'account' ); ?>" type="text" value="<?php echo esc_attr( $account ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show' ); ?>">
				<?php esc_html_e( 'Maximum number of Tweets to show:', 'wpcomsh' ); ?>
				<select id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>">
					<?php
					for ( $i = 1; $i <= 20; ++$i ) :
						?>
						<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $show, $i ); ?>><?php echo esc_attr( $i ); ?></option>
						<?php
					endfor;
					?>
				</select>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'hidereplies' ); ?>">
				<input id="<?php echo $this->get_field_id( 'hidereplies' ); ?>" class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'hidereplies' ); ?>" <?php checked( $hidereplies, true ); ?> />
				<?php esc_html_e( 'Hide replies', 'wpcomsh' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'hidepublicized' ); ?>">
				<input id="<?php echo $this->get_field_id( 'hidepublicized' ); ?>" class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'hidepublicized' ); ?>" <?php checked( $hidepublicized, true ); ?> />
				<?php esc_html_e( 'Hide Tweets pushed by Publicize', 'wpcomsh' ); ?>
			 </label>
		 </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'includeretweets' ); ?>">
				<input id="<?php echo $this->get_field_id( 'includeretweets' ); ?>" class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'includeretweets' ); ?>" <?php checked( $include_retweets, true ); ?> />
				<?php esc_html_e( 'Include retweets', 'wpcomsh' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'followbutton' ); ?>">
				<input id="<?php echo $this->get_field_id( 'followbutton' ); ?>" class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'followbutton' ); ?>" <?php checked( $follow_button, 1 ); ?> />
				<?php esc_html_e( 'Display Follow Button', 'wpcomsh' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'beforetimesince' ); ?>">
				<?php esc_html_e( 'Text to display between Tweet and timestamp:', 'wpcomsh' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'beforetimesince' ); ?>" name="<?php echo $this->get_field_name( 'beforetimesince' ); ?>" type="text" value="<?php echo esc_attr( $before_timesince ); ?>" />
			</label>
		</p>

		<?php
	}

	function time_since( $original, $do_more = 0 ) {
		// array of time period chunks
		$chunks = array(
			array( 60 * 60 * 24 * 365, 'year' ),
			array( 60 * 60 * 24 * 30, 'month' ),
			array( 60 * 60 * 24 * 7, 'week' ),
			array( 60 * 60 * 24, 'day' ),
			array( 60 * 60, 'hour' ),
			array( 60, 'minute' ),
		);

		$today = time();
		$since = $today - $original;

		for ( $i = 0, $j = count( $chunks ); $i < $j; $i++ ) {
			$seconds = $chunks[ $i ][0];
			$name    = $chunks[ $i ][1];

			if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
				break;
			}
		}

		$print = ( $count == 1 ) ? '1 ' . $name : "$count {$name}s";

		if ( $i + 1 < $j ) {
			$seconds2 = $chunks[ $i + 1 ][0];
			$name2    = $chunks[ $i + 1 ][1];

			// add second item if it's greater than 0
			if ( ( ( $count2 = floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) != 0 ) && $do_more ) {
				$print .= ( $count2 == 1 ) ? ', 1 ' . $name2 : ", $count2 {$name2}s";
			}
		}
		return $print;
	}

	/**
	 * Link a Twitter user mentioned in the tweet text to the user's page on Twitter.
	 *
	 * @param array $matches regex match
	 * @return string Tweet text with inserted @user link
	 */
	function _jetpack_widget_twitter_username( array $matches ) {
		// $matches has already been through wp_specialchars
		return "$matches[1]@<a href='" . esc_url( 'http://twitter.com/' . urlencode( $matches[3] ) ) . "'>$matches[3]</a>";
	}

	/**
	 * Link a Twitter hashtag with a search results page on Twitter.com
	 *
	 * @param array $matches regex match
	 * @return string Tweet text with inserted #hashtag link
	 */
	function _jetpack_widget_twitter_hashtag( array $matches ) {
		// $matches has already been through wp_specialchars
		return "$matches[1]<a href='" . esc_url( 'http://twitter.com/search?q=%23' . urlencode( $matches[3] ) ) . "'>#$matches[3]</a>";
	}

	function twitter_widget_script() {
		if ( ! wp_script_is( 'twitter-widgets', 'registered' ) ) {
			if ( is_ssl() ) {
				$twitter_widget_js = 'https://platform.twitter.com/widgets.js';
			} else {
				$twitter_widget_js = 'http://platform.twitter.com/widgets.js';
			}
			wp_register_script( 'twitter-widgets', $twitter_widget_js, array(), '20111117', true );
			wp_print_scripts( 'twitter-widgets' );
		}
	}
}
