<?php

namespace Promptopolis\Framework;

abstract class Db
{
    private static $conn;

    private static function getConfig()
    {
        // get the config file
        return parse_ini_file(__DIR__ . "/config/config.ini");
    }


    public static function getInstance()
    {
        if (self::$conn != null) {
            // REUSE our connection
            return self::$conn;
        } else {
            // CREATE a new connection

            // get the configuration for our connection from one central settings file
            $config = self::getConfig();
            $database = $config['database'];
            $user = $config['user'];
            $password = $config['password'];
            $host = $config['host'];
            $ssl_ca = $config['ssl_ca']; // Add this line to retrieve the SSL certificate path

            $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";

            // Create the PDO connection with SSL options
            self::$conn = new \PDO($dsn, $user, $password, [
                \PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
                \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ]);

            return self::$conn;
        }
    }
}
