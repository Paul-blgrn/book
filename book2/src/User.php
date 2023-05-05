<?php
class User {
    // ---------------------------------------------------- //
    //                       VARIABLES                      //
    // ---------------------------------------------------- //

    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $firstname;
    protected $lastname;
    protected $library;
    protected $wishlist;

    // ---------------------------------------------------- //
    //                      CONSTRUCTOR                     //
    // ---------------------------------------------------- //

    public function __construct(
        $id,
        $name,
        $email,
        $password,
        $firstname,
        $lastname,
        $library,
        $wishlist
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->library = $library;
        $this->wishlist = $wishlist;
    }

    // ---------------------------------------------------- //
    //                   STATIC FUNCTIONS                   //
    // ---------------------------------------------------- //

    // -_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- //

    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //

    public static function createUser(
        $name,
        $email,
        $password,
        $firstname,
        $lastname
    ) {
        $insertDocument = MongoDBConnectionService::
            selectCollection('users')
                ->insertOne([
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'firstname' => $firstname,
                    'lastname' => $lastname
                ]);

        // Throw an error when $document is unset
        if(! $insertDocument) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }
        printf("Inserted %d document(s)\n", $insertDocument->getInsertedCount());
        var_dump($insertDocument->getInsertedId());
        return $insertDocument;
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //


    // select all users
    public static function selectUsers() {
        $result = [];

        $selectAllBooks = MongoDBConnectionService::
            selectCollection('users')
                ->find();
        
            foreach($selectAllBooks as $document) {
                $result[] = $document;
            }
        return $result;
    }

    // select user by his ID
    public static function selectUserID($id) {
        $document = MongoDBConnectionService::
            selectCollection('users')
                ->findOne([
                    '_id' => MongoUtils::makeObjectId($id),
                ]);

        // Throw an error when $document is unset
        if(! $document) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }
        if () {
            
        }

        unset($document->_id);
        unset($document->password);
        //unset($document->library);
        //unset($document->wishlist);

        return $document;
    }

     // select user by his Name
     public static function selectUserName($name) {
        $cursor = MongoDBConnectionService::
                selectCollection('users')
                    ->aggregate([
                        ['$match' => 
                            ['name' => $name]
                        ], 
                        ['$lookup' => 
                            ['from' => 'books', 
                            'localField' => 'library', 
                            'foreignField' => '_id', 
                            'as' => 'library']
                        ], 
                        ['$lookup' => 
                            ['from' => 'books', 
                            'localField' => 'wishlist', 
                            'foreignField' => '_id', 
                            'as' => 'wishlist']
                        ], 
                        ['$project' => 
                            [
                                'name' => 1, 
                                'library' => 1, 
                                'wishlist' => 1
                            ]
                        ]
                    ]);

        // Throw an error when $document is unset
        $documents = $cursor->toArray();
        if(! $documents) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }

        return $documents[0];
     }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //

    public static function updateUser($objectId, $fields) {
        MongoDBConnectionService::selectCollection('users')
            ->updateOne([
                '_id' => MongoUtils::makeObjectId($objectId),
            ], [
                '$set' => $fields
            ]);
    }

    public static function deleteBookFromLibrary($id, $bookId) {
        MongoDBConnectionService::
            selectCollection('users')
                ->updateOne([
                    '_id' => MongoUtils::makeObjectId($id),
                ], [
                    '$pull' => [
                        'library' => MongoUtils::makeObjectId($bookId),
                    ],
                ]);
    }

    public static function addBookToLibrary($userID, $bookID) {
        $updateDocument = MongoDBConnectionService::
            selectCollection('users')
                ->updateOne([
                    '_id' => MongoUtils::makeObjectId($userID),
                ], [
                    '$addToSet' => [
                        'library' => MongoUtils::makeObjectId($bookID),
                    ]
                ]);
        return $updateDocument;
    }

    public static function deleteBookFromWishlist($id, $bookId) {
        MongoDBConnectionService::
            selectCollection('users')
                ->updateOne([
                    '_id' => MongoUtils::makeObjectId($id),
                ], [
                    '$pull' => [
                        'wishlist' => MongoUtils::makeObjectId($bookId),
                    ],
                ]);
    }

    public static function addBookToWishlist($userID, $bookID) {
        $updateDocument = MongoDBConnectionService::
            selectCollection('users')
                ->updateOne([
                    '_id' => MongoUtils::makeObjectId($userID),
                ], [
                    '$addToSet' => [
                        'wishlist' => MongoUtils::makeObjectId($bookID),
                    ]
                ]);
        return $updateDocument;
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //

    public static function deleteUser($id) {
        // delete user
        $deleteDocument = MongoDBConnectionService::
            selectCollection('users')
                ->deleteOne([
                    '_id' => MongoUtils::makeObjectId($id),
                ]);

        // Throw an error when $document is unset
        if(! $deleteDocument) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }

        printf("Deleted %d document(s)\n", $deleteDocument->getDeletedCount());
        var_dump($deleteDocument->getDeletedCount());
        //header("Location: /book2/book");
        return $deleteDocument;
    }
}