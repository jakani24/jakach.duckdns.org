<?php
	function convert_to_utf8($path)
	{
		$output=exec("file $path");
		//echo ($output);
		if(strpos($output,"ISO-8859")!==false)
		{
			echo(exec("iconv --verbose -f ISO-8859-1 -t UTF-8 $path -o $path"));
		}
		else if(strpos($output,"UTF-8")!==false)
		{
			//is allready utf-8
		}
		else
		{
			echo ("<br>ERROR: file encoding not known, maybe lead to further errors<br>You can either trie to convert your file manually to utf-8 or just ignore this error<br>");
		}
		file_put_contents($path, file_get_contents($path)."\r\n");
	}


?>


