<?php

    use core\controller;

    class Controller404 extends controller {
        function action_index() {
            $this->setTemplate( "404.error" );
            $this->generate();
        }
    }