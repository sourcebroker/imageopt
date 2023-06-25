CREATE TABLE sys_file_processedfile
(
    tx_imageopt_executed_successfully tinyint(3) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_imageopt_domain_model_moderesult'
#
CREATE TABLE tx_imageopt_domain_model_moderesult
(
    file_absolute_path    text,
    size_before           varchar(20)  DEFAULT '' NOT NULL,
    size_after            varchar(20)  DEFAULT '' NOT NULL,
    executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
    step_results          int(11) unsigned DEFAULT '0' NOT NULL,
    info                  text,
);

#
# Table structure for table 'tx_imageopt_domain_model_stepresult'
#
CREATE TABLE tx_imageopt_domain_model_stepresult
(
    name                  varchar(100) DEFAULT '' NOT NULL,
    description           varchar(255) DEFAULT '' NOT NULL,
    size_before           varchar(20)  DEFAULT '' NOT NULL,
    size_after            varchar(20)  DEFAULT '' NOT NULL,
    provider_winner_name  varchar(255) DEFAULT '' NOT NULL,
    executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
    mode_result           int(11) unsigned DEFAULT '0' NOT NULL,
    providers_results     int(11) unsigned DEFAULT '0' NOT NULL,
    info                  text,
);

#
# Table structure for table 'tx_imageopt_domain_model_providerresult'
#
CREATE TABLE tx_imageopt_domain_model_providerresult
(
    name                  varchar(255) DEFAULT ''  NOT NULL,
    size_before           varchar(20)  DEFAULT '' NOT NULL,
    size_after            varchar(20)  DEFAULT ''  NOT NULL,
    executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
    step_result           int(11) unsigned DEFAULT '0' NOT NULL,
    executors_results     int(11) unsigned DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_imageopt_domain_model_executorresult'
#
CREATE TABLE tx_imageopt_domain_model_executorresult
(
    provider_result       int(11) unsigned DEFAULT '0' NOT NULL,
    size_before           varchar(20)  DEFAULT '' NOT NULL,
    size_after            varchar(20)  DEFAULT '' NOT NULL,
    command               text,
    command_output        text,
    command_status        varchar(255) DEFAULT ''  NOT NULL,
    executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
    error_message         text,
);
