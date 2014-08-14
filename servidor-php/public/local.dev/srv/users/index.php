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

if ($mysqli->connect_errno != 0) {
    die ($mysqli->connect_errno . " | " . $mysqli->connect_error);
}

// =============================================================================
switch (strtoupper ($_SERVER['REQUEST_METHOD']))
{

case "POST":
    $q = "SELECT userId, name ".
        "FROM users ".
        "WHERE email = ? ".
        "AND password = SHA2(?, 256) ".
        "AND enabled = 'Y' ".
        "LIMIT 1";

    $p = $mysqli->prepare ($q);
    $p->bind_param ("ss", $_REQUEST ["email"], $_REQUEST ["password"]);
    $p->execute ();
    $p->bind_result ($userId, $name);
    $p->fetch ();

    $_SESSION["userId"] = $userId;
    $_SESSION["name"] = $name;

    echo ('{'.
    '"userId" : "'.$userId.'",'.
    '"name" : "'.$name.'"'.
    '}');

break;

case "GET":

    if (isset ($_REQUEST ["signOut"])) {
        session_destroy();

    }  else  {

        echo ('{'.
        '"userId" : "'.$_SESSION["userId"].'",'.
        '"name" : "'.$_SESSION["name"].'"'.
        '}');

    }

break;

case "PUT":
break;

case "DELETE":
break;

}
// =====================================================================
?>
