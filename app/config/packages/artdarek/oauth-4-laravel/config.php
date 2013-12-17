<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '',
            'client_secret' => '',
            'scope'         => array(),
        ),		

		/**
		 * Facebook
		 */
        'Twitter' => array(
            'client_id'     => 'GGLZ64FL0QC8xGptzWJGw',
            'client_secret' => 'KY9uFRHFXsmcAnchUrREQcuw5scOSyuEmAhCHAOD4',
            'scope'         => array(),
        ),		

	)

);