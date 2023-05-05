<?php
abstract class MongoDBConnectionService
{
    protected static $connection = null;
    protected static $selectDatabase = null;
    protected static $selectTable = null;

    public static function mongo() {
        // create connection to database
        if (!self::$connection) {
        
            try {
                self::$connection = self::createConnection('book_app');
            } catch (Exception $e) {
                echo( "error: ". $e->getMessage() );
                exit();
            }
        }
        return self::$connection;
    }

    // get database
    protected static function createConnection($selectDatabase)
	{
		return (new MongoDB\Client())->selectDatabase($selectDatabase);
	}

    // select 'users' in mongo database
    public static function selectCollection($collection) {
        return self::mongo()->selectCollection($collection);
    }
}