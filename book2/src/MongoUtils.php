<?php

// import
use MongoDB\BSON\ObjectId;

class MongoUtils {
    public static function makeObjectId(string $id) {
        try {
            return new ObjectId($id);
        } catch(InvalidArgumentException $e) {
            throw new MyException('Invalid ID format ', 503);
        }

    }
}