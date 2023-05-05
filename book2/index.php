<?php
include_once 'bootstrap.php';

$localpath = "/book2";

$routes = [
    // ROUTE SHEMA
    // new Route($localpath .'', '', ''),

    // BOOKS ROUTE
    new Route($localpath . '/books', 'BookController', 'showAllBooks'),
    new Route($localpath . '/book', 'BookController', 'showBook'),
    
    new Route($localpath . '/book/create', 'BookController', 'createBook'),
    new Route($localpath . '/book/delete', 'BookController', 'deleteBook'),
    new Route($localpath . '/book/update', 'BookController', 'updateBook'),
    
    // COMMENT ROUTE
    new Route($localpath . '/book/comments', 'CommentController', 'showComments'),
    new Route($localpath . '/book/comment/add', 'CommentController', 'createComment'),
    new Route($localpath . '/book/comment/delete', 'CommentController', 'deleteComment'),
    new Route($localpath . '/book/comment/update', 'CommentController', 'updateComment'),

    // USERS ROUTE
    new Route($localpath . '/users', 'UserController', 'showUsers'),
    new Route($localpath . '/user', 'UserController', 'showUser'),
    new Route($localpath . '/user/create', 'UserController', 'createUser'),
    new Route($localpath . '/user/delete', 'UserController', 'deleteUser'),
    new Route($localpath . '/user/update', 'UserController', 'updateUser'),

    // LIBRARY ROUTE
    new Route($localpath . '/user/library', 'UserController', 'deleteBookLibrary'),
    new Route($localpath . '/user/library/add', 'UserController', 'addBookLibrary'),

    // WISHLIST ROUTE
    new Route($localpath . '/user/wishlist', 'UserController', 'deleteBookWishlist'),
    new Route($localpath . '/user/wishlist/add', 'UserController', 'addBookWishlist'),
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
    // throw $e;
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