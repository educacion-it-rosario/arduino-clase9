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
session_start();
// =============================================================================
include_once ( dirname(__FILE__) . "/../security.config.php");
include_once ( dirname(__FILE__) . "/../mysql.config.php");
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
break;

case "GET":

    if (!isset($_SESSION ["userId"]))
        die();

    if (isset ($_REQUEST ["deviceId"]))  {

        $q = "SELECT devices.pinType, devices.deviceId, devices.fx, ".
            "requests.value, responses.value, responses.dateTime ".
            "FROM responses ".
            "INNER JOIN requests ON ( ".
            "responses.requestId = requests.requestId ".
            ") ".
            "INNER JOIN devices ON ( ".
            "devices.deviceId = ? ".
            "AND devices.port = requests.port ".
            "AND devices.pinType = requests.pinType ".
            "AND devices.pinNumber = requests.pinNumber ".
            ") ".
            "ORDER BY responses.dateTime DESC ".
            "LIMIT 1";

        $p = $mysqli->prepare ($q);
        $p->bind_param ("s", $_REQUEST ["deviceId"]);
        $p->execute ();
        $p->bind_result ($pinType, $deviceId, $fx,  $requestValue,
        $responseValue, $dateTime);
        $p->fetch ();

        $value = $responseValue;

        if ($value == "OK")
            $value = $requestValue;

        $x = $value;
        eval('$value = ' . $fx . ";");

        if ($pinType == "D") {
            if ($value == 1) $value = "On";
            else $value = "Off";
        }

        echo ('{'.
            '"deviceId" : "'.$deviceId.'",'.
            '"value" : "'.$value.'",'.
            '"dateTime" : "'.$dateTime.'"'.
        '}');

    }
    else {
        $q = "SELECT deviceId, name, port, pinType, pinNumber, readOnly, fx ".
            "FROM devices ".
            "ORDER BY name ";

        $q = $mysqli->query ($q);

        echo ('{');

        $i = 0;
        while ($r = $q->fetch_array(MYSQLI_ASSOC)) {

            $q2 = "SELECT requests.value AS requestValue, responses.value AS responseValue, ".
                "responses.dateTime ".
                "FROM responses ".
                "INNER JOIN requests ON responses.requestId = requests.requestId ".
                "WHERE port = '".$r["port"]."' ".
                "AND pinType = '".$r["pinType"]."' ".
                "AND pinNumber = '".$r["pinNumber"]."' ".
                "ORDER BY dateTime DESC ".
                "LIMIT 1";

            $q2 = $mysqli->query ($q2);

            $r2 = $q2->fetch_array(MYSQLI_ASSOC);

            $r2["value"] = $r2["responseValue"];

            if ($r2["value"] == "OK")
                $r2["value"] = $r2["requestValue"];

            $x = $r2["value"];
            eval('$r2["value"] = ' . $r["fx"] . ";");

            if ($r["pinType"] == "D") {
                if ($r2["value"] == 1) $r2["value"] = "On";
                else $r2["value"] = "Off";
            }

            echo (
                '"'.$i.'":{'.
                '"deviceId":"'.$r["deviceId"].'",'.
                '"name":"'.$r["name"].'",'.
                '"readOnly":"'.$r["readOnly"].'",'.
                '"value":"'.$r2["value"].'",'.
                '"dateTime":"'.$r2["dateTime"].'"'.
                '}'
            );

            $i++;

            if ($i < $q->num_rows) echo (',');

        }

        echo('}');

    }

break;

case "PUT":
break;

case "DELETE":
break;

}
// =============================================================================
?>
