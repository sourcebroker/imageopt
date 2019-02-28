<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult',
        'label' => 'file_relative_path',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => false,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'file_relative_path,size_before,size_after,optimization_bytes,optimization_percentage,provider_winner_name,executed_successfully,info,providers_results',
        'iconfile' => 'EXT:imageopt/Resources/Public/Icons/tx_imageopt_domain_model_optimizationoptionresult.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'file_relative_path, size_before, size_after, optimization_bytes, optimization_percentage, provider_winner_name, executed_successfully, info, step_results',
    ],
    'types' => [
        '1' => ['showitem' => 'file_relative_path, --palette--;;sizes, --palette--;;optimization, provider_winner_name, info, executed_successfully, step_results'],
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
        'file_relative_path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.file_relative_path',
            'config' => [
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'readOnly' => 1
            ],
        ],
        'size_before' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.size_before',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'readOnly' => 1
            ],
        ],
        'size_after' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.size_after',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'readOnly' => 1
            ],
        ],
        'executed_successfully' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.executed_successfully',
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
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.info',
            'config' => [
                'type' => 'text',
                'cols' => 200,
                'rows' => 3,
                'eval' => 'trim',
                'readOnly' => 1,
            ]
        ],
        'step_results' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.optimization_step_results',
            'config' => [
                'type' => 'inline',
                'readOnly' => 1,
                'foreign_table' => 'tx_imageopt_domain_model_stepresult',
                'foreign_field' => 'option_result',
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
