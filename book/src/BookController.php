<?php
class BookController {

    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //
    public function createBook() {
        if(empty($_POST)) {
            throw new MyException('Accèss forbidden', 403);
        }

        // create book
        if (
            isset($_POST['title']) &&
            isset($_POST['description']) &&
            isset($_POST['content']) &&
            isset($_POST['author_id'])
            ) {
            return book::createBook(
                $_POST['title'],
                $_POST['description'],
                $_POST['content'],
                $_POST['author_id']
            );
        }

        throw new MyException("Parameter not found, try another one", 404);

    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //

    public function showAllBooks() {
        return Book::showAllBooksJSON();
    }

    public function showBook() {
        if(empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a parameter', 404);
        }

        if (isset($_GET['title'])) {
            return $this->showByTitle($_GET['title']);
        } 

        throw new MyException("Parameter not found, try another one", 404);
    }

    public function showByTitle($bookName) {
        if ($bookName != null || $bookName != "" || $bookName != " " || !empty($bookName)) {
            return Book::showBook($bookName);
        }
        
        throw new MyException("Empty Title", 404);
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //
    public function updateBook() {
        if(empty($_POST) || !isset($_POST['id'])) {
            throw new MyException('Accèss forbidden', 403);
        }

        $authorizedFields =[
            'title', 
            'description',
            'content',
            'author_id',
        ];
        $fields = [];

        foreach($_POST AS $key => $value) {
            if(in_array($key, $authorizedFields)) {
                $fields[$key] = $value;
            }
        }
        
        if (!empty($fields)) {
            return Book::updateBook(
                $_POST['id'],
                $fields
            );
        }

        throw new MyException("Parameter not found, try another one", 404);
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //
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
?>