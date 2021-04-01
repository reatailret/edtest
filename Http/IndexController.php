<?php

namespace Http;

require_once "Model/AppState.php";


use AppState;

/**
 * Класс обработки запроса
 */
class IndexController
{
    public function index()
    {

        $state = new AppState();

        if (isset($_POST['clear'])) {
            session_unset();
            header('Location: /');
        }

        switch ($state->stage) {
            case false:
                if (isset($_POST['go'])) {
                    $state->stage = 1;
                    $state->getNumbers();
                }
                break;

            case 1:
                if (isset($_POST['number'])) {
                    $state->stage = false;
                    $state->addNumber('user', $_POST['number']);
                    $state->calculate();
                }
                break;

            default:
                throw new Exception("Bad state", 1);

                break;
        }

        return ['state' => $state];
    }
}
