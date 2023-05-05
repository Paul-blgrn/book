<?php
class CommentController {
    // show all comments by book title
    public function showAllComments() {
        $commentBook = $_GET['title'];
        if ($commentBook != null || $commentBook != "") {
            echo json_encode(Comment::showAllBookComments($commentBook), JSON_PRETTY_PRINT);
        } else {
            echo "Aucune note sur ce livre.";
        }
    }

    // show all comment by user names
    public function showAllUserComments() {
        $commentUser = $_GET['user'];
        if ($commentUser != null || $commentUser != "") {
            echo json_encode(Comment::showAllUserComments($commentUser), JSON_PRETTY_PRINT);
        } else {
            echo "Aucun Commentaires publié pour cet utilisateur.";
        }
    }
}