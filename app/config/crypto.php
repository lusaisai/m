<?php

//---------------------------------------------
// Cryptography configuration
//---------------------------------------------

return array
(
	/**
	 * Default configuration to use.
	 */

	'default' => 'mcrypt',

	/**
	 * You can define as many cryptography configurations as you want.
	 *
	 * The supported cryptography libraries are: "Mcrypt", and "OpenSSL".
	 *
	 * library: Cryptography library you want to use (case-sensitive).
	 * cipher : The cipher method to use for encryption.
	 * key    : Key used to encrypt/decrypt data. You should NOT use the key included with the framework in a production environment!
	 * mode   : Encryption mode (only required when using the "mcrypt" library).
	 */

	'configurations' => array
	(
		'mcrypt' => array
		(
			'library' => 'Mcrypt',
			'cipher'  => MCRYPT_RIJNDAEL_256,
			'key'     => 'mbMLHaJc7fM8x+zRy5o3c+K+V6mbwK7E3S7g6c10PnP/269WCpZg116JYB2LunEMcpc=',
			'mode'    => MCRYPT_MODE_ECB,
		),

		'openssl' => array
		(
			'library'  => 'OpenSSL',
			'key'      => 'a1xLPu1/PijloaGPGP0TdJaBEWlevGyB5acwzUAXboM=',
			'cipher'   => 'AES-256-OFB',
		),
	),
);

/** -------------------- End of file --------------------**/