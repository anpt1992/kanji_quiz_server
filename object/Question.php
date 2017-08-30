<?php
class Question{
	public $id;
	public $content;
	public $answer1;
	public $answer2;
	public $answer3;
	public $correct_answer;
	function Question($ID,$CONTENT,$ANSWER1,$ANSWER2,$ANSWER3,$CORRECT_ANSWER){
		$this->id = $ID;
		$this->content = $CONTENT;
		$this->answer1 = $ANSWER1;
		$this->answer2 = $ANSWER2;
		$this->answer3 = $ANSWER3;
		$this->correct_answer = $CORRECT_ANSWER;
	}
}