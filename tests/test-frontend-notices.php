<?php
/**
 * Frontend Notices Test file.
 *
 * @package wpcomsh
 */

/**
 * Class FrontendNoticesTest.
 */
class FrontendNoticesTest extends WP_UnitTestCase {

	/**
	 * Test no purchase data available.
	 */
	public function test_no_purchases_data() {
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array() ) );
		$this->assertFalse( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
	}

	/**
	 * Test notice not show when plan is not expired.
	 */
	public function test_should_not_show_notice_when_plan_not_expired() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->add( new DateInterval( 'P7D' ) )->format( 'c' ),
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );

		$this->assertFalse( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
	}

	/**
	 * Test notice not show when plan is expired for only 2 days.
	 */
	public function test_should_not_show_notice_when_plan_expired_2_days() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P2D' ) )->format( 'c' ),
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );

		$this->assertFalse( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
	}

	/**
	 * Test notice is show when plan is expired for 3 days.
	 */
	public function test_should_show_notice_when_plan_expired_3_days_logged_out() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P3D' ) )->format( 'c' ),
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		wp_set_current_user( 0 );
		$this->assertTrue( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
	}

	/**
	 * Test notice should not show when the user is logged in.
	 */
	public function test_should_show_notice_when_user_logged_in() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P3D' ) )->format( 'c' ),
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		// Logged out.
		wp_set_current_user( 0 );
		$this->assertTrue( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
		// Logged in.
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$this->assertTrue( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
	}

	/**
	 * Test notice should not show when locale is not english.
	 */
	public function test_should_not_show_notice_when_locale_not_en() {
		add_filter(
			'locale',
			function() {
				return 'fr_FR';
			}
		);
		$this->assertFalse( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
	}

	/**
	 * Test notice is show when plan is more than 6 days.
	 */
	public function test_should_show_notice_when_plan_expired_6_days() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P6D' ) )->format( 'c' ),
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );

		$this->assertTrue( WPCOMSH_Frontend_Notices::should_display_expired_plan_notice() );
	}

	/**
	 * Test should display gift notice when plan expired.
	 */
	public function test_should_display_gift_notice_when_plan_expired() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P1D' ) )->format( 'c' ),
			'auto_renew'   => false,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertTrue( $gifting_banner->should_display_expiring_plan_notice() );
	}

	/**
	 * Test should display gift notice when monthly plan expiring under 5 days.
	 */
	public function test_should_display_gift_notice_when_monthly_plan_expiring_under_5_days() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle-monthly',
			'expiry_date'  => ( new DateTime() )->add( new DateInterval( 'P4D' ) )->format( 'c' ),
			'auto_renew'   => false,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertTrue( $gifting_banner->should_display_expiring_plan_notice() );
	}

	/**
	 * Test should not display gift notice when monthly plan expiring more than 5 days out.
	 */
	public function test_should_not_display_gift_notice_when_monthly_plan_expiring_more_than_5_days_out() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle-monthly',
			'expiry_date'  => ( new DateTime() )->add( new DateInterval( 'P6D' ) )->format( 'c' ),
			'auto_renew'   => false,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertFalse( $gifting_banner->should_display_expiring_plan_notice() );
	}

	/**
	 * Test should display gift notice when annual plan expiring under 54 days.
	 */
	public function test_should_display_gift_notice_when_annual_plan_expiring_under_54_days() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->add( new DateInterval( 'P53D' ) )->format( 'c' ),
			'auto_renew'   => false,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertTrue( $gifting_banner->should_display_expiring_plan_notice() );
	}

	/**
	 * Test should not display gift notice when annual plan expiring more than 54 days out.
	 */
	public function test_should_not_display_gift_notice_when_annual_plan_expiring_more_than_54_days_out() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->add( new DateInterval( 'P55D' ) )->format( 'c' ),
			'auto_renew'   => false,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertFalse( $gifting_banner->should_display_expiring_plan_notice() );
	}

	/**
	 * Test should not display gift notice when plan unknown.
	 */
	public function test_should_not_display_gift_notice_when_plan_unknown() {
		$business_plan_purchase = array(
			'product_slug' => 'unknown',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P1D' ) )->format( 'c' ),
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertFalse( $gifting_banner->should_display_expiring_plan_notice() );
	}

	/**
	 * Test should not display gift notice when plan auto renew on and gifting toggle has not been set.
	 */
	public function test_should_not_display_gift_notice_when_plan_auto_renew_on() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P1D' ) )->format( 'c' ),
			'auto_renew'   => true,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertFalse( $gifting_banner->should_display_expiring_plan_notice() );
	}

	/**
	 * Test should display gift notice when gifting toggle it true.
	 */
	public function test_should_display_gift_notice_when_option_true() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P1D' ) )->format( 'c' ),
			'auto_renew'   => true,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		add_option( 'wpcom_gifting_subscription', true );
		$this->assertTrue( $gifting_banner->should_display_expiring_plan_notice() );
		delete_option( 'wpcom_gifting_subscription' );
	}

	/**
	 * Test should not display gift notice when gifting option disabled.
	 */
	public function test_should_not_display_gift_notice_when_gifting_option_disabled() {
		$business_plan_purchase = array(
			'product_slug' => 'business-bundle',
			'expiry_date'  => ( new DateTime() )->sub( new DateInterval( 'P1D' ) )->format( 'c' ),
			'auto_renew'   => false,
		);
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array( $business_plan_purchase ) ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertTrue( $gifting_banner->should_display_expiring_plan_notice() );

		add_option( 'wpcom_gifting_subscription', false );
		$this->assertFalse( $gifting_banner->should_display_expiring_plan_notice() );
		delete_option( 'wpcom_gifting_subscription' );
	}

	/**
	 * Test should not display gift notice when no purchases.
	 */
	public function test_should_not_display_gift_notice_when_no_purchases() {
		Atomic_Persistent_Data::set( 'WPCOM_PURCHASES', wp_json_encode( array() ) );
		$gifting_banner = new Gifting_Banner();
		$this->assertFalse( $gifting_banner->should_display_expiring_plan_notice() );
	}
}
