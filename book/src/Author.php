<?php
class Author {
    // ---------------------------------------------------- //
    //                       VARIABLES                      //
    // ---------------------------------------------------- //
    protected $id;
    protected $authorName;

    // ---------------------------------------------------- //
    //                      CONSTRUCTOR                     //
    // ---------------------------------------------------- //
    public function __construct ($id, $authorName) {
        $this->id = $id;
        $this->authorName = $authorName;
    }

    //GET
    public function getAuthorId() {
        return $this->id;
    }
    public function getAuthorName() {
        return $this->authorName;
    }

    //SET
    public function setAuthorName($authorName) {
        $this->authorName = $authorName;
    }


    // ---------------------------------------------------- //
    //                   STATIC FUNCTIONS                   //
    // ---------------------------------------------------- //

    // -_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- //

    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //

    public static function createAuthor($name) {
        $addAuthor = "INSERT INTO author (`author_name`)
                      VALUES (?)";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($addAuthor);
        $bdd->execute([
            $name
        ]);
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //

    // show all authors
    public static function showAllAuthors() {
        $selectAllAuthors = "SELECT author_name FROM `author`";

        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectAllAuthors);
        $bdd->execute();
        $requestAuthorAll = $bdd->fetchAll();
        
        return $requestAuthorAll;
    }

    // show books informations related by author names
    public static function showAuthor($authorName) {
        // fetch author name
        $selectAuthorName = "SELECT author.id, author_name AS author
        FROM `author`
        WHERE author_name = :author_name";

        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectAuthorName);
        $bdd->execute([
            ":author_name" => "$authorName",
        ]);
        $requestAuthor = $bdd->fetch();

        // Si aucun auteur de trouvé
        if(! $requestAuthor) {
            throw new MyException("Name does not exist, try another", 404);
        }

        // Fetch authors books
        $authorBooksQuery = "SELECT books.id, `title` AS titre, `description`, GROUP_CONCAT(DISTINCT(category_name) ORDER BY category.id ASC SEPARATOR ',') AS categories, publisher_name AS publisher, GROUP_CONCAT(DISTINCT(`type`) ORDER BY format.id ASC SEPARATOR ',') AS format
        FROM books
        LEFT JOIN category_books AS cb ON cb.books_id = books.id
        LEFT JOIN category ON category.id = cb.category_id 
        LEFT JOIN published_books AS pb ON pb.published_book_id = books.id
        LEFT JOIN publisher ON publisher.id = pb.publisher_id
        LEFT JOIN format ON format.id = pb.format_id
        WHERE author_id = ?
        GROUP BY books.id
        ";

        $stmt = MysqlDatabaseConnectionService::get()->prepare($authorBooksQuery);
        $stmt->execute([$requestAuthor['id']]);
        $requestBooks = $stmt->fetchAll();

        // Si aucun livre de trouvé dans auteur
        if(! $requestBooks) {
            throw new MyException("L'auteur recherché ne possède pas de livre.", 404);
        }
        $requestAuthor['books'] = $requestBooks;


        return $requestAuthor;
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //

    public static function updateAuthor($id, $name) {
        $updateAuthor = "UPDATE `author` 
                         SET `author_name`= :author_name 
                         WHERE `id` = :id
                         ";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($updateAuthor);
        $bdd ->execute([
            "author_name" => $name,
            ":id" => $id
        ]);
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //

    public static function deleteAuthor($id) {
        $deleteAuthor = "DELETE FROM author 
                         WHERE id = ?";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($deleteAuthor);
        $bdd->execute([
            $id
        ]);
    }

};
?>