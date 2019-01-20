CREATE TABLE sys_file_processedfile (
  tx_imageopt_executed_successfully tinyint(3) unsigned DEFAULT '0' NOT NULL
);


#
# Table structure for table 'tx_imageopt_domain_model_optimizationoptionresult'
#
CREATE TABLE tx_imageopt_domain_model_optimizationoptionresult (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	file_relative_path text,
	size_before int(10) UNSIGNED DEFAULT 0 NOT NULL,
	size_after int(10) UNSIGNED DEFAULT 0 NOT NULL,
	executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
	info text,
	optimization_step_results int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);


#
# Table structure for table 'tx_imageopt_domain_model_optimizationstepresult'
#
CREATE TABLE tx_imageopt_domain_model_optimizationstepresult (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(100) DEFAULT '' NOT NULL,
	size_before int(10) UNSIGNED DEFAULT 0 NOT NULL,
	size_after int(10) UNSIGNED DEFAULT 0 NOT NULL,
	provider_winner_name varchar(255) DEFAULT '' NOT NULL,
	executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
	info text,
	providers_results int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_imageopt_domain_model_providerresult'
#
CREATE TABLE tx_imageopt_domain_model_providerresult (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	optimizationresult int(11) unsigned DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	size_before varchar(20) DEFAULT '0' NOT NULL,
	size_after varchar(20) DEFAULT '' NOT NULL,
	executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
	winner smallint(5) unsigned DEFAULT '0' NOT NULL,
	executors_results int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_imageopt_domain_model_executorresult'
#
CREATE TABLE tx_imageopt_domain_model_executorresult (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	providerresult int(11) unsigned DEFAULT '0' NOT NULL,

	size_before varchar(20) DEFAULT '0' NOT NULL,
	size_after varchar(20) DEFAULT '0' NOT NULL,
	command text,
	command_output text,
	command_status varchar(255) DEFAULT '' NOT NULL,
	executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
	error_message text,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);
