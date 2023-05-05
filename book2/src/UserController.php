<?php
class UserController {
    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //

    public function createUser(){
        // throw an error when $_POST is empty
        if(empty($_POST)) {
            throw new MyException('Accèss forbidden', 403);
        }

        // send creation script when all $_POST are set
        if (
            isset($_POST['name']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['firstname']) &&
            isset($_POST['lastname'])
        ) {
            return User::createUser(
                $_POST['name'],
                $_POST['email'],
                $_POST['password'],
                $_POST['firstname'],
                $_POST['lastname']
            );
        }

        // throw an error when parameter doesn't match with result
        throw new MyException("Parameter not found, try another one", 404);
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //

    public function showUsers() {
        // show all users
        echo json_encode(User::selectUsers(), JSON_PRETTY_PRINT);
    }

    public function showUser() {
        // throw an error when parameters are missing or empty
        if (empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a parameter', 404);
        }

        // show user information by his ID
        if (isset($_GET['id'])) {
            return $this->showUserId($_GET['id']);
        }

        // show user information by his Name
        if (isset($_GET['name'])) {
            return $this->showUserName($_GET['name']);
        }

        // throw an error when parameters don't correlate with the result
        throw new MyException("Parameter not found, try another one", 404);
    }

    // show user information by his ID
    public function showUserId($getID) {
        if ($getID != null || $getID != "" || $getID != " " || !empty($getID)) {
            return User::selectUserID($getID);
        }
        throw new MyException('Requested ID is empty', 404);
    }

    // show user information by his Name
    public function showUserName($getName) {
        if ($getName != null || $getName != "" || $getName != " " || !empty($getName)) {
            return User::selectUserName($getName);
        }
        throw new MyException('Requested Name is empty', 404);
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //

    public function updateUser(){
        // throw an error when $_POST or $_GET['id'] are empty
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden, empty $_POST or ID', 403);
        }

        $authorizedFields = [
            'name',
            'password',
            'firstname',
            'lastname'
        ];
        $fields = [];
        foreach($_POST AS $key => $value) {
            if(in_array($key, $authorizedFields)) {
                $fields[$key] = $key === 'password' 
                    ? password_hash($value, PASSWORD_DEFAULT)
                    : $value;

                //printf($value);
            }
        }
        return User::updateUser(
            $_GET['id'],
            $fields
        );
    }

    public function deleteBookLibrary() {
        // throw an error when $_POST or $_GET['id'] are empty
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden, empty $_POST or invalid ID', 403);
        }
        $getID = $_GET['id'];
        $getBookID = $_POST['bookid'];
        
        return User::deleteBookFromLibrary(
            $getID,
            $getBookID
        );

    }

    public function addBookLibrary() {
        // throw an error when $_POST or $_GET['id'] are empty
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden, empty $_POST or invalid ID', 403);
        }
        
        return User::addBookToLibrary(
            $_GET['id'],
            $_POST['bookid']
        );

    }

    public function deleteBookWishlist() {
        // throw an error when $_POST or $_GET['id'] are empty
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden, empty $_POST or invalid ID', 403);
        }
        $getID = $_GET['id'];
        $getBookID = $_POST['bookid'];
        
        return User::deleteBookFromWishlist(
            $getID,
            $getBookID
        );

    }

    public function addBookWishlist() {
        // throw an error when $_POST or $_GET['id'] are empty
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden, empty $_POST or invalid ID', 403);
        }
        
        return User::addBookToWishlist(
            $_GET['id'],
            $_POST['bookid']
        );

    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //

    public function deleteUser() {
        if(empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a valid parameter (ID)', 404);
        }

        // delete user by his ID
        if (isset($_GET['id']) != null || isset($_GET['id']) != "" || isset($_GET['id']) != " " || !empty($_GET['id'])) {
            return User::deleteUser($_GET['id']);
        }

        throw new MyException("Parameter not found, try another one", 404);
    }
}