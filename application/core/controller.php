<?php
    namespace core;

    use cogwheel\database;
    use cogwheel\user;
    use mysqli;

    class controller extends core {

        public $model;
        public $include;
        public $template;
        public $data;

        /**
         * @return mixed
         */
        public function getInclude() {
            return $this->include;
        }

        /**
         * @param mixed $include
         *
         * @return $this
         */
        public function setInclude( $include ) {
            $this->include = $include;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getTemplate() {
            return $this->template;
        }

        /**
         * @param mixed $template
         *
         * @return $this
         */
        public function setTemplate( $template ) {
            $this->template = $template;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getData() {
            return $this->data;
        }

        /**
         * @param mixed $data
         *
         * @return $this
         */
        public function setData( $data ) {
            $this->data = $data;

            return $this;
        }

        public function generate() {
            $view = new view();
            $view->generate( $this->getTemplate(), $this->getData(), $this->getInclude() );
        }

    }
