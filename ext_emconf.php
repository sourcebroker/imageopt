<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "imageopt".
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Optimize images created/resized by TYPO3',
    'description' => 'Optimize images created/resized by TYPO3 so they take less space. Cron based. Support for linux native commands.',
    'category' => 'be',
    'version' => '2.0.0',
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => false,
    'author' => 'SourceBroker Team',
    'author_email' => 'office@sourcebroker.net',
    'author_company' => 'SourceBroker',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '6.2.0-8.7.99',
                ],
            'conflicts' =>
                [],
            'suggests' =>
                [],
        ],
];
