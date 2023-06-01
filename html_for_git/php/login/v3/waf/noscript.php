
<?php
	function noscript_replace($input)
	{
		$xss=array("java","src","onload","onerror","onmouseover","script","on");
		$output=str_ireplace($xss," ",$input);
		return $output;
	}
	function noscript_delete($input)
	{
		$xss=array("java","src","onload","onerror","onmouseover","script","on");
		if(strpos($input,$xss)!==0)
		{
			$output="<p><br><b>js blocked!<br>You got protected by jwaf</b><br></p>";
		}
		else
		{
			$output=$input;
		}
		return $output;
	}

?>
