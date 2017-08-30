<?php
return [ 
		'settings' => [
				// Slim Settings
				'determineRouteBeforeAppMiddleware' => false,
				'displayErrorDetails' => true,
				
				// View settings
				'view' => [ 
						'template_path' => __DIR__ . '/templates',
						'twig' => [ 
								'cache' => __DIR__ . '/../cache/twig',
								'debug' => true,
								'auto_reload' => true 
						] 
				],
				
				// monolog settings
				'logger' => [ 
						'name' => 'app',
						'path' => __DIR__ . '/../log/app.log' 
				],
				// Database connection settings
				'db' => [ 
						'host' => '127.0.0.1',
						'dbname' => 'bluetooth',
						'user' => 'root',
						'pass' => '' 
				] ,
//				'db' => [
//						'host' => '127.0.0.1',
//						'dbname' => 'bluetooth',
//						'user' => 'root',
//						'pass' => ''
//				] ,
// 				'db' => [
// 						'host' => 'mysql.hostinger.vn',
// 						'dbname' => 'u229611345_ssbt',
// 						'user' => 'u229611345_anpt',
// 						'pass' => 'wInJAph3ze'
// 				],
		] 
];
