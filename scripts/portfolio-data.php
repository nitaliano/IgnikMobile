<?php
/**
 * @author: Nick Italiano
 */

	include_once "../lib/MySQLInfo.php";
	include_once "../lib/MySQLConnector.php";
	include_once "../lib/Portfolio.php";
	
	$PORTFOLIO_DIR = "../portfolio-images";
	$portfolio = new Portfolio(new MySQLConnector(MySQLInfo::HOST, MySQLInfo::USER, MySQLInfo::PASSWORD), MySQLInfo::DB);
	
	if($portfolio->openPortfolioByPath($PORTFOLIO_DIR) != -1){
		echo $portfolio->getPortfolio();
	} else {
		echo "Directory not found";
	}
?>