<?PHP 
// =============================================================================
// GIANA Framework | Home Automation Made Easy. (LAMP || WAMP) + Arduino UNO r3.
// =============================================================================
// Copyright (C) 2013 Federico Pfaffendorf (www.federicopfaffendorf.com.ar)
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// any later version. 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program. If not, see http://www.gnu.org/licenses/gpl.txt
// =============================================================================
error_reporting(0);
session_start();
// =============================================================================
header ("content-type: text/xml");
echo ('<?xml version="1.0"?>');
echo ('<request>');
// =============================================================================
include_once ($_SERVER["DOCUMENT_ROOT"] . "/giana/srv/security.config.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/giana/srv/mysql.config.php");
// =============================================================================
$mysqli = new mysqli(
								MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB, MYSQL_PORT
							);
if ($mysqli->connect_errno != 0)
	die ($mysqli->connect_errno . " | " . $mysqli->connect_error);
// =============================================================================
switch (strtoupper ($_SERVER['REQUEST_METHOD']))
{

	case "POST":
	
		if ((!isset($_SESSION ["userId"])) && ($_REQUEST ["SALT"] != SALT))
			die();
	
		if (!isset($_REQUEST ["userId"])) 
			$_REQUEST ["userId"] = $_SESSION ["userId"];
	
		if (!$_REQUEST ["value"])
			$_REQUEST ["value"] = 0;
			
	
		if (isset($_REQUEST ["deviceId"]))
		{
		
			$q = "SELECT port, pinType, pinNumber ".
					 "FROM devices ".
					 "WHERE deviceId = ? ".
					 "LIMIT 1";
					 
			$p = $mysqli->prepare ($q);
			$p->bind_param ("s", $_REQUEST ["deviceId"]);		 
			$p->execute ();
			$p->bind_result ($_REQUEST ["port"], $_REQUEST ["pinType"], $_REQUEST ["pinNumber"]);
			$p->fetch ();

			$mysqli = new mysqli(
								MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB, MYSQL_PORT
							);
			
		}
	
		$q = "INSERT INTO requests ". 
				 "(userId, port, action, pinType, pinNumber, value, done, dateTime) ".
				 "VALUES ".
				 "('".$_REQUEST ["userId"]."', '".$_REQUEST ["port"]."', '".
				      $_REQUEST ["action"]."', '".$_REQUEST ["pinType"]."', '".
				      $_REQUEST ["pinNumber"]."', '".$_REQUEST ["value"]."', 'N', NOW())";

		$mysqli->query ($q);

		echo ("<requestId>".$mysqli->insert_id."</requestId>");
	
	break;
	

  case "GET":
	
		if ($_REQUEST ["SALT"] != SALT)
			die();
	
		$q = "SELECT requestId, port, action, pinType, pinNumber, value " . 
				 "FROM requests " .
				 "WHERE done = 'N' " .
				 "LIMIT 1";

 	  $q = $mysqli->query ($q);

		if ($r = $q->fetch_array(MYSQLI_ASSOC))
		{

			echo ('<requestId>'.$r ["requestId"].'</requestId>'.
						'<port>'.$r ["port"].'</port>'.
						'<action>'.$r ["action"].'</action>'.
						'<pinType>'.$r ["pinType"].'</pinType>'.
						'<pinNumber>'.$r ["pinNumber"].'</pinNumber>'.
						'<value>'.$r ["value"].'</value>');
						
		}

	break;

  case "PUT":
	break;

  case "DELETE":
	break;
	
}
// =============================================================================
echo ('</request>');
// =============================================================================
?>