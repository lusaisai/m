<?php

//---------------------------------------------
// Database configuration
//---------------------------------------------

return array
(
	/**
	 * Default configuration to use.
	 */
	
	'default' => 'mysql',

	/**
	 * Enable the query log?
	 */

	'log_queries' => false,
	
	/**
	 * You can define as many database configurations as you want.
	 *
	 * dsn       : PDO data source name
	 * username  : (optional) Username of the database server
	 * password  : (optional) Password of the database server
	 * persistent: (optional) Set to true to make the connection persistent
	 * queries   : (optional) Queries that will be executed right after a connection has been made
	 */
	
	'configurations' => array
	(
		'mysql' => array
		(
			'dsn'        => 'mysql:dbname=mav;host=localhost;port=3306',
			'username'   => 'mav',
			'password'   => 'mav633',
			'persistent' => false,
			'queries'    => array
			(
				"SET NAMES UTF8",
				"SET SESSION group_concat_max_len = 1000000"
			),
		),

		'sqlite' => array
		(
			'dsn'     => 'sqlite:' . MAKO_APPLICATION_PATH . '/storage/database/test.sqlite',
			'queries' => array
			(
				"PRAGMA encoding = 'UTF-8'",
			),
		),	
	),
);

/** -------------------- End of file --------------------**/