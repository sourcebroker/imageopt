
Changelog
---------

1.2.0
~~~~~

1) [FEATURE] Add support for choosing uid of page to parse TSConfig. If not set then fallback to first root page.

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