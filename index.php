<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER ['SCRIPT_FILENAME'] !== __FILE__) {
	return false;
}
function debug($var){
    echo '<pre>'; print_r($var);die;
}
require __DIR__ . '/vendor/autoload.php';

session_start ();
// Instantiate the app
$settings = require __DIR__ . '/app/settings.php';
require __DIR__ . '/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
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
    $this->view->render($response, '/home.twig');
    return $response;
})->setName('home');
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

$app->post('/questions/import', function($request, $response,$arg) {   
    $filepath =$_FILES["filepath"]["tmp_name"];   
    $objPHPExcel = PHPExcel_IOFactory::load ( $filepath );  
    $data = array();
    $succ = 0;
    $err = 0;   
	$ActiveSheet = $objPHPExcel->getActiveSheet ();    
	for($r = 3; $r <= $ActiveSheet->getHighestRow (); $r ++) {
		try {           
            if(( $ActiveSheet->getCell ( 'A' . $r ) == ''))
            {
                continue;
            }            
           
			$mapping = array (	
                'A' => 'content',			
				'B' => 'answer1',
				'C' => 'answer2',
				'D' => 'answer3',				
				'E' => 'correct_answer',					
			);            
           
			for($col = 'A'; $col !== 'F'; $col ++) {               
				$cell = $ActiveSheet->getCell ( $col . $r );
				$text_value = trim ( $cell->getValue (), " " );               
				switch ($col) {                    					
					default :
						$data [$mapping[$col]] = $text_value;
                        break;
				}
            }  
            //debug($data);      
			$sth = $this->db->prepare ( "INSERT INTO questions(content, answer1, answer2, answer3, correct_answer) VALUES(:content, :answer1, :answer2, :answer3, :correct_answer)" );
            $sth->bindParam ( 'content', $data['content'] );
            $sth->bindParam ( 'answer1', $data['answer1'] );
            $sth->bindParam ( 'answer2', $data['answer2'] );
            $sth->bindParam ( 'answer3', $data['answer3'] );
            $sth->bindParam ( 'correct_answer', $data['correct_answer'] );
            $sth->execute ();	
            $succ++;  
            $this->logger->info("successfully imported");
        } 
        catch ( Exception $exc ) {
            $this->logger->info($exc);
            $err = 1;
        }
	}     
	echo 'ok';
    //$this->flash->addMessage('success', "Đã thêm thành công $succ câu hỏi.");
   // return $response->withRedirect($this->router->pathFor('partner'), 200);
});

$app->run();