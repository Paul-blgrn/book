<?php
class Publisher {
    protected $id;
    protected $publisherName;

    // CONSTRUCTOR
    public function __construct($id, $publisherName) {
        $this->id = $id;
        $this->publisherName = $publisherName;
    }

    // GET
    public function getPublisherId() {
        return $this->id;
    }

    public function getPublisherName() {
        return $this->publisherName;
    }


    // SET
    public function setPublisherName($publisherName) {
        $this->publisherName = $publisherName;
    }

    // BDD

    // get all publisher names
    public static function showPublishers() {
        $selectAllPublishers = "SELECT `publisher_name` AS `publisher` 
        FROM `publisher`";
        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectAllPublishers);
        $bdd->execute();
        $requestAllPublishers = $bdd->fetchAll();
        return $requestAllPublishers;
    }

    // show all books informations related by publisher name
    public static function showPublisherWorks($publisherName) {
        $selectPublisherName = "SELECT published_book_id AS id, `title`, COUNT(distinct avis.comment) AS `nbr_comment`, AVG(note) AS `avg_note`
        FROM `publisher`
        LEFT JOIN published_books AS pb ON pb.publisher_id = publisher.id
        LEFT JOIN books ON books.id = pb.published_book_id
        LEFT JOIN avis ON avis.book_id = books.id
        WHERE publisher_name = :publisher_name
        GROUP BY books.id
        ";

        $bdd = MysqlDatabaseConnectionService::get()->prepare($selectPublisherName);
        $bdd->execute([
            ":publisher_name" => $publisherName,
        ]);
        $requestPublisherName = $bdd->fetchAll();

        // si l'éditeur n'existe pas
        if (! $requestPublisherName) {
            return [
                'error' => 'Ohh no ! no publisher found !'
            ];
        }

        return [
            'publisher' => [
                'name' => $publisherName,
            ],
            'book' => $requestPublisherName,
        ];
    }
}