<?php
/**
 * @author: Nick Italiano
 * This class handles getting the portfolio image names, likes, and popuplinks
 */
	include_once "PortfolioImage.php"; 
	
	class Portfolio{
		private $images;
		private $mySQLConnector;
		private $db;
		
		public function __construct($mySQLConnector, $db){
			$this->images = array();
			$this->mySQLConnector = $mySQLConnector;
			$this->db = $db;
		}
		
		public function openPortfolioByPath($path){
			if ($opendir = opendir($path.'/')){
				
				$this->mySQLConnector->connect();
				$this->mySQLConnector->selectDB($this->db);
				
				while( ($image = readdir($opendir)) != false ){
					
					if( $image != "." && $image != ".."){						
						$piece = new PortfolioImage($image);
						$piece->setLikes($this->mySQLConnector);
						$this->images[$image] = $piece;
					}
				}
				
				$this->mySQLConnector->close();
				
			} else {
				return -1;
			}
		}
		
		public function getPortfolio(){
			$json = array();
			
			foreach($this->images as $key => $portfolioImage){
				$json[$key] = $portfolioImage->getJsonData();
			}
			
			return json_encode($json);
		}
	}
?>