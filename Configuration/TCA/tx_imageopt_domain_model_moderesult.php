<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult',
        'label' => 'file_absolute_path',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => false,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'file_absolute_path,size_before,size_after,optimization_bytes,optimization_percentage,executed_successfully,info',
        'iconfile' => 'EXT:imageopt/Resources/Public/Icons/tx_imageopt_domain_model_optimizationoptionresult.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'name, file_absolute_path, output_filename, --palette--;;sizes, --palette--;;optimization, info, executed_successfully, file_does_not_exists, step_results'],
    ],
    'palettes' => [
        'sizes' => [
            'showitem' => 'size_before, size_after',
            'canNotCollapse' => true,
        ],
        'optimization' => [
            'showitem' => 'optimization_bytes, optimization_percentage',
            'canNotCollapse' => true,
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.enabled',
                    ],
                ],
            ],
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.name',
            'config' => [
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'file_absolute_path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.file_absolute_path',
            'config' => [
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'size_before' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.size_before',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'readOnly' => 1,
            ],
        ],
        'size_after' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.size_after',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'readOnly' => 1,
            ],
        ],
        'executed_successfully' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.executed_successfully',
            'config' => [
                'readOnly' => 1,
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'file_does_not_exist' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.file_does_not_exist',
            'config' => [
                'readOnly' => 1,
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
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
            ],
        ],
        'output_filename' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.output_filename',
            'config' => [
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'step_results' => [
            'exclude' => true,
            'label' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:tx_imageopt_domain_model_optimizationoptionresult.optimization_step_results',
            'config' => [
                'type' => 'inline',
                'readOnly' => 1,
                'foreign_table' => 'tx_imageopt_domain_model_stepresult',
                'foreign_field' => 'mode_result',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],
        ],
    ],
];
