<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult',
        'label' => 'file_relative_path',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'file_relative_path,size_before,size_after,optimization_bytes,optimization_percentage,provider_winner_name,executed_successfully,info,providers_results',
        'iconfile' => 'EXT:imageopt/Resources/Public/Icons/tx_imageopt_domain_model_optimizationresult.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, file_relative_path, size_before, size_after, optimization_bytes, optimization_percentage, provider_winner_name, executed_successfully, info, providers_results',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, file_relative_path, size_before, size_after, optimization_bytes, optimization_percentage, provider_winner_name, executed_successfully, info, providers_results'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_imageopt_domain_model_optimizationresult',
                'foreign_table_where' => 'AND tx_imageopt_domain_model_optimizationresult.pid=###CURRENT_PID### AND tx_imageopt_domain_model_optimizationresult.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
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
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.file_relative_path',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'size_before' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.size_before',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'size_after' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.size_after',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'optimization_bytes' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.optimization_bytes',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'optimization_percentage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.optimization_percentage',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'provider_winner_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.provider_winner_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'executed_successfully' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.executed_successfully',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'info' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.info',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'providers_results' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationresult.providers_results',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_imageopt_domain_model_providerresult',
                'foreign_field' => 'optimizationresult',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],
    
    ],
];
