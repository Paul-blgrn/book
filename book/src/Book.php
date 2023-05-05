<?php
class Book {
    // ---------------------------------------------------- //
    //                       VARIABLES                      //
    // ---------------------------------------------------- //
    protected $id;
    protected $title;
    protected $description;
    protected $content;

    // ---------------------------------------------------- //
    //                      CONSTRUCTOR                     //
    // ---------------------------------------------------- //
    public function __construct ($id, $title, $description, $content) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
    }

    // GET
    public function getBookId() {
        return $this->id;
    }

    public function getBookTitle() {
        return $this->title;
    }

    public function getBookDescription() {
        return $this->description;
    }

    public function getBookContent() {
        return $this->content;
    }

    //SET
    public function setBookTitle($title) {
        $this->title = $title;
    }

    public function setBookDescription($description) {
        $this->description = $description;
    }

    public function setBookContent($content) {
        $this->content = $content;
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
        $content, 
        $author
    ) {
        $addBook = "INSERT INTO books (`title`, `description`, `content`, `author_id`)
                    VALUES (?, ?, ?, ?)
                    ";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($addBook);
        $bdd->execute([
            $title,
            $description,
            $content,
            $author
        ]);
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //

    // show all books
    public static function showAllBooksJSON() {
        $selectAllBooks = "SELECT `author_name` AS author, `title`, `description`, `content`
        FROM `books`
        LEFT JOIN `author` ON author.id = books.author_id";
        
        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectAllBooks);
        $bdd->execute();
        $requestBookAll = $bdd->fetchAll();

        return $requestBookAll;
    }

    // show book informations by their title
    public static function showBook($title) {
        $selectBookTitle = "SELECT books.id, `title`, `description`, `content`, `author_name`, GROUP_CONCAT(category_name ORDER BY category.id ASC) AS categories 
        FROM `books` 
        LEFT JOIN `author` ON author.id = books.author_id
        LEFT JOIN category_books AS cb ON cb.books_id = books.id
        LEFT JOIN category ON category.id = cb.category_id
        WHERE `title` = :title
        GROUP BY books.id
        ";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectBookTitle);
        $bdd->execute([
            ":title" => $title,
        ]);
        $requestBook = $bdd->fetchAll();
        
        // Si aucun livre de trouvé
        if(! $requestBook) {
            throw new MyException("Title does not exist, try another", 404);
        }

        return $requestBook;
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //

    public static function updateBook($id, $fields) {
        // SET title = 'test', name = '' ...
        $query = 'UPDATE books SET ';

        $columnsAndPlaceholders = array_map(
            fn($column) => "$column = ?", 
            array_keys($fields));

        $query .= implode(', ', $columnsAndPlaceholders);
        
        $query .= ' WHERE id = ?';

        $values = array_values($fields);
        $values[] = $id;

        $bdd = MysqlDatabaseConnectionService::get()->prepare($query);

        $bdd->execute($values);
    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //

    public static function deleteBook($id) {
        $deleteBook = "DELETE FROM books 
                         WHERE id = ?";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($deleteBook);
        $bdd->execute([
            $id
        ]);
    }
};
?>