<?php

	function nosqli($input)
	{
		$sqli=array("'","\"","=","!","-","&","*","?","+","-","/","#","@",";");
		$output=str_ireplace($sqli,"_",$input);
		return $output;
	}
?>
