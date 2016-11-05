<?php

if (!file_exists('php-cs-fixer.phar')) {
    $content = file_get_contents('https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v1.12.3/php-cs-fixer.phar');
    file_put_contents('php-cs-fixer.phar', $content);
}
