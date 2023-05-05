<?php 
session_start();

function custom_autoloader($className) {
    // charge automatiquement toutes les classes dans le dossier lib
    // /!\/!\/!\ seulement si les fichiers ont le même nom de classe /!\/!\/!\
    include 'src/' . $className . '.php';
}
spl_autoload_register('custom_autoloader');
require 'vendor/autoload.php';
