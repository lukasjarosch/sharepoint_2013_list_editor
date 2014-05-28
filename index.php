<?php
include 'vendor/Thybag/SharePointAPI.php';
include 'vendor/Thybag/Auth/SharePointOnlineAuth.php';

// Enable errors
echo '<pre>';
error_reporting(E_ALL);
ini_set('display_errors', '1');

// All available WSDL files
$wsdl_files = array(
	'HR'	=> './wsd_data/hr_lists.wsdl',
	'QM'	=> './wsd_data/qm_lists.wsdl',
);

// Readable list names (not all lists included)
$list_names = array(
	'HR'	=> array(
		'Mitgliederverwaltung'	=> '',
	),
	'QM'	=> array(
		'Dokumente'	=> '{0FB13693-FCD1-4E5A-AD00-4A2908B8A5F6}',
	),
);

$sp = new Thybag\SharePointAPI('lukas.jarosch@gokreaktiv.de', '!Und3rlin3!', $wsdl_files['QM'], 'SPONLINE');

// Example: Read all root documents of QM
$documents = $sp->read($list_names['QM']['Dokumente']);

foreach ($documents as $document) {
	$filename = $document['linkfilename'];
	$last_edited_by = preg_replace('/[^a-zA-Z ]/', '', $document['editor']);
	$modify_date = $document['modified'];

	render_document($filename, $last_edited_by, $modify_date);
}

/**
 * Yeah..it's a function...Deal with it 8)
 * 
 * @param String $filename
 * @param String $last_edited_by
 * @param String $modify_date
 */
function render_document($filename, $last_edited_by, $modify_date) {
	$format = "<b>%s</b>\n\tLast edited by <i>%s</i> on %s\n\n";
	
	echo sprintf($format, $filename, $last_edited_by, $modify_date);
}