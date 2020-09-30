<?php

return [
    'imageopt:optimizefolderimages' => [
        'class' => \SourceBroker\Imageopt\Command\OptimizeFolderImages::class
    ],
    'imageopt:optimizefalprocessedimages' => [
        'class' => \SourceBroker\Imageopt\Command\OptimizeFalProcessedImages::class
    ],
    'imageopt:resetoptimizationflagforfal' => [
        'class' => \SourceBroker\Imageopt\Command\ResetOptimizationFlagForFal::class
    ],
    'imageopt:resetoptimizationflagforfolders' => [
        'class' => \SourceBroker\Imageopt\Command\ResetOptimizationFlagForFolders::class
    ],
];