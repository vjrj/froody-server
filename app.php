<?php

// Some Application inits
mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');

/**
 * Get settings from config.php
 *
 * @param bool $refresh Refresh from file if true
 *
 * @return array Array of settings defined in config.php
 */
function getSettings($refresh = false)
{
    static $settings;

    if ($settings === null || $refresh) {
        $settings = require PROOT . '/config.php';
    }

    return $settings;
}

/**
 * Returns a MySQLi database connection or null if couldn't connect
 *
 * @return mysqli
 */
function getDB()
{
    static $connection;

    $appSettings = getSettings();

    date_default_timezone_set('UTC');
    mb_internal_encoding('UTF-8');

    if ($connection === null) {
        //$connection = @new mysqli("$dbhost", "$dbuser", "$dbpass", "$dbname");
        $connection = @new mysqli($appSettings['db']['host'], $appSettings['db']['user'], $appSettings['db']['pass'], $appSettings['db']['name']);
    }

    // If connection was not successful, handle the error
    if ($connection->connect_errno) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        //file_put_contents('php://stderr', 'Error: Cannot connect to database ' . $dbname . ' on ' . $dbhost);
        file_put_contents('php://stderr', 'Error: Cannot connect to database ' . $appSettings['db']['dbname'] . ' on ' . $appSettings['db']['dbhost']);

        return null;
    }

    $connection->set_charset($appSettings['db']['encoding']);
    //$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    //$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $connection;
}
