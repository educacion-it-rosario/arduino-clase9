================================================================================
GIANA Framework | Home Automation Made Easy. (LAMP || WAMP) + Arduino UNO r3.
================================================================================
Copyright (C) 2013 Federico Pfaffendorf (www.federicopfaffendorf.com.ar)
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version. 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program. If not, see http://www.gnu.org/licenses/gpl.txt
================================================================================
INSTALLATION
================================================================================
1. Arduino
--------------------------------------------------------------------------------
Upload the GIANA sketch to your Arduino UNO r3.
You can find the sketch in ./arduino/giana/giana.ino
--------------------------------------------------------------------------------
2. mySQL
--------------------------------------------------------------------------------
Create a new database in your mySQL called, for example, 'giana'.
Execute against that database the script located in ./mysql/db.sql
--------------------------------------------------------------------------------
3. PHP
--------------------------------------------------------------------------------
Create a new directory on your apache server root called, for example, 'giana'.
Copy there the entire content of the ./php folder.
--------------------------------------------------------------------------------
4. Daemon (Windows)
--------------------------------------------------------------------------------
4.1 Configuration
--------------------------------------------------------------------------------
Open ./daemon/NET/Giana.cs with your favorite text editor.
In Context class, please change: Giana, _ServicesRootURI and _SALT members
  following the instructions commented in that class.
--------------------------------------------------------------------------------
4.2 Running from command line
--------------------------------------------------------------------------------
First of all be sure that you have the latest C# compiler executable (csc.exe) 
	in your PATH environment variable. If not please add it. 
Open a command line console.
Go to ./daemon/NET and execute: csc Giana.cs
Run Giana.exe
--------------------------------------------------------------------------------
4.3 Running from Visual Studio
--------------------------------------------------------------------------------
Open a new console application project.
Go to the solution explorer and delete Program.cs.
Now add to your project ./daemon/NET/Giana.cs and press F5.
--------------------------------------------------------------------------------
5. Daemon (Linux)
--------------------------------------------------------------------------------
5.1 Configuration
--------------------------------------------------------------------------------
Please install curl and mysqli extensions.
Open ./daemon/php/giana.php with your favorite text editor.
In Context class change servicesRootURI, SALT and BaudRate constants. Also
  change in Context::initialize() function the $gianas array following the 
	instructions commented in that class.
--------------------------------------------------------------------------------
5.2 Cron
--------------------------------------------------------------------------------
Cron a new job to excecute the command 'php giana.php' every 10 seconds.
You can also run 'while true ; do ./your-script & ; sleep 10; done' if you don't 
  use cron.
--------------------------------------------------------------------------------
6. Done
--------------------------------------------------------------------------------
Now you can navigate to the frontend using a web browser.
Login using user 'admin@giana.com' password 'admin'.
You can manage users, devices and scheduled task from any mySQL client. For
  example phpMyAdmin.
By default you can set and get onboard pin 13 LED and the analogue pin 0.
--------------------------------------------------------------------------------