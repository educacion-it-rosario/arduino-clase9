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
error_reporting(1);
// =============================================================================
include_once ( dirname(__FILE__) . "/../security.config.php");
include_once ( dirname(__FILE__) . "/../mysql.config.php");

// =============================================================================
$mysqli = new mysqli(
								MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB, MYSQL_PORT
							);
if (!$mysqli != 0)
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());

// =============================================================================
switch (strtoupper ($_SERVER['REQUEST_METHOD']))
{

	case "POST":

		if ($_REQUEST ["SALT"] != SALT)
			die();

		$q = "UPDATE schedule ".
				 "SET lastRun = NOW(), ".
				 "lastRunError = '".$_REQUEST ["error"]."' ".
				 "WHERE scheduleId = '".$_REQUEST ["scheduleId"]."'";

	  $mysqli->query ($q);

		header ("content-type: text/xml");
		echo ('<?xml version="1.0"?>');
		echo ('<schedule>');
		echo ("<scheduleId>".$_REQUEST ["scheduleId"]."</scheduleId>");
		echo ('</schedule>');

	break;

  case "GET":

		if ($_REQUEST ["SALT"] != SALT)
			die();

		header ("content-type: text/xml");

		echo ('<?xml version="1.0"?>');

		$q = "SELECT scheduleId, port, pinType, pinNumber, trueValue, ".
				 "falseValue ".
				 "FROM schedule ".
				 "INNER JOIN devices ON schedule.deviceId = devices.deviceId ".
				 "WHERE 1 = 1 ".
				 "AND devices.readOnly = 'N' ".
				 "AND SUBSTRING(week, WEEKDAY(NOW()) + 1, 1) = 'Y' ".
				 "AND CURTIME() BETWEEN `from` AND `to` ".
				 "AND ".
				 "(".
				 "lastRun IS NULL OR ".
				 "(lastRun + INTERVAL everyMinutes MINUTE) <= NOW() ".
				 ") ".
				 "LIMIT 1";

		$q = $mysqli->query ($q);

		if ($r = $q->fetch_array(MYSQLI_ASSOC))
		{

			$scheduleId = $r ["scheduleId"];

			echo ('<schedule id="'.$scheduleId.'">');

			echo ('<device>'.
					  '<port>'.$r ["port"].'</port>'.
						'<pinType>'.$r ["pinType"].'</pinType>'.
						'<pinNumber>'.$r ["pinNumber"].'</pinNumber>'.
					  '<trueValue>'.$r ["trueValue"].'</trueValue>'.
					  '<falseValue>'.$r ["falseValue"].'</falseValue>'.
						'</device>');

			$q = "SELECT port, pinType, pinNumber, sign, rawValue ".
					 "FROM schedule_conditions ".
  				 "INNER JOIN devices ON ".
					 "schedule_conditions.deviceId = devices.deviceId ".
					 "WHERE 1 = 1 ".
					 "AND scheduleId = '".$scheduleId."'";

		  $q = $mysqli->query ($q);

			while ($r = $q->fetch_array(MYSQLI_ASSOC))
			{

				echo ('<condition>'.
							'<port>'.$r ["port"].'</port>'.
							'<pinType>'.$r ["pinType"].'</pinType>'.
							'<pinNumber>'.$r ["pinNumber"].'</pinNumber>'.
							'<sign><![CDATA['.$r ["sign"].']]></sign>'.
							'<rawValue>'.$r ["rawValue"].'</rawValue>'.
							'</condition>');

			}

			echo ('</schedule>');

		}
		else
		{

			echo ('<schedule></schedule>');

		}

	break;

  case "PUT":
	break;

  case "DELETE":
	break;

}
// =============================================================================
?>