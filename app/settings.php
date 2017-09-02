<?php
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
return [ 	
	'settings' => [
		// Slim Settings
		'determineRouteBeforeAppMiddleware' => false,
		'displayErrorDetails' => true,			
		// View settings
		'view' => [ 
			'template_path' => __DIR__ . '/templates',
			'twig' => [ 
				//'cache' => __DIR__ . '/../cache/twig',
				'cache' => false,
				'debug' => true,
				'auto_reload' => true 
			] 
		],				
		// monolog settings
		'logger' => [ 
			'name' => 'app',
			'path' => __DIR__ . '/../log/app.log',
			'level' => \Monolog\Logger::DEBUG
		],				
		'db' => [ 			
			'driver' => getenv('DB_DRIVER'),
			'host' => getenv('DB_HOST'),
			'port' => getenv('DB_PORT'),
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'charset'   => getenv('DB_CHARSET'),
            'collation' => getenv('DB_COLLATION'),
            'prefix'    => getenv('DB_PREFIX'),
		] ,				
	] 
];
