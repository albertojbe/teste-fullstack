<?php

class HomeController extends AppController {

    public function index() {
        $this->set('mensagem', 'Minha primeira página no CakePHP 2!');
    }

}