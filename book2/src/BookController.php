<?php
class BookController {

    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //

    // create book
    public function createBook() {
        if(empty($_POST)) {
            throw new MyException('Accèss forbidden', 403);
        }

        if (
            isset($_POST['title']) && 
            isset($_POST['description']) && 
            isset($_POST['pages']) && 
            isset($_POST['authors']) && 
            isset($_POST['languages']) && 
            isset($_POST['genres']) &&
            isset($_POST['year']) &&
            isset($_POST['format']) &&
            isset($_POST['poster']
            )) {

            return Book::createBook(
                $_POST['title'], 
                $_POST['description'], 
                $_POST['pages'], 
                $_POST['authors'], 
                $_POST['languages'], 
                $_POST['genres'],
                $_POST['year'],
                $_POST['format'],
                $_POST['poster']
            );
        }
        throw new MyException("Parameter not found, try another one", 404);
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //

    // show all books
    public function showAllBooks() {
        echo json_encode(Book::showAllBooks(), JSON_PRETTY_PRINT);
        //var_dump(Book::showAllBooks());
    }

    public function showbook() {
        if(empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a parameter', 404);
        }


        if (isset($_GET['id'])) {
            return $this->showBookId($_GET['id']);
        } 
        
        if (isset($_GET['title'])) {
           return $this->showBookTitle($_GET['title']);
        } 

        throw new MyException("Parameter not found, try another one", 404);
    }

    public function showBookId($getID) {
        if ($getID != null || $getID != "" || $getID != " " || !empty($_GET['id'])) {
            return Book::showBookId($getID);
        } 
        
        throw new MyException('empty ID', 404);
        
    }

    public function showBookTitle($getTitle) {
        if ($getTitle != null || $getTitle != "" || $getTitle != " " || !empty($_GET['title'])) {
            return Book::showBookTitle($getTitle);
        }

        throw new MyException('empty title', 404);
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //

    //update book

    public function updateBook() {
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden', 403);
        }

        $authorizedFields =[
            'title', 
            'description',
            'pages',
            'authors',
            'languages',
            'genres',
            'year',
            'format',
            'poster'
        ];
        $fields = [];
        foreach($_POST AS $key => $value) {
            if(in_array($key, $authorizedFields)) {
                $fields[$key] = $value;
            }
        }

            return Book::updateBook(
                $_GET['id'],
                $fields
            );

        throw new MyException("Parameter not found, try another one", 404);
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //
    
    // delete book
    public function deleteBook() {
        if(empty($_GET)) {
            throw new MyException('Accèss forbidden', 403);
        }

        if (isset($_GET['id']) != null || isset($_GET['id']) != "" || isset($_GET['id']) != " " || !empty($_GET['id'])) {
            return Book::deleteBook($_GET['id']);
        }

        throw new MyException("Parameter not found, try another one", 404);
    }

};