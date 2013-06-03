<?php
/**
 * @author Nick Italiano
 * This class is handles the information from the Contact form
 * and sends a email out to the desired location
 */ 

	class Contact{		
		private $name;
		private $email;
		private $budget;
		private $timeline;
		private $comments;
		
		private $to;
		private $from;
		private $subject;
		private $body;
		
		public function __construct($name, $email, $budget, $timeline, $comments){
			$this->name = $name;
			$this->email = $email;
			$this->budget = $budget;
			$this->timeline = $timeline;
			$this->comments = ($comments == "") ? null : $comments;
		}
		
		public function create(){
			$this->from = "nick@ignikdesign.com";
			$this->to = "ignikdesign@gmail.com";
			$this->subject = "IgnikDesign Contact Email From:  ".$this->name;
			
			$this->body = "From:  ".$this->name."\r\n\r\n
						   Email: ".$this->email."\r\n\r\n
						   Budget: ".$this->budget."\r\n\r\n
						   Timeline: ".$this->timeline."\r\n\r\n
			               Comments: ".$this->comments;
		}
		
		public function send(){
			mail($this->to, $this->subject, $this->body, "From:".$this->from);
		}
	}
?>