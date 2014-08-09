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
// =============================================================================
header ("content-type: text/xml");
echo ('<?xml version="1.0"?>');
echo ('<response>');
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

		if ($_REQUEST ["SALT"] != SALT)
			die();
	
		$q = "UPDATE requests ". 
				 "SET done = 'Y' ".
				 "WHERE requestId = '".$_REQUEST ["requestId"]."'";

 		$mysqli->query ($q);

		$q = "INSERT INTO responses (requestId, value, dateTime) ". 
				 "VALUES ('".$_REQUEST ["requestId"]."', '".$_REQUEST ["value"]."', ".				 
				 "NOW()); ";

		$mysqli->query ($q);
	
		echo ("<requestId>".$_REQUEST ["requestId"]."</requestId>");
	
	break;

  case "GET":
	break;

  case "PUT":
	break;

  case "DELETE":
	break;
	
}
// =============================================================================
echo ('</response>');
// =============================================================================
?>