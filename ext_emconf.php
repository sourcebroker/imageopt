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
    'description' => 'Optimize images created/resized by TYPO3 so they take less space. Safe as it does not optimize original images.',
    'category' => 'be',
    'version' => '8.0.0',
    'state' => 'stable',
    'author' => 'Inscript Team',
    'author_email' => 'office@inscript.dev',
    'author_company' => 'Inscript',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '11.5.99',
                ],
            'conflicts' =>
                [],
            'suggests' =>
                [],
        ],
];
