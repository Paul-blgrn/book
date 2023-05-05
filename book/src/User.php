<?php
class User {
    // ---------------------------------------------------- //
    //                       VARIABLES                      //
    // ---------------------------------------------------- //
    private $id;
    private $name;
    private $email;
    private $password;
    private $created_at;

    // ---------------------------------------------------- //
    //                      CONSTRUCTOR                     //
    // ---------------------------------------------------- //
    public function __construct($id, $name, $email, $password, $created_at) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
    }


    // GET
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getCreationDate() {
        return $this->created_at;
    }

    // SET
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setCreationDate($created_at) {
        $this->created_at = $created_at;
    }

    // ---------------------------------------------------- //
    //                   STATIC FUNCTIONS                   //
    // ---------------------------------------------------- //

    // -_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- //

    // ---------------------------------------------------- //
    //                        CREATE                        //
    // ---------------------------------------------------- //
    public static function createUser($name, $email, $password) {
        $addUser = "INSERT INTO users (name, email, password)
                    VALUES (:name, :email, :password)";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($addUser);
        $bdd->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    // ---------------------------------------------------- //
    //                         READ                         //
    // ---------------------------------------------------- //
    public static function showUser($userName) {
        $selectUserName = "SELECT users.id, `name`, `email` 
                           FROM `users`
                           WHERE `name` = :user_name
                          ";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectUserName);
        $bdd->execute([
            ":user_name" => $userName
        ]);
        $requestUser = $bdd->fetch();

        if(! $requestUser) {
            throw new MyException("Name does not exist, try another", 404);
        }

        $userBookQuery = "SELECT `title`, `description`, `author_name`, `status`, `comment`, `note`
                          FROM possessed_books
                          LEFT JOIN books ON books.id = possessed_id
                          LEFT JOIN author ON author.id = books.author_id
                          LEFT JOIN avis ON avis.user_id = possessed_books.user_id
                          WHERE possessed_books.user_id = ?
                         ";


        $stmt = MysqlDatabaseConnectionService::get()->prepare($userBookQuery);
        $stmt->execute([$requestUser['id']]);
        $userBookQuery = $stmt->fetchAll();

        if(! $userBookQuery) {
            throw new MyException("L'utilisateur recherché ne possède pas de livre.", 404);
        }

        $requestUser['books'] = $userBookQuery;
        return $requestUser;
    }

    // ---------------------------------------------------- //
    //                         UPDATE                       //
    // ---------------------------------------------------- //
    public static function updateUser() {

    }

    // ---------------------------------------------------- //
    //                         DELETE                       //
    // ---------------------------------------------------- //
    public static function deleteUser($id) {
        $deleteUser = "DELETE FROM users 
                         WHERE id = ?";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($deleteUser);
        $bdd->execute([
            $id
        ]);
    }

};
?>