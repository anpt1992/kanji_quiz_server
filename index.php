<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER ['SCRIPT_FILENAME'] !== __FILE__) {
	return false;
}

require __DIR__ . '/vendor/autoload.php';

session_start ();
// Instantiate the app
$settings = require __DIR__ . '/app/settings.php';
$app = new \Slim\App ( $settings );


// Set up dependencies
require __DIR__ . '/app/dependencies.php';

// Register middleware
require __DIR__ . '/app/middleware.php';

// Register routes
require __DIR__ . '/app/routes.php';
//Object
require 'object/Question.php';

$app->get('/', function ($request, $response, $args) {
    $response->write("Hello, ");
    return $response;
});
$app->get('/questions', function ($request, $response) {
	$result =array();
	try {			
		$result['status'] = "success";
		$arr_question =array();
		$query = "SELECT * FROM questions  WHERE 1";		
		$sth = $this->db->prepare ( $query );	
		$sth->execute ();
		foreach($sth->fetchAll () as $question) {	
			array_push($arr_question,new Question($question["id"],$question["content"],$question["answer1"],$question['answer2'],$question["answer3"],$question["correct_answer"]));
		}	
		$result['data']['questions'] = $arr_question;
		return $response->withJson ( $result );
	} catch (Exception $e) {
		$result['status'] = "error";
		return $response->withJson ( $result );
	}
});
$app->get('/questions/{id}', function ($request, $response) {	
	try {	
		$result['status'] = "success";		
		$id = $request->getAttribute ( 'id' );	
		$sth = $this->db->prepare ( "SELECT * FROM questions WHERE id=:id" );
		$sth->bindParam ( 'id', $id );
		$sth->execute ();	
		$result['data']['question'] = $sth->fetch();
		return $response->withJson ( $result );
	} catch (Exception $e) {
		$result['status'] = "error";
		return $response->withJson ( $result );
	}
});

$app->run();