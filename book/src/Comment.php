<?php
class Comment {
    protected $id;
    protected $comment;
    protected $note;


    // Comment Constructor
    public function __construct($id, $comment, $note) {
        $this->id = $id;
        $this->comment = $comment;
        $this->note = $note;
    }

    // GET
    public function getCommentId() {
        return $this->id;
    }

    public function getCommentContent() {
        return $this->comment;
    }

    public function getCommentNote() {
        return $this->note;
    }


    // SET
    public function setCommentContent($comment) {
        $this->comment = $comment;
    }

    public function setCommentNote($note) {
        $this->note = $note;
    }

    // get all comments of book by their title and show avg note based on all user notes
    public static function showAllBookComments($title) {
        $selectAllComments = "SELECT books.id AS `book_id`, `title` as `livre`, `name` AS `username`, `comment`, `note`
        FROM `avis`
        LEFT JOIN `books` ON books.id = book_id
        LEFT JOIN `users` ON users.id = user_id
        WHERE `title` = :title
        ";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectAllComments);
        $bdd->execute([
            ":title" => $title,
        ]);
        $requestAllComments = $bdd->fetchAll();

        if (! $requestAllComments) {
            return ['error' => 'Livre non trouvé'];
        }

        $book_id = $requestAllComments[0]['book_id'];
    

        $selectAvgBook = "SELECT AVG(note) AS note_moyenne
        FROM `avis` 
        WHERE `book_id` = ?
        ";

        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectAvgBook);
        $bdd->execute([$book_id]);
        $requestAvg = $bdd->fetchColumn();

        if (! $requestAvg) {
            return [
                'title' => $title,
                'avg' => 'not found !'
            ];
        }
        return [
            'book' => [
                'title' => $title,
                'id' => $book_id,
            ],
            'commments' => $requestAllComments,
            'average_rate' => $requestAvg
        ];
    }

    // show all commented book of users by their names
    public static function showAllUserComments($commentUser) {
        $selectAllComments = "SELECT books.id AS `book_id`, `title` as `livre`, `comment`, `note`
        FROM `avis`
        LEFT JOIN `books` ON books.id = book_id
        LEFT JOIN `users` ON users.id = user_id
        WHERE users.name = :username
        ";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectAllComments);
        $bdd->execute([
            ":username" => $commentUser,
        ]);
        $requestAllyuserComments = $bdd->fetchAll();

        if (! $requestAllyuserComments) {
            return ['error' => 'Utilisateur introuvable.'];
        }

        //return $requestAllyuserComments;

        return [
            'users' => [
                'name' => $commentUser,
            ],
            'commments' => $requestAllyuserComments,
        ];
    }
};
?>