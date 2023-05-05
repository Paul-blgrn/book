<?php
class Comment {
    // ---------------------------------------------------- //
    //                       VARIABLES                      //
    // ---------------------------------------------------- //
    protected $id;
    protected $comment;
    protected $note;

    // ---------------------------------------------------- //
    //                      CONSTRUCTOR                     //
    // ---------------------------------------------------- //
    public function __construct(
        $id,
        $comment,
        $note
    ) {
        $this->id = $id;
        $this->comment = $comment;
        $this->note = $note;
    }

    // ---------------------------------------------------- //
    //                   STATIC FUNCTIONS                   //
    // ---------------------------------------------------- //

    // -_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- //

    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //
    public static function createComment(
        $id,
        $bookID,
        $comment,
        $note
    ) {
        $insertDocument = MongoDBConnectionService::
            selectCollection('comments')
                ->insertOne([
                    'user_id' => MongoUtils::makeObjectId($id),
                    'book_id' => MongoUtils::makeObjectId($bookID),
                    'comment' => $comment,
                    'note' => $note
                    
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
    public static function showComments($id) {
        $cursor = MongoDBConnectionService::
            selectCollection('books')
                ->aggregate([
                    [
                        '$match' => 
                        [
                            '_id' => MongoUtils::makeObjectId($id)
                        ]
                    ], 
                    [
                        '$lookup' => 
                        [
                            'from' => 'comments', 
                            'localField' => '_id', 
                            'foreignField' => 'book_id', 
                            'as' => 'comments'
                        ]
                    ], 
                    [
                        '$unwind' => 
                        [
                            'path' => '$comments'
                        ]
                    ], 
                    [
                        '$lookup' => 
                        [
                            'from' => 'users', 
                            'localField' => 'comments.user_id', 
                            'foreignField' => '_id', 
                            'as' => 'comments.user'
                        ]
                    ], 
                    [
                        '$group' => 
                        [
                            '_id' => '$_id', 
                            'book' => 
                            [
                                '$mergeObjects' => '$$ROOT'
                            ], 
                            'comments' => 
                            [
                                '$push' => '$comments'
                            ]
                        ]
                    ],
                    [
                        '$project' => 
                            [
                                'book' => 1,
                                "comments._id" => 1,
                                "comments.comment" => 1,
                                "comments.note" => 1,
                                "comments.user._id" => 1,
                                "comments.user.name"=> 1,
                            ]
                    ],
                    [
                        '$addFields' => 
                        [
                            'book.comments' => '$comments'
                        ]
                    ], 
                    [
                        '$project' => 
                        [
                            '_id' => 0, 
                            'book' => 1
                        ]
                    ]
                ]);

        // Throw an error when $document is unset
        $documents = $cursor->toArray();
        if(! $documents) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }

        return $documents[0]['book'];
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //
    public static function updateComment($objectId, $fields) {
        MongoDBConnectionService::selectCollection('comments')
            ->updateOne([
                '_id' => MongoUtils::makeObjectId($objectId),
            ], [
                '$set' => $fields
            ]);
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //
    public static function deleteComment($id) {
        $deleteDocument = MongoDBConnectionService::
            selectCollection('comments')
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