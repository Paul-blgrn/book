<?php
class PublisherController {
    public function showAllPublishers() {
        echo json_encode(Publisher::showPublishers());
    }

    public function showPublisherWorksByName() {
        $researchValuePublisher = $_GET['name'];
        if ($researchValuePublisher != null || $researchValuePublisher != "") {
            echo json_encode(Publisher::showPublisherWorks($researchValuePublisher));
        } else {
            echo 'Aucune recherche de maison d\'édition en cours.';
        }
    }
}