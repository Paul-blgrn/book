<?php

class Book {
    // ---------------------------------------------------- //
    //                       VARIABLES                      //
    // ---------------------------------------------------- //

    protected $id;
    protected $title;
    protected $description;
    protected $short_description;
    protected $pages;
    protected $author;
    protected $languages;
    protected $genres;
    protected $year;
    protected $format;
    protected $publication_date;
    protected $poster;

    // ---------------------------------------------------- //
    //                      CONSTRUCTOR                     //
    // ---------------------------------------------------- //

    public function __construct(
            $id, 
            $title, 
            $description,
            $short_description, 
            $pages, 
            $author, 
            $languages, 
            $genres,
            $year,
            $format,
            $publication_date,
            $poster
        ) {

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->short_description = $short_description;
        $this->pages = $pages;
        $this->author = $author;
        $this->languages = $languages;
        $this->genres = $genres;
        $this->year = $year;
        $this->format = $format;
        $this->publication_date = $publication_date;
        $this->poster = $poster;
    }

    
    // ---------------------------------------------------- //
    //                   STATIC FUNCTIONS                   //
    // ---------------------------------------------------- //

    // -_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- //

    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //
    public static function createBook(
        $title, 
        $description, 
        $pages, 
        $authors, 
        $languages, 
        $genres,
        $year,
        $format,
        $poster
        ) {

        $insertDocument = MongoDBConnectionService::
            selectCollection('books')
                ->insertOne([
                    'title' => $title,
                    'description' => $description,
                    'short_description' => substr($description, 0, 50),
                    'pages' => $pages,
                    'authors' => $authors,
                    'languages' => $languages,
                    'genres' => $genres,
                    
                    'year' => $year,
                    'format' =>  $format,
                    
                    'publication_date' => date("d/m/Y"),
                    'poster' => $poster,
                ]);

        // if no document
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

    // show all books
    public static function showAllBooks() {
        $result = [];

        $selectAllBooks = MongoDBConnectionService::
            selectCollection('books')
                ->find();
        
            foreach($selectAllBooks as $document) {
                $result[] = $document;
            }
        return $result;
    }

    // research by ID
    public static function showBookId(string $id) {
        $document = MongoDBConnectionService::
            selectCollection('books')
                ->findOne([
                    '_id' => MongoUtils::makeObjectId($id),
                ]);

        // if no document
        if(! $document) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }
        return $document;
    }

    // research by title
    public static function showBookTitle($title) {
        $document = MongoDBConnectionService::
            selectCollection('books')
                ->findOne([
                    'title' => $title,
                ]);

        // if no document
        if(! $document) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }
        return $document;
    }


    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //

    public static function updateBook($objectId, $fields) {
        MongoDBConnectionService::
        selectCollection('books')
            ->updateOne([
                '_id' => MongoUtils::makeObjectId($objectId),
            ], [
                '$set' => $fields
            ]);
    }


    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //

    public static function deleteBook($id) {
        $deleteDocument = MongoDBConnectionService::
            selectCollection('books')
                ->deleteOne([
                    '_id' => MongoUtils::makeObjectId($id),
                ]);
        // if no document
        if(! $deleteDocument) {
            throw new MyException('Empty document or unmatching parameters', 404);
        }

        printf("Deleted %d document(s)\n", $deleteDocument->getDeletedCount());
        var_dump($deleteDocument->getDeletedCount());
        header("Location: /book2/book");
        return $deleteDocument;
    }
};