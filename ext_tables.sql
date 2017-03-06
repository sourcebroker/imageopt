
CREATE TABLE sys_file_processedfile (
  tx_imageopt_optimized tinyint(3) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_imageopt_images (
  path text NOT NULL,
  optimized tinyint(3) unsigned DEFAULT '0' NOT NULL,
  file_size_before int(11) unsigned DEFAULT '0' NOT NULL,
  file_size_after int(11) unsigned DEFAULT '0' NOT NULL,
  optimization_bytes int(11) unsigned DEFAULT '0' NOT NULL,
  optimization_percentage float NOT NULL DEFAULT '0',
  provider_results text NOT NULL,
  provider_winner varchar(255) DEFAULT '' NOT NULL,

  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid),
);