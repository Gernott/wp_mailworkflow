CREATE TABLE tx_wpmailworkflow_domain_model_mailgroup (
	title varchar(255) NOT NULL DEFAULT '',
	mails int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_wpmailworkflow_domain_model_mail (
	mailgroup int(11) unsigned DEFAULT '0' NOT NULL,
	title varchar(255) NOT NULL DEFAULT '',
	days_to_send int(11) NOT NULL DEFAULT '0',
	send_time time DEFAULT NULL,
	subject varchar(255) NOT NULL DEFAULT '',
	mailtext text,
	attachment int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_wpmailworkflow_domain_model_recipient (
	start date DEFAULT NULL,
	first_name varchar(255) NOT NULL DEFAULT '',
	last_name varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT '',
	parameter1 varchar(255) NOT NULL DEFAULT '',
	parameter2 varchar(255) NOT NULL DEFAULT '',
	parameter3 varchar(255) NOT NULL DEFAULT '',
	parameter4 varchar(255) NOT NULL DEFAULT '',
	parameter5 varchar(255) NOT NULL DEFAULT '',
	mail_group int(11) unsigned DEFAULT '0'
);

CREATE TABLE tx_wpmailworkflow_domain_model_queue (
	is_sent smallint(1) unsigned NOT NULL DEFAULT '0',
	send_at int(11) NOT NULL DEFAULT '0',
	sent int(11) NOT NULL DEFAULT '0',
	recipient int(11) unsigned DEFAULT '0',
	mail int(11) unsigned DEFAULT '0'
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder