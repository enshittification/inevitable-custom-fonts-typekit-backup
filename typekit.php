<?php
class Jetpack_Typekit_Font_Provider extends Jetpack_Font_Provider {

	protected $api_base = 'https://typekit.com/api/v1/json';

	public $id = 'typekit';

	/**
	 * Constructor
	 * @param Jetpack_Fonts $custom_fonts Manager instance
	 */
	public function __construct( Jetpack_Fonts $custom_fonts ) {
		parent::__construct( $custom_fonts );
		// add_filter( 'jetpack_fonts_whitelist_' . $this->id, array( $this, 'default_whitelist' ), 9 );
	}

	public function body_font_whitelist(){
		return array(
		);
	}

	public function headings_font_whitelist(){
		return array(
		);
	}

	public function default_whitelist( $whitelist ) {
		$all_fonts = array_merge ( $this->body_font_whitelist(), $this->headings_font_whitelist() );
		return $all_fonts;
	}

	/**
	 * Retrieve fonts from the API
	 * @return array List of fonts
	 */
	public function retrieve_fonts() {
		return array(
			array(
				'displayName' => 'Abril Text',
				'id' => 'gjst',
				'cssName' => 'abril-text',
				'variations' => array(
					'n4',
					'i4',
					'n6',
					'i6',
					'n7',
					'i7',
					'n8',
					'i8',
				),
				'shortname' => 'Abril Text',
				'smallTextLegibility' => true
			),
			array(
				'displayName' => 'Adelle Web',
				'id' => 'gmsj',
				'cssName' => 'adelle-web',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Adelle',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Ambroise STD',
				'id' => 'sskw',
				'cssName' => 'ambroise-std',
				'variations' => array(
					'n4',
					'n7',
				),
				'shortname' => 'Ambroise',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Anonymous Pro',
				'id' => 'fbln',
				'cssName' => 'anonymous-pro',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Anonymous Pro',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Arimo',
				'id' => 'nlwf',
				'cssName' => 'arimo',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Arimo',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Arvo',
				'id' => 'tsyb',
				'cssName' => 'arvo',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Arvo',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Brandon Grotesque',
				'id' => 'yvxn',
				'cssName' => 'brandon-grotesque',
				'variations' => array(
					'n1',
					'i1',
					'n3',
					'i3',
					'n4',
					'i4',
					'n5',
					'i5',
					'n7',
					'i7',
					'n9',
					'i9',
				),
				'shortname' => 'Brandon Grotesque',
				'smallTextLegibility' => false
			),
			array(
				'displayName' => 'Bree Web',
				'id' => 'ymzk',
				'cssName' => 'bree-web',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Bree',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Calluna',
				'id' => 'vybr',
				'cssName' => 'calluna',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Calluna',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Calluna Sans',
				'id' => 'wgzc',
				'cssName' => 'calluna-sans',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Calluna Sans',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Chaparral Pro',
				'id' => 'hrpf',
				'cssName' => 'chaparral-pro',
				'variations' => array(
					'n3',
					'i3',
					'n4',
					'i4',
					'n6',
					'i6',
					'n7',
					'i7',
				),
				'shortname' => 'Chaparral Pro',
				'smallTextLegibility' => true
			),
			array(
				'displayName' => 'Chunk',
				'id' => 'klcb',
				'cssName' => 'chunk',
				'variations' => array(
					'n4',
				),
				'shortname' => 'Chunk',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Coquette',
				'id' => 'drjf',
				'cssName' => 'coquette',
				'variations' => array(
					'n4',
					'n7',
				),
				'shortname' => 'Coquette',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Droid Sans',
				'id' => 'gkmg',
				'cssName' => 'droid-sans',
				'variations' => array(
					'n4',
					'n7',
				),
				'shortname' => 'Droid Sans',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Droid Sans Mono',
				'id' => 'vqgt',
				'cssName' => 'droid-sans-mono',
				'variations' => array(
					'n4',
				),
				'shortname' => 'Droid Sans Mono',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Droid Serif',
				'id' => 'pcpv',
				'cssName' => 'droid-serif',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Droid Serif',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Eigerdals',
				'id' => 'gckq',
				'cssName' => 'eigerdals',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Eigerdals',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FF Basic Gothic Web Pro',
				'id' => 'snqb',
				'cssName' => 'ff-basic-gothic-web-pro',
				'variations' => array(
					'n3',
					'i3',
					'n7',
					'i7',
				),
				'shortname' => 'FF Basic Gothic',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FF Brokenscript Web Condensed',
				'id' => 'gwsq',
				'cssName' => 'ff-brokenscript-web-condensed',
				'variations' => array(
					'n7',
				),
				'shortname' => 'FF Brokenscript Condensed',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'FF Dagny Web Pro',
				'id' => 'rlxq',
				'cssName' => 'ff-dagny-web-pro',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'FF Dagny',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FF Dax Web Pro',
				'id' => 'dbqg',
				'cssName' => 'ff-dax-web-pro',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'FF Dax',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FF Market Web',
				'id' => 'fytf',
				'cssName' => 'ff-market-web',
				'variations' => array(
					'n4',
				),
				'shortname' => 'FF Market',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'FF Meta Web Pro',
				'id' => 'brwr',
				'cssName' => 'ff-meta-web-pro',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'FF Meta',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FF Meta Serif Web Pro',
				'id' => 'rrtc',
				'cssName' => 'ff-meta-serif-web-pro',
				'variations' => array(
					'n5',
					'i5',
					'n7',
					'i7',
				),
				'shortname' => 'FF Meta Serif',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FF Netto Web',
				'id' => 'rgzb',
				'cssName' => 'ff-netto-web',
				'variations' => array(
					'n4',
				),
				'shortname' => 'FF Netto',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FF Prater Block Web',
				'id' => 'sbsp',
				'cssName' => 'ff-prater-block-web',
				'variations' => array(
					'n4',
				),
				'shortname' => 'FF Prater Block',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'FF Tisa Web Pro',
				'id' => 'xwmz',
				'cssName' => 'ff-tisa-web-pro',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'FF Tisa',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'FacitWeb',
				'id' => 'ttyp',
				'cssName' => 'facitweb',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'FacitWeb',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Fertigo Pro',
				'id' => 'pzyv',
				'cssName' => 'fertigo-pro',
				'variations' => array(
					'n4',
					'i4',
				),
				'shortname' => 'Fertigo Pro',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Fertigo Pro Script',
				'id' => 'twbx',
				'cssName' => 'fertigo-pro-script',
				'variations' => array(
					'n4',
				),
				'shortname' => 'Fertigo Pro Script',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Futura PT',
				'id' => 'ftnk',
				'cssName' => 'futura-pt',
				'variations' => array(
					'n3',
					'i3',
					'n4',
					'i4',
					'n5',
					'i5',
					'n7',
					'i7',
					'n8',
					'i8',
				),
				'shortname' => 'Futura PT',
				'smallTextLegibility' => false
			),
			array(
				'displayName' => 'Herb Condensed',
				'id' => 'lmgn',
				'cssName' => 'herb-condensed',
				'variations' => array(
					'n4',
					'n7',
				),
				'shortname' => 'Herb Condensed',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Inconsolata',
				'id' => 'gmvz',
				'cssName' => 'inconsolata',
				'variations' => array(
					'n5',
				),
				'shortname' => 'Inconsolata',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Jubilat',
				'id' => 'cwfk',
				'cssName' => 'jubilat',
				'variations' => array(
					'n1',
					'i1',
					'n2',
					'i2',
					'n4',
					'i4',
					'n5',
					'i5',
					'n6',
					'i6',
					'n7',
					'i7',
					'n9',
					'i9',
				),
				'shortname' => 'Jubilat',
				'smallTextLegibility' => true
			),
			array(
				'displayName' => 'Kaffeesatz',
				'id' => 'jgfl',
				'cssName' => 'kaffeesatz',
				'variations' => array(
					'n4',
					'n7',
				),
				'shortname' => 'Kaffeesatz',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'LFT Etica Display Web',
				'id' => 'vyvm',
				'cssName' => 'lft-etica-display-web',
				'variations' => array(
					'n2',
					'n9',
				),
				'shortname' => 'LFT Etica Display',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'LTC Bodoni 175',
				'id' => 'mrnw',
				'cssName' => 'ltc-bodoni-175',
				'variations' => array(
					'n4',
					'i4',
				),
				'shortname' => 'LTC Bodoni 175',
				'smallTextLegibility' => false
			),
			array(
				'displayName' => 'Lapture',
				'id' => 'rvnd',
				'cssName' => 'lapture',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Lapture',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Le Monde Journal STD',
				'id' => 'mvgb',
				'cssName' => 'le-monde-journal-std',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Le Monde Journal',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Le Monde Sans STD',
				'id' => 'rshz',
				'cssName' => 'le-monde-sans-std',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Le Monde Sans',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'League Gothic',
				'id' => 'kmpm',
				'cssName' => 'league-gothic',
				'variations' => array(
					'n4',
				),
				'shortname' => 'League Gothic',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Liberation Sans',
				'id' => 'zsyz',
				'cssName' => 'liberation-sans',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Liberation Sans',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Liberation Serif',
				'id' => 'lcny',
				'cssName' => 'liberation-serif',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Liberation Serif',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Minion Pro',
				'id' => 'nljb',
				'cssName' => 'minion-pro',
				'variations' => array(
					'n4',
					'i4',
					'n5',
					'i5',
					'n6',
					'i6',
					'n7',
					'i7',
				),
				'shortname' => 'Minion Pro',
				'smallTextLegibility' => true
			),
			array(
				'displayName' => 'Museo',
				'id' => 'htrh',
				'cssName' => 'museo',
				'variations' => array(
					'n3',
					'i3',
					'n7',
					'i7',
				),
				'shortname' => 'Museo',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Museo Sans',
				'id' => 'ycvr',
				'cssName' => 'museo-sans',
				'variations' => array(
					'n3',
					'i3',
					'n7',
					'i7',
				),
				'shortname' => 'Museo Sans',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Museo Slab',
				'id' => 'llxb',
				'cssName' => 'museo-slab',
				'variations' => array(
					'n3',
					'i3',
					'n7',
					'i7',
				),
				'shortname' => 'Museo Slab',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Omnes Pro',
				'id' => 'mpmb',
				'cssName' => 'omnes-pro',
				'variations' => array(
					'n1',
					'i1',
					'n2',
					'i2',
					'n3',
					'i3',
					'n4',
					'i4',
					'n5',
					'i5',
					'n6',
					'i6',
					'n7',
					'i7',
					'n9',
					'i9',
				),
				'shortname' => 'Omnes Pro',
				'smallTextLegibility' => false
			),
			array(
				'displayName' => 'Open Sans',
				'id' => 'jtcj',
				'cssName' => 'open-sans',
				'variations' => array(
					'n3',
					'i3',
					'n4',
					'i4',
					'n6',
					'i6',
					'n7',
					'i7',
					'n8',
					'i8',
				),
				'shortname' => 'Open Sans',
				'smallTextLegibility' => true
			),
			array(
				'displayName' => 'Orbitron',
				'id' => 'rfss',
				'cssName' => 'orbitron',
				'variations' => array(
					'n5',
					'n7',
				),
				'shortname' => 'Orbitron',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'PT Serif',
				'id' => 'xcqq',
				'cssName' => 'pt-serif',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'PT Serif',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Proxima Nova',
				'id' => 'vcsm',
				'cssName' => 'proxima-nova',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Proxima Nova',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Puritan',
				'id' => 'ccqc',
				'cssName' => 'puritan',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Puritan',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Raleway',
				'id' => 'nqdy',
				'cssName' => 'raleway',
				'variations' => array(
					'n1',
				),
				'shortname' => 'Raleway',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Refrigerator Deluxe',
				'id' => 'snjm',
				'cssName' => 'refrigerator-deluxe',
				'variations' => array(
					'n4',
					'n7',
				),
				'shortname' => 'Refrigerator Deluxe',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Ronnia Web',
				'id' => 'rtgb',
				'cssName' => 'ronnia-web',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Ronnia',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Ronnia Web Condensed',
				'id' => 'hzlv',
				'cssName' => 'ronnia-web-condensed',
				'variations' => array(
					'n4',
					'n7',
				),
				'shortname' => 'Ronnia Condensed',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Skolar Web',
				'id' => 'wbmp',
				'cssName' => 'skolar-web',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Skolar',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Snicker',
				'id' => 'mkrf',
				'cssName' => 'snicker',
				'variations' => array(
					'n7',
				),
				'shortname' => 'Snicker',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Sommet Slab',
				'id' => 'qlvb',
				'cssName' => 'sommet-slab',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Sommet Slab',
				'smallTextLegibility' => false,
			),
			array(
				'displayName' => 'Source Sans Pro',
				'id' => 'bhyf',
				'cssName' => 'source-sans-pro',
				'variations' => array(
					'n2',
					'i2',
					'n3',
					'i3',
					'n4',
					'i4',
					'n6',
					'i6',
					'n7',
					'i7',
					'n9',
					'i9',
				),
				'shortname' => 'Source Sans Pro',
				'smallTextLegibility' => true
			),
			array(
				'displayName' => 'Sorts Mill Goudy',
				'id' => 'yrwy',
				'cssName' => 'sorts-mill-goudy',
				'variations' => array(
					'n5',
					'i5',
				),
				'shortname' => 'Sorts Mill Goudy',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Tekton Pro',
				'id' => 'fkjd',
				'cssName' => 'tekton-pro',
				'variations' => array(
					'n3',
					'i3',
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Tekton Pro',
				'smallTextLegibility' => false
			),
			array(
				'displayName' => 'Tinos',
				'id' => 'plns',
				'cssName' => 'tinos',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Tinos',
				'smallTextLegibility' => true,
			),
			array(
				'displayName' => 'Ubuntu',
				'id' => 'jhhw',
				'cssName' => 'ubuntu',
				'variations' => array(
					'n4',
					'i4',
					'n7',
					'i7',
				),
				'shortname' => 'Ubuntu',
				'smallTextLegibility' => true,
			),
		);
	}

	// TEMP
	public function get_api_key() {
		return '';
	}

	/**
	 * Converts a font from API format to internal format.
	 * @param  array $font API font
	 * @return array       Formatted font
	 */
	public function format_font( $font ) {
		$formatted = array(
			'id'   => urlencode( $font['family'] ),
			'cssName' => $font['family'],
			'displayName' => $font['family'],
			'fvds' => $this->variants_to_fvds( $font['variants'] ),
			'subsets' => $font['subsets'],
			'bodyText' => in_array( urlencode( $font['family'] ), $this->body_font_whitelist() )
		);
		return $formatted;
	}

	/**
	 * Converts API variants to Font Variant Descriptions
	 * @param  array $variants
	 * @return array           FVDs
	 */
	private function variants_to_fvds( $variants ) {
		$fvds = array();
		foreach( $variants as $variant ) {
			array_push( $fvds, $this->variant_to_fvd( $variant ) );
		}
		return $fvds;
	}

	/**
	 * Convert an API variant to a Font Variant Description
	 * @param  string $variant API variant
	 * @return string          FVD
	 */
	private function variant_to_fvd( $variant ) {
		$variant = strtolower( $variant );

		if ( false !== strpos( $variant, 'italic' ) ) {
			$style = 'i';
			$weight = str_replace( 'italic', '', $variant );
		} elseif ( false !== strpos( $variant, 'oblique' ) ) {
			$style = 'o';
			$weight = str_replace( 'oblique', '', $variant );
		} else {
			$style = 'n';
			$weight = $variant;
		}

		if ( 'regular' === $weight || 'normal' === $weight || '' === $weight ) {
			$weight = '400';
		}
		$weight = substr( $weight, 0, 1 );
		return $style . $weight;
	}

	/**
	 * Adds an appropriate Typekit Fonts stylesheet to the page. Will not be called
	 * with an empty array.
	 * @param  array $fonts List of fonts.
	 * @return void
	 */
	public function render_fonts( $fonts ) {
		//TODO: add css
	}

	/**
	 * Convert FVDs to an API string for variants.
	 * @param  array $fvds FVDs
	 * @return string      API variants
	 */
	private function fvds_to_api_string( $fvds ) {
		$to_return = array();
		foreach( $fvds as $fvd ) {
			switch ( $fvd ) {
				case 'n4':
					$to_return[] = 'r'; break;
				case 'i4':
					$to_return[] = 'i'; break;
				case 'n7':
					$to_return[] = 'b'; break;
				case 'i7':
					$to_return[] = 'bi'; break;
				default:
					$style = substr( $fvd, 1, 1 ) . '00';
					if ( 'i' === substr( $fvd, 0, 1 ) ) {
						$style .= 'i';
					}
					$to_return[] = $style;
			}
		}
		return implode( ',', $to_return );
	}

	/**
	 * The URL for your frontend customizer script. Underscore and jQuery
	 * will be called as dependencies.
	 * @return string
	 */
	public function customizer_frontend_script_url() {

	}

	/**
	 * The URL for your admin customizer script to enable your control.
	 * Backbone, Underscore, and jQuery will be called as dependencies.
	 * @return string
	 */
	public function customizer_admin_script_url() {

	}

	/**
	 * Get all available fonts from Typekit.
	 * @return array A list of fonts.
	 */
	public function get_fonts() {
		if ( $fonts = $this->get_cached_fonts() ) {
			return $fonts;
		}
		$fonts = $this->retrieve_fonts();
		if ( $fonts ) {
			$this->set_cached_fonts( $fonts );
			return $fonts;
		}
		return array();
	}

	/**
	 * Save the kit
	 * @param  array $fonts     A list of fonts.
	 * @return boolean|WP_Error true on success, WP_Error instance on failure.
	 */
	public function save_fonts( $fonts ) {
		return true;
	}
}
