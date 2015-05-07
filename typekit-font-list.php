<?php
add_filter( 'jetpack_fonts_list_typekit', array( 'Jetpack_Fonts_List_Typekit', 'get_fonts' ) );
add_filter( 'jetpack_fonts_list_typekit_retired', array( 'Jetpack_Fonts_List_Typekit', 'get_retired_font_ids' ) );

class Jetpack_Fonts_List_Typekit {

	public static $retired_font_ids = array(
		'gkmg', // Droid Sans
		'pcpv', // Droid Serif
		'gckq', // Eigerdals
		'gwsq', // FF Brokenscript Web Condensed
		'dbqg', // FF Dax
		'rgzb', // FF Netto
		'sbsp', // FF Prater Block
		'rvnd', // Latpure
		'zsyz', // Liberation Sans
		'lcny', // Liberation Serif
		'rfss', // Orbitron
		'snjm', // Refrigerator Deluxe
		'rtgb', // Ronnia Web
		'hzlv', // Ronnia Web Condensed
		'mkrf', // Snicker
		'qlvb', // Sommet Slab
		'nlwf', // Arimo
		'fbln', // Anonymous Pro
		'jtcj', // Open Sans
		'xcqq', // PT Serif
		'bhyf', // Source Sans Pro
		'jhhw', // Ubuntu
	);

	public static $fonts = array(
		array(
			'displayName' => 'Abril Text',
			'id' => 'gjst',
			'cssName' => 'abril-text',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n4',
			),
			'shortname' => 'Chunk',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'Coquette',
			'id' => 'drjf',
			'cssName' => 'coquette',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n4',
			),
			'shortname' => 'Droid Sans Mono',
			'smallTextLegibility' => true,
		),
		array(
			'displayName' => 'Droid Serif',
			'id' => 'pcpv',
			'cssName' => 'droid-serif',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n7',
			),
			'shortname' => 'FF Brokenscript Condensed',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'FF Dagny Web Pro',
			'id' => 'rlxq',
			'cssName' => 'ff-dagny-web-pro',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n4',
			),
			'shortname' => 'FF Market',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'FF Meta Web Pro',
			'id' => 'brwr',
			'cssName' => 'ff-meta-web-pro',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n4',
			),
			'shortname' => 'FF Netto',
			'smallTextLegibility' => true,
		),
		array(
			'displayName' => 'FF Prater Block Web',
			'id' => 'sbsp',
			'cssName' => 'ff-prater-block-web',
			'variants' => array(
				'n4',
			),
			'shortname' => 'FF Prater Block',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'FF Tisa Web Pro',
			'id' => 'xwmz',
			'cssName' => 'ff-tisa-web-pro',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n4',
			),
			'shortname' => 'Fertigo Pro Script',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'Futura PT',
			'id' => 'ftnk',
			'cssName' => 'futura-pt',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n5',
			),
			'shortname' => 'Inconsolata',
			'smallTextLegibility' => true,
		),
		array(
			'displayName' => 'Jubilat',
			'id' => 'cwfk',
			'cssName' => 'jubilat',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n4',
			),
			'shortname' => 'League Gothic',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'Liberation Sans',
			'id' => 'zsyz',
			'cssName' => 'liberation-sans',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n1',
			),
			'shortname' => 'Raleway',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'Refrigerator Deluxe',
			'id' => 'snjm',
			'cssName' => 'refrigerator-deluxe',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n7',
			),
			'shortname' => 'Snicker',
			'smallTextLegibility' => false,
		),
		array(
			'displayName' => 'Sommet Slab',
			'id' => 'qlvb',
			'cssName' => 'sommet-slab',
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
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
			'variants' => array(
				'n4',
				'i4',
				'n7',
				'i7',
			),
			'shortname' => 'Ubuntu',
			'smallTextLegibility' => true,
		),
	);

	public static function get_fonts() {
		return self::$fonts;
	}

	public static function get_retired_font_ids() {
		return self::$retired_font_ids;
	}

}
