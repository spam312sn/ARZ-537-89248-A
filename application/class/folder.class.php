<?php
    namespace cogwheel;

    use core\core;

    class folder extends core {
        public $id;
        public $name;
        public $label;

        /**
         * @param null $name
         * @param null $id
         */
        public function __construct( $name = null, $id = null ) {
            parent::__construct();
            // TODO: Remake this shit
            if ( !is_null( $name ) ) {
                $this->setName( $name );
                $this->setId();
                $this->setLabel();
            }
            if ( !is_null( $id ) ) {
                $this->setId( $id );
                $this->setName();
                $this->setLabel();
            }
        }

        /**
         * @return mixed
         */
        public function getId() {
            return $this->id;
        }

        /**
         * @param int|string $id
         *
         * @return $this
         */
        public function setId( $id = '' ) {
            if ( !empty( $id ) ) {
                $this->id = $id;
            } else {
                if ( !empty( $this->getName() ) ) {
                    $mysqli = $this->getDatabase()->getMysqli();
                    $query = "SELECT * from `folders` WHERE `folder_name` = '" . $this->getName() . "'";
                    $query = $mysqli->query( $query );
                    for ( $result = [ ]; ( $row = $query->fetch_assoc() ) != false; $result[] = $row ) ;
                    if ( !empty( $result[ 0 ][ "id" ] ) ) {
                        $this->setId( intval( $result[ 0 ][ "id" ] ) );
                    }
                }
            }

            return $this;
        }

        /**
         * @return mixed
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param string $name
         *
         * @return $this
         */
        public function setName( $name = '' ) {
            if ( !empty( $name ) ) {
                $this->name = $name;
            } else {
                $name = '';
                if ( !empty( $this->getId() ) ) {
                    $mysqli = $this->getDatabase()->getMysqli();
                    $query = "SELECT * from `folders` WHERE `id` = " . $this->getId();
                    $query = $mysqli->query( $query ) or die( $mysqli->error );
                    for ( $result = [ ]; ( $row = $query->fetch_assoc() ) != false; $result[] = $row ) ;
                    if ( !empty( $result[ 0 ][ "folder_name" ] ) ) {
                        $name = $result[ 0 ][ "folder_name" ];
                    }
                }

                $this->setName( $name );
            }

            return $this;
        }

        /**
         * @return mixed
         */
        public function getLabel() {
            return $this->label;
        }

        /**
         * @param string $label
         */
        public function setLabel( $label = '' ) {
            if ( !empty( $label ) ) {
                $this->label = $label;
            } else {
                $label = '';
                if ( !empty( $this->getName() ) ) {
                    $mysqli = $this->getDatabase()->getMysqli();
                    $query = "SELECT * from `folders` WHERE `folder_name` = '" . $this->getName() . "'";
                    $query = $mysqli->query( $query );
                    for ( $result = [ ]; ( $row = $query->fetch_assoc() ) != false; $result[] = $row ) ;
                    if ( !empty( $result[ 0 ][ "label" ] ) ) {
                        $label = strval( $result[ 0 ][ "label" ] );
                    }
                }
                $this->setLabel( $label );
            }
        }

        /**
         * @return array
         */
        public function getAllNames() {
            $mysqli = $this->getDatabase()->getMysqli();
            $query = "SELECT `folder_name` from `folders` WHERE 1";
            $query = $mysqli->query( $query );
            for ( $result = [ ]; ( $row = $query->fetch_assoc() ) != false; $result[] = $row ) ;

            return $result;
        }
    }