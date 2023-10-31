
Changelog
---------

7.0.2
~~~~~

1) [BUGFIX] Cleanup TCA from deprecated showRecordFieldList.

7.0.1
~~~~~

1) [BUGFIX] Move addStaticFile from ext_tables to TCA/Overrides for sys_template. Replace TYPO3_MODE to TYPO3.

7.0.0
~~~~~

1) [TASK] TYPO3 11 compatibility.
2) [BUGFIX] Fix failing when step has no provider.
3) [TASK] Extended ddev testbed.
4) [TASK] Cleanup ext_tables.sql from standard fields / rename TS setup extension
5) [BUGFIX] Fix $file in isAllowedToForceFrontendImageProcessing is not always a path (string).
6) [FEATURE] Allow to choose what files extensions are to be optimised.
7) [TASK] Remove wrap for database queries. Was used when yet TYPO3 below 8.7 was supported.
8) [BUGFIX][BREAKING] Add gif as supported extension for webp imagemagick provider.
9) [FEATURE] Add new default settings. Only webp optimisation.
10) [BUGFIX] Normalise size_before, size_after database schema to varchar. The best would be
    "int(11) unsigned DEFAULT NULL", but TYPO3 database update schema command do not accept this.

6.0.3
~~~~~

1) [BUGFIX] Make composer.json valid.

6.0.2
~~~~~

1) [BUGFIX] Fix removing of temporary filenames.
2) [TASK] Add composer allow-plugins.

6.0.1
~~~~~

1) [BUGFIX] Make ext_tables.sql compatible with compare database tool.

6.0.0
~~~~~

1) [TASK][!!!BREAKING] Replace CommandController commands  with Symfony commands.
2) [BUGFIX] Fix wrong TS config for tx_imageopt__0110.tsconfig
3) [TASK] Drop support for TYPO3 7.6 and 8.7. Add support for TYPO3 10.
4) [TASK] Optimise travis config file.

5.0.0
~~~~~

1) [FEATURE] Support for executorsDefault and providersDefault.
2) [TASK][BREAKING] Remove executor.enabled option.
3) [FEATURE] Add remote executors implementation (kraken / imageoptim / tinypng).
4) [FEATURE][BREAKING] Disable all providers by default.
5) [TASK] Add support for TYPO3 7.6 and PHP 5.6.
6) [FEATURE] Add .env files for unit test passwords for remote executors.
7) [FEATURE] Add type for providers and config override by type.
8) [FEATURE] Add new way to decide what images should be optimized: mix of provider type and regexp on filepath and filename.
9) [FEATURE] Add support for mozjpeg.
10) [TASK][BREAKING] Remove int key based quality as its hard to compare qualities.
11) [BUGFIX] Fix results not being persisted.
12) [FEATURE][!!!BREAKING] Add support for chained provides executors.
13) [TASK] Rename models / fix TCA relations.
14) [TASK] Set name and description for mode and step for better CLI reporting.
15) [TASK] Update FAL with file outside file storage.
16) [TASK] Make cli results more descriptive and easy to understand.
17) [FEATURE] Add configuration sets choosable in page properties

4.0.0
~~~~~

1) [FEATURE][BREAKING] So far imageopt was forcing all images to be resized on fronted by default. Right now its
   configurable in Typoscript. To activate this behaviour you need to include static extension Typoscript
   in frontent template record.
2) [FEATURE] Allow to define files that should not be forced to be processed (regexp on filepath / filename).
3) [FEATURE] Add symfony commands and rework configurator.
4) [TASK] Add scheduler task as fallback for using symfony commands as scheduler task in TYPO3 8.7.
5) [TASK] Refactor for FileProcessingService xclass.
6) [TASK] Increase numberOfImagesToProcess from 20 to 50.
7) [TASK][BREAKING] Convert symfony argumnets to options.

3.0.0
~~~~~

1) [TASK] Drop travis testing for PHP 5.6 and TYPO3 7.6.
2) [TASK] TYPO3 9.5 compatibility.
3) [TASK] ext_localconf.php refactor
4) [TASK] Drop travis testing for TYPO3 7.6. Add testing for TYPO3 9.5.
5) [TASK] Increase nimut/testing-framework for TYPO3 9.5 tests
6) [TASK] Update test for TYPO3 9.5
7) [TASK] Remove not used variables, improve phpdocs, cast variables.

2.0.1
~~~~~

1) [BUGFIX] Correctly cleanup temp files.

2.0.0
~~~~~

1) [DOC] Add missing changelog for version 1.2.1
2) [BUGFIX] Add missing "info" lang label
3) [FEATURE] Add wordwrap 70 for info when showing resulats on CLI
4) [BUGFIX] Do not throw exception if processed files is deleted - show info instead.
5) [TASK] Increase ext version ext_emconf.php
6) [DOC] Improve changelog.
7) [BREAKING] Replace function "exif_imagetype" with "getimagesize" which is more popular.
8) [DOCS] Improve docs / add overview images.
9) [TASK] Change typo3/cms to typo3/cms-core in composer json req.

1.2.1
~~~~~

1) [BUGFIX] Increase ext version ext_emconf.php

1.2.0
~~~~~

1) [FEATURE] Add support for choosing uid of page to parse TSConfig. If not set then fallback to first root page.
2) [FEATURE] Colapse 1:n relation of executorsResults in ProviderResult

1.1.0
~~~~~

1) [BUGFIX] Fix wrong default value for file_relative_path / text.
2) [TASK] Optimize TCA settings for models.

1.0.2
~~~~~

1) [BUGFIX] Fix wrong data type/size on sql. Fix Tests to reflect changed data types.

1.0.1
~~~~~

1) [BUGFIX] Change composer.json description.

1.0.0
~~~~~

1) [TASK][BREAKING] Remove services.
2) [TASK][BREAKING] Remove support for remote optimizers for now. It will be back later.
3) [TASK]Add support for chained executors.
4) [TASK][BREAKING] Remove services.
5) [TASK]Add models for OptimizationResult / ProviderResult / ExecutorResult.
6) [TASK][BREAKING] Modify TSconfig structure.
7) [TASK][BREAKING] Rename tx_imageopt_optimized to tx_imageopt_executed_successfully on sys_file_processedfile
