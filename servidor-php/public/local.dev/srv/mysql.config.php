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
define ("MYSQL_HOST", "localhost");
define ("MYSQL_PORT", "3306");
define ("MYSQL_USER", "giana");
define ("MYSQL_PASSWORD", "giana1234");
define ("MYSQL_DB", "giana");

$link = new mysqli(
    MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB, MYSQL_PORT
);

if (!$link != 0)
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());

//echo 'Connected... ' . mysqli_get_host_info($link) . "\n";

// =============================================================================
?>