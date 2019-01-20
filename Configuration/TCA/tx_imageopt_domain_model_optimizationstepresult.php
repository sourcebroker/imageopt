<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => false,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'name,size_before,size_after,optimization_bytes,optimization_percentage,provider_winner_name,executed_successfully,info,optimization_step_results',
        'iconfile' => 'EXT:imageopt/Resources/Public/Icons/tx_imageopt_domain_model_optimizationstepresult.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'name, size_before, size_after, optimization_bytes, optimization_percentage, provider_winner_name, executed_successfully, info, optimization_step_results',
    ],
    'types' => [
        '1' => ['showitem' => 'name, --palette--;;sizes, --palette--;;optimization, provider_winner_name, info, executed_successfully, optimization_step_results'],
    ],
    'palettes' => [
        'sizes' => [
            'showitem' => 'size_before, size_after',
            'canNotCollapse' => true
        ],
        'optimization' => [
            'showitem' => 'optimization_bytes, optimization_percentage',
            'canNotCollapse' => true
        ]
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult.name',
            'config' => [
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'readOnly' => 1
            ],
        ],
        'size_before' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult.size_before',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'readOnly' => 1
            ],
        ],
        'size_after' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult.size_after',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'readOnly' => 1
            ],
        ],
        'provider_winner_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult.provider_winner_name',
            'config' => [
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'readOnly' => 1
            ],
        ],
        'executed_successfully' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult.executed_successfully',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
                'readOnly' => 1
            ]
        ],
        'info' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult.info',
            'config' => [
                'type' => 'text',
                'cols' => 200,
                'rows' => 3,
                'eval' => 'trim',
                'readOnly' => 1,
            ]
        ],
        'providers_results' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationstepresult.providers_results',
            'config' => [
                'type' => 'inline',
                'readOnly' => 1,
                'foreign_table' => 'tx_imageopt_domain_model_providerresult',
                'foreign_field' => 'optimizationresult',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],

    ],
];
