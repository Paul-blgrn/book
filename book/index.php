<?php
include_once 'bootstrap.php';

$localpath = "/book";

$routes = [
    // ROUTE SHEMA
    // new Route($localpath .'', '', ''),

    // BOOK ROUTE
    new Route($localpath . '/books', 'BookController', 'showAllBooks'),
    new Route($localpath . '/book', 'BookController', 'showBook'),
    new Route($localpath . '/book/create', 'BookController', 'createBook'),
    new Route($localpath . '/book/update', 'BookController', 'updateBook'),
    new Route($localpath . '/book/delete', 'BookController', 'deleteBook'),

    // AUTHORS ROUTE
    new Route($localpath . '/authors', 'AuthorController', 'showAllAuthors'),
    new Route($localpath . '/author', 'AuthorController', 'showAuthor'),
    new Route($localpath . '/author/create', 'AuthorController', 'createAuthor'),
    new Route($localpath . '/author/delete', 'AuthorController', 'deleteAuthor'),
    new Route($localpath . '/author/update', 'AuthorController', 'updateAuthor'),

    // PUBLISHER ROUTE
    new Route($localpath .'/publishers', 'PublisherController', 'showAllPublishers'),
    new Route($localpath .'/publisher', 'PublisherController', 'showPublisherWorksByName'),

    // COMMENT ROUTE
    new Route($localpath .'/comments', 'CommentController', 'showAllComments'),
    new Route($localpath .'/comment', 'CommentController', 'showAllUserComments'),

    // USER ROUTE
    new Route($localpath .'/user', 'UserController', 'showUser'),
    new Route($localpath .'/user/create', 'UserController', 'createUser'),
    new Route($localpath .'/user/update', 'UserController', 'updateUser'),
    new Route($localpath .'/user/delete', 'UserController', 'deleteUser'),
];

$url = parse_url($_SERVER['REQUEST_URI'])['path'];

try {
    foreach($routes as $route) {
        if($route->match($url)){
            $result = $route->run();

            if($result) {
                echo json_encode($result, JSON_PRETTY_PRINT);
            }
            break;
        }
    }
} catch(MyException $e) {
    echo json_encode([
        'Error: ' => $e->getMessage(),
        'Code' => $e->getCode(),
        'More Infos' => [
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
        ]
    ]);
}
?>