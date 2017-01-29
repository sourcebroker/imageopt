<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "imageopt".
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Optimize images created/resized by TYPO3',
	'description' => 'Optimize images created/resized by TYPO3 so they take less space. TYPO3 services used. Cron based. Support for linux native commands and external services as Kraken.io etc.',
	'category' => 'be',
	'version' => '0.0.2',
	'state' => 'alpha',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => true,
    'author' => 'SourceBroker Team',
    'author_email' => 'office@sourcebroker.net',
    'author_company' => 'SourceBroker',
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '6.2.0-7.6.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
);

?>