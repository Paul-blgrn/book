<?php
class User {
    private $id;
    private $name;
    private $email;
    private $password;
    private $created_at;

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

    
};
?>