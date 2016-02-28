<?php

    use core\controller;

    class ControllerFaq extends controller {
        function action_index() {
            $this->setTemplate( "template" );
            $this->setInclude( "faq" );
            $this->generate();
        }
    }