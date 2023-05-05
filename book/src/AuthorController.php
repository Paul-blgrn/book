<?php
class AuthorController {
    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //

    public function createAuthor() {
        if(empty($_POST)) {
            throw new MyException('Accèss forbidden', 403);
        }

        // create author if $_POST['name'] is set
        if (isset($_POST['name'])) {
            return Author::createAuthor($_POST['name']);
        }

        throw new MyException("Parameter not found, try another one", 404);
    } 

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //

    // show all author names
    public function showAllAuthors() {
        echo json_encode(Author::showAllAuthors());
        //var_dump(Author::showAllAuthors());
    }

    public function showAuthor() {
        if(empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a parameter', 404);
        }
        
        if (isset($_GET['name'])) {
            return $this->showAuthorWorksByName($_GET['name']);
        } 

        throw new MyException("Parameter not found, try another one", 404);
    }

    // show all book of authors by author names
    public function showAuthorWorksByName($getName) {
        if ($getName != null || $getName != "" || $getName = " " || !empty($getName)) {
            //var_dump(Author::showAuthor($getName));
            return Author::showAuthor($getName);
        }

        throw new MyException("Empty Name", 404);
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //

    public function updateAuthor() {
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden', 403);
        }

        if (isset($_POST['name'])) {
            return Author::updateAuthor($_GET['id'], $_POST['name']);
        }

        throw new MyException("Parameter not found, try another one", 404);
    } 


    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //

    public function deleteAuthor() {
        if(empty($_GET)) {
            throw new MyException('Accèss forbidden', 403);
        }

        if (isset($_GET['id']) != null || isset($_GET['id']) != "" || isset($_GET['id']) != " " || !empty($_GET['id'])) {
            return Author::deleteAuthor($_GET['id']);
        }

        throw new MyException("Parameter not found, try another one", 404);
    } 

}