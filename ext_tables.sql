CREATE TABLE sys_file_processedfile
(
    tx_imageopt_executed_successfully tinyint(3) unsigned DEFAULT '0' NOT NULL,
    tx_imageopt_executed              tinyint(3) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_imageopt_domain_model_moderesult'
#
CREATE TABLE tx_imageopt_domain_model_moderesult
(
    file_absolute_path    text,
    size_before           varchar(20) DEFAULT '' NOT NULL,
    size_after            varchar(20) DEFAULT '' NOT NULL,
    name           				varchar(50) DEFAULT '' NOT NULL,
    executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
    file_does_not_exist   smallint(5) unsigned DEFAULT '0' NOT NULL,
    output_filename       text,
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
    name                  varchar(255) DEFAULT '' NOT NULL,
    size_before           varchar(20)  DEFAULT '' NOT NULL,
    size_after            varchar(20)  DEFAULT '' NOT NULL,
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
    command_status        varchar(255) DEFAULT '' NOT NULL,
    executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
    error_message         text,
);

#
# Table structure for table 'tx_imageopt_domain_model_croppedfile'.
# which is a "cropped" file by remote executor
# This table does not have a TCA representation, as it is only written
# to using direct SQL queries in the code
#
CREATE TABLE tx_imageopt_domain_model_croppedfile (
    uid int(11) NOT NULL auto_increment,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,

    storage int(11) DEFAULT '0' NOT NULL,
    original int(11) DEFAULT '0' NOT NULL,
    identifier varchar(512) DEFAULT '' NOT NULL,
    configuration_sha1 varchar(40) DEFAULT '' NOT NULL,
    original_file_sha1 char(40) DEFAULT '' NOT NULL,
    name tinytext,
    processing_provider varchar(255) DEFAULT '' NOT NULL,
    configuration blob,

    PRIMARY KEY (uid),
    KEY combined_1 (original,configuration_sha1),
    KEY identifier (storage,identifier(180))
);

