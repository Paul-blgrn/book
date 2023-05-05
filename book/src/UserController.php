<?php
class UserController {
    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //
    public function createUser() {
        if(empty($_POST)) {
            throw new MyException('Accèss forbidden', 403);
        }

        if (
            isset($_POST['name']) &&
            isset($_POST['email']) &&
            isset($_POST['password'])
            ) {
            return User::createUser(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            );
        }

        throw new MyException("Parameter not found, try another one", 404);
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //
    public function showUser() {
        if(empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a parameter', 404);
        }

        if (isset($_GET['name'])) {
            return $this->showUserInfo($_GET['name']);
        } 

        throw new MyException("Parameter not found, try another one", 404);
    }

    public function showUserInfo($getName) {
        if ($getName != null || $getName != "" || $getName = " " || !empty($getName)) {
            return User::showUser($getName);
        }
        throw new MyException("Empty Name", 404);
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //
    public function updateUser() {
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden', 403);
        }
        throw new MyException("Parameter not found, try another one", 404);
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //
    public function deleteUser() {
        if(empty($_GET)) {
            throw new MyException('Accèss forbidden', 403);
        }
        if (isset($_GET['id']) != null || isset($_GET['id']) != "" || isset($_GET['id']) != " " || !empty($_GET['id'])) {
            return user::deleteUser($_GET['id']);
        }
        throw new MyException("Parameter not found, try another one", 404);
    }
}