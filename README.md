# fork
my fork function with example

/**
*example
*/
$masParamz = array(
	'0' => 'id1',
	'1' => 'id2',
	'2' => 'id3',
	'3' => 'id4',
	'4' => 'id5',
	'5' => 'id6',
	'6' => 'id7',
	'7' => 'id8',
	'8' => 'id9',
	'9' => 'id10',
	'10' => 'id11',
	'11' => 'id12',
	'12' => 'id13',
	'13' => 'id14',
	'14' => 'id15',
	'15' => 'id16',
	'16' => 'id17',
	'17' => 'id18',
);
doParallel('doTest', $masParamz, 'DOP_PARAMETR');
echo "---\n";
