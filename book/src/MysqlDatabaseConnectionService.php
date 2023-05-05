<?php

abstract class MysqlDatabaseConnectionService
{
	protected static $connection = null;

	public static function get()
	{
		if (!self::$connection) {
			try {
				self::$connection = self::createConnection();
			} catch (PDOException $e) {
				// Log db error message
				// $e->getMessage()
				throw new Exception('Database ERROR');
			}
		}

		return self::$connection;
	}

	protected static function createConnection()
	{
		$host = 'localhost';
		$port = 3306;
		$database = 'book';
		$user = 'root';
		$password = '';
		$dsn = "mysql:host={$host}:{$port};dbname={$database}";

		return new PDO($dsn, $user, $password, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		]);
	}
}