<?php
class Route {
  private $path;
  private $controller;
  private $action;

  public function __construct($path, $controller, $action) {
    $this->path = $path;
    $this->controller = $controller;
    $this->action = $action;
  }

  public function match($url) {
    return preg_match("#^{$this->path}$#", $url);
  }

  public function run() {
    require_once("{$this->controller}.php");
    $controller = new $this->controller();
    return $controller->{$this->action}();
  }
}
?>