
Changelog
---------

Remote Executors
~~~~~~~~~~~~~~~~

1) [FEATURE] Add configuration merging from default.providers (and executors).

2) [FEATURE] Make executors enabled by default.

master
~~~~~

1) [FEATURE][BREAKING] So far imageopt was forcing all images to be resized on fronted by default. Right now its
    configurable in Typoscript. To activate this behaviour you need to include static extension Typoscript
    in frontent template record.

2) [FEATURE] Allow to define files that should not be forced to be processed (regexp on filepath / filename).

3) [FEATURE] Add symfony commands and rework configurator.

4) [TASK] Add scheduler task as fallback for using symfony commands as scheduler task in TYPO3 8.7.

5) [TASK] Refactor for FileProcessingService xclass.

6) [TASK] Increase numberOfImagesToProcess from 20 to 50.

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
