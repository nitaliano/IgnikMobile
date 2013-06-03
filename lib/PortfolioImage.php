<?php
/**
 * @author Nick Italiano 
 */

	class PortfolioImage{
		private $fileName;
		private $popupLInk;
		private $likes;
		
		function __construct($fileName){
			$this->fileName = $fileName;
			$this->popupLink = str_replace(".jpg", "_popup.jpg", $fileName);
		}
		
		public function setLikes($mysql){
			$selectQuery = "select likes from Likes where filename='".$this->fileName."'";
			$result = $mysql->executeQuery($selectQuery, false);
			$this->likes = $result[0];
		}
		
		public function getLikes(){
			return $this->likes;
		}
		
		public function getThumbnailPath(){
			return $this->fileName;
		}
		
		public function getLargeImagePath(){
			return $this->popupLink;
		}
		
		public function getJsonData(){
			return get_object_vars($this);
		}
	}

?>