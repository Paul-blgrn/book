<?php
class CommentController {
    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //
    public function createComment() {
        // throw an error when $_POST is empty
        if(empty($_POST) && !isset($_GET['id']) && !isset($_GET['bookid'])) {
            throw new MyException('Accèss forbidden', 403);
        }

        if (
            isset($_GET['id']) &&
            isset($_GET['bookid']) &&
            isset($_POST['comment']) &&
            isset($_POST['note'])
        ) {
            return Comment::createComment(
                $_GET['id'],
                $_GET['bookid'],
                $_POST['comment'], 
                $_POST['note']
            );
        }

        // throw an error when parameter doesn't match with result
        throw new MyException("Parameter not found, try another one", 404);
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //
    public function showComments() {
        // throw an error when parameters are missing or empty
        if (empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a parameter', 404);
        }

        // show user information by his ID
        if (isset($_GET['id'])) {
            return $this->showCommentID($_GET['id']);
        }

        // throw an error when parameters don't correlate with the result
        throw new MyException("Parameter not found, try another one", 404);
    }

    public function showCommentID($getID) {
        if ($getID != null || $getID != "" || $getID != " " || !empty($getID)) {
            return Comment::showComments($getID);
        }
        throw new MyException('Requested Name is empty', 404);
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //
    public function updateComment() {
        // throw an error when $_POST or $_GET['id'] are empty
        if(empty($_POST) || !isset($_GET['id'])) {
            throw new MyException('Accèss forbidden, empty $_POST or ID', 403);
        }

        $authorizedFields = [
            'comment',
            'note'
        ];
        $fields = [];
        foreach($_POST AS $key => $value) {
            if(in_array($key, $authorizedFields)) {
                $fields[$key] =$value;
            }
        }
        return Comment::updateComment(
            $_GET['id'],
            $fields
        );
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //
    public function deleteComment() {
        if(empty($_GET)) {
            throw new MyException('Missing parameter(s), you must specify a valid parameter (ID)', 404);
        }

        // delete user by his ID
        if (isset($_GET['id']) != null || isset($_GET['id']) != "" || isset($_GET['id']) != " " || !empty($_GET['id'])) {
            return Comment::deleteComment($_GET['id']);
        }

        throw new MyException("Parameter not found, try another one", 404);
    }
}