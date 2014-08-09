-- =============================================================================
-- GIANA Framework | Home Automation Made Easy. (LAMP || WAMP) + Arduino UNO r3.
-- =============================================================================
-- Copyright (C) 2013 Federico Pfaffendorf (www.federicopfaffendorf.com.ar)
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- any later version. 
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
-- GNU General Public License for more details.
-- You should have received a copy of the GNU General Public License
-- along with this program. If not, see http://www.gnu.org/licenses/gpl.txt
-- =============================================================================
-- TABLE users
-- =============================================================================
CREATE TABLE users
(
	userId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL,
	description VARCHAR(200) NOT NULL,
	email VARCHAR(100) NOT NULL,
	password CHAR(64) NOT NULL,
	enabled CHAR(1) NOT NULL COMMENT 'Y-Yes / N-No',
	isAdmin CHAR(1) NOT NULL COMMENT 'Y-Yes / N-No',
	PRIMARY KEY(userId)
);
-- =============================================================================
-- INSERT users
-- =============================================================================
INSERT INTO users 
	(name, description, email, password, enabled, isAdmin)
VALUES 
	('admin', 'admin', 'admin@giana.com', SHA2('admin', 256), 'Y', 'Y');
-- =============================================================================
-- TABLE devices
-- =============================================================================
CREATE TABLE devices
(
	deviceId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL,
	description VARCHAR(200) NULL,
	port VARCHAR(30),
	pinType CHAR(1) COMMENT 'D-Digital / A-Analog',
	pinNumber TINYINT UNSIGNED NOT NULL,
	readOnly CHAR(1) NOT NULL COMMENT 'Y-Yes / N-No',
	fx VARCHAR(255) NOT NULL COMMENT 'F(x)',
	PRIMARY KEY (deviceId)
);
-- =============================================================================
-- INSERT devices
-- =============================================================================
INSERT INTO devices
	(name, description, port, pinType, pinNumber, readOnly, fx)
VALUES
	('LED dp13', 'Onboard LED - digital p13', 'COM13', 'D', 13, 'N', '$x'),
	('POT ap0', 'Pot - analogue p0', 'COM13', 'A', 0, 'Y', '$x');
-- =============================================================================
-- TABLE requests
-- =============================================================================
CREATE TABLE requests
(
	requestId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	userId INT UNSIGNED NOT NULL,
	port VARCHAR(30) NOT NULL,
	action CHAR(1) NOT NULL,
	pinType CHAR(1) NOT NULL COMMENT 'D-Digital / A-Analog',
	pinNumber TINYINT UNSIGNED NOT NULL,
	value INT UNSIGNED NOT NULL,
	done CHAR(1) NOT NULL COMMENT 'N-No / Y-Yes',
	dateTime DATETIME NOT NULL,
	PRIMARY KEY (requestId)
);
-- =============================================================================
-- TABLE responses
-- =============================================================================
CREATE TABLE responses
(
	requestId INT UNSIGNED NOT NULL,
	value VARCHAR(4),
	dateTime DATETIME NOT NULL,
	PRIMARY KEY (requestId)	
);
-- =============================================================================
-- TABLE schedule
-- =============================================================================
CREATE TABLE schedule
(
	scheduleId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	week CHAR(7) NOT NULL,
	`from` TIME NOT NULL,
	`to` TIME NOT NULL,
	everyMinutes TINYINT UNSIGNED NOT NULL,
	deviceId INT UNSIGNED NOT NULL,
	trueValue TINYINT UNSIGNED NOT NULL,
	falseValue TINYINT UNSIGNED NOT NULL,
	name VARCHAR(30) NOT NULL,
	lastRun DATETIME NULL,
	lastRunError CHAR(1) NULL COMMENT 'N-No / Y-Yes',
	PRIMARY KEY(scheduleId)
);
-- =============================================================================
-- TABLE schedule_conditions
-- =============================================================================
CREATE TABLE schedule_conditions
(
	scheduleId INT UNSIGNED NOT NULL,
	deviceId INT UNSIGNED NOT NULL,
	sign CHAR(1) NOT NULL COMMENT '<, > or =',
	rawValue INT UNSIGNED NOT NULL,
	PRIMARY KEY(scheduleId, deviceId, sign)
);
-- =============================================================================