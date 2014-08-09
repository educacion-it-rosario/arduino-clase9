<?PHP 

	function getName()
	{

		ob_start();
		$_SERVER["REQUEST_METHOD"] = "GET";
		include_once ("./srv/users/index.php");
		$json = json_decode(ob_get_clean(), true);
		ob_flush();
		return $json["name"];
		
	}

	function signOut()
	{
	
		$_SERVER["REQUEST_METHOD"] = "GET";
		$_REQUEST["signOut"] = "true";
		include_once ("./srv/users/index.php");
	
	}
	
	function getDevices()
	{
	
		ob_start();
		$_SERVER["REQUEST_METHOD"] = "GET";
		include_once ("./srv/devices/index.php");
		$json = json_decode(ob_get_clean(), true);
		ob_flush();		
		return $json;
		
	}
	
?>