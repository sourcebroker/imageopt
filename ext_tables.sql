#
# Table structure for table 'tx_imageopt_domain_model_providerresult'
#
CREATE TABLE tx_imageopt_domain_model_providerresult (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	optimizationresult int(11) unsigned DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	size_before int(11) DEFAULT '0' NOT NULL,
	size_after varchar(255) DEFAULT '' NOT NULL,
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

	size_before int(11) DEFAULT '0' NOT NULL,
	size_after int(11) DEFAULT '0' NOT NULL,
	command varchar(255) DEFAULT '' NOT NULL,
	command_output varchar(255) DEFAULT '' NOT NULL,
	command_status varchar(255) DEFAULT '' NOT NULL,
	executed_successfully smallint(5) unsigned DEFAULT '0' NOT NULL,
	error_message varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_imageopt_domain_model_optimizationresult'
#
CREATE TABLE tx_imageopt_domain_model_optimizationresult (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	file_relative_path varchar(255) DEFAULT '' NOT NULL,
	size_before varchar(255) DEFAULT '' NOT NULL,
	size_after varchar(255) DEFAULT '' NOT NULL,
	optimization_bytes varchar(255) DEFAULT '' NOT NULL,
	optimization_percentage varchar(255) DEFAULT '' NOT NULL,
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
# Table structure for table 'tx_imageopt_domain_model_executorresult'
#
CREATE TABLE tx_imageopt_domain_model_executorresult (

	providerresult int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_imageopt_domain_model_providerresult'
#
CREATE TABLE tx_imageopt_domain_model_providerresult (

	optimizationresult int(11) unsigned DEFAULT '0' NOT NULL,

);
