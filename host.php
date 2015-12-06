<?php 

	header("content-type:application/json");
	error_reporting(E_ALL);
	ini_set('display_errors',1);

	$HostFiles = array(
		'adaway.org' => 'https://adaway.org/hosts.txt',
		'winhelp2002.mvps.org' => 'http://winhelp2002.mvps.org/hosts.txt' 

	);

	function remove_hashtags($string){
	    return str_replace('#', '', 
	        preg_replace('/(?:#[\w-]+\s*)+$/', '', $string));
	}

	function PurgeHostFiles($files){
		
		$combinedFiles = '';

		#array of item to search for and remove in string
		$srchAdtrj = array('localhost', '::1', '#[IPv6]', ' ');

		#Regex strings to use to search through the files and remove unnecessary information
		$find = array(
			'!/\*.*?\*/!s', 
			'/\n\s*\n/', #Remove comment blockes
			'/\#(.*)$/m', #/Remove comment blockes
			'/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', #Remove linebreaks that are left behind after removing comments
			'~^[ \t]++(?=(?:[^<]++|<(?!/?+pre\b))*+(?:\z|<pre\b))~im',
			'/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', #Remove linebreakes and space on top of file
		);

		#Replace values to combine with the find array. The quotation marks must be double once for some reason, else the \n will not work
		$replace = array(
			"", 
			"\n", 
			"", 
			"\n", 
			"",
			"",
		);

		if (!sizeof($find) === sizeof($replace)) die("$find and $replace arrays do not match, make sure there are equel mounts of items in both arrays");

		foreach ($files as $value) {

			$value = file_get_contents($value);

			#regex values, see more info in the arrays above
			$value = preg_replace($find, $replace, $value);

			#plain text values to replace direct examples, se more in the array above
			$value = str_replace($srchAdtrj, "", $value);

			$combinedFiles .= $value;
		}

		//remove any space left in the top of the file
		return ltrim($combinedFiles);
	}

	$hostArray = explode("\n", PurgeHostFiles($HostFiles));

	echo json_encode($hostArray)

	//$myfile = fopen("hosts.txt", "w") or die("Unable to open file!");
	
	//fwrite($myfile, PurgeHostFiles($HostFiles));

	//fclose($myfile);



?>