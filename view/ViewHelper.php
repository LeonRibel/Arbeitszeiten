<?php

namespace App\View;

use Exception;

class ViewHelper
{
    private array $debugs = [];
    function getUrl($page, $order = "id", $sort = "asc", $seite = 1)
    {
        return $page.'?sort=' . $sort . '&order=' . $order . '&seite=' . $seite;
    }

    public function fetchMessagesRecursive(mixed $messages): array {
        if(is_array($messages)) {
            $allMessages = [];
            foreach($messages as $messagePart) {
                $allMessages = array_merge($this->fetchMessagesRecursive($messagePart));
            }
            return $allMessages;
        }

        return [$messages];
    }

    function flash(string | array $message, $type = 'success') {
       
        $_SESSION['flashMessages'][$type][] = implode('<br>', $this->fetchMessagesRecursive($message));
    }

    function getFlashMessages() {
        $flashMessages = $_SESSION['flashMessages'];

        unset($_SESSION['flashMessages']);

        return $flashMessages;
    }

    function debug() {
        $this->debugs = array_merge(func_get_args());
    }
  
    function render(string $view, array $vars): string
    {
        $accept = $_SERVER['HTTP_ACCEPT'];

        if($accept === 'application/json') {
            return json_encode($vars);
        }
        
        if(!file_exists(__DIR__ . '/' . $view . '_html.phtml')) {
            throw new Exception(sprintf('View "%s" not found', $view));
        }
        extract($vars);
        ob_start();
        include __DIR__ . "/layout.phtml";
        return ob_get_clean();
    }
}
