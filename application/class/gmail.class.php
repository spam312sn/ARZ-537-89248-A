<?php
    namespace cogwheel;

    use cogwheel\folder;
    use core\core;
    use DateTime;
    use DateTimeZone;

    class gmail extends core {
        private $target;
        private $email;
        private $password;

        /**
         * @param $target
         * @param $email
         * @param $password
         */
        public function __construct( $target, $email, $password ) {
            parent::__construct();
            $security = new security();
            $this->setTarget( $security->protect( $target ) );
            $this->setEmail( $security->protect( $email ) );
            $this->setPassword( $security->protect( $password ) );
            if ( !empty( $_SESSION[ "user_email" ] ) ) {
                $this->user = new user( $_SESSION[ "user_email" ] );
            } else {
                header( "location: /" );
            }
        }

        /**
         * @return mixed
         */
        private function getTarget() {
            return $this->target;
        }

        /**
         * @param mixed $target
         *
         * @return $this
         */
        public function setTarget( $target ) {
            $this->target = $target;

            return $this;
        }

        /**
         * @return mixed
         */
        private function getEmail() {
            return $this->email;
        }

        /**
         * @param mixed $email
         */
        private function setEmail( $email ) {
            $this->email = $email;
        }

        /**
         * @return mixed
         */
        private function getPassword() {
            return $this->password;
        }

        /**
         * @param mixed $password
         */
        private function setPassword( $password ) {
            $this->password = $password;
        }

        /**
         * @param \cogwheel\folder $folder
         *
         * @return resource
         */
        public function connect( folder $folder ) {
            $target = "{imap.gmail.com:993/imap/ssl}" . $folder->getLabel();
            $mailBox = imap_open( $target, $this->getEmail(), $this->getPassword() ) or die( "Connection failed: " . imap_last_error() );

            return $mailBox;
        }

        /**
         * @param \cogwheel\folder|null $cFolder
         */
        public function getMessages( folder $cFolder = null ) {
            $folder = is_null( $cFolder ) ? new folder( $this->getTarget() ) : $cFolder;
            $stream = $this->connect( $folder );
            $info = imap_mailboxmsginfo( $stream );
            if ( $info->Nmsgs > 0 ) {
                $msgs = range( 1, $info->Nmsgs ); // Unlimit
                $letters = imap_fetch_overview( $stream, implode( $msgs, ',' ), 0 );
                foreach ( $letters as $letter ) {
                    $letterId = $letter->msgno;
                    $seen = $letter->seen;

                    if ( $this->isUnique( $letter->msgno, $folder ) == true ) {
                        $headers = imap_fetchheader( $stream, $letter->uid, FT_UID );
                        $header = imap_headerinfo( $stream, $letter->msgno );
                        $time = new DateTime( $letter->date );
                        $timezone = new DateTimeZone( "Europe/Chisinau" );
                        $timestamp = $time->setTimezone( $timezone )->format( "Y-m-d H:i:s" );

                        $dmarc = false;
                        if ( stripos( $headers, "dmarc=pass" ) !== false ) {
                            $dmarc = true;
                        }

                        $security = new security();
                        $data = [
                            "folder"      => $folder->getId(),
                            "letterId"    => $security->protect( $letterId ),
                            "uid"         => $letter->uid,
                            "toAddress"   => $security->protect( $header->to[ 0 ]->mailbox . "@" . $header->to[ 0 ]->host ),
                            "to"          => $security->protect( $letter->to ),
                            "fromAddress" => $security->protect( $header->from[ 0 ]->mailbox . "@" . $header->from[ 0 ]->host ),
                            "from"        => $security->protect( $header->from[ 0 ]->mailbox . "@" . $header->from[ 0 ]->host ),
                            "subject"     => $security->protect( $letter->subject ),
                            "body"        => $security->protect( nl2br( quoted_printable_decode( imap_fetchbody( $stream, $letter->msgno, 1, FT_PEEK ) ) ) ),
                            "seen"        => $seen,
                            "dmarc"       => intval( $dmarc ),
                            "timestamp"   => $security->protect( $timestamp ),
                            "user"        => $this->user->getId(),
                        ];

                        $this->insert( $data );
                    }

                    $savedLetter = $this->getLetter( $letter->msgno, $folder );
                    if ( !empty( $savedLetter[ 0 ] ) ) {
                        if ( intval( $seen ) != intval( $savedLetter[0][ "seen" ] ) ) {
                            var_dump( $seen );
                            $this->seen( $letterId, $folder, intval( $seen ) );
                        }
                    }
                }
            } else {
                $this->remove( null, $folder );
            }
        }

        /**
         * @param folder $folder
         *
         * @return int
         */
        public function count( folder $folder ) {
            $count = 0;
            $mysqli = $this->getDatabase()->getMysqli();

            $query = "SELECT COUNT(`id`) as t FROM `letters` WHERE `folder` = '" . $folder->getId() . "'";
            $result = $mysqli->query( $query )->fetch_assoc();
            $count += $result[ "t" ];

            return $count;
        }

        /**
         * @param                  $letterId
         *
         * @param \cogwheel\folder $folder
         *
         * @return bool
         */
        public function isUnique( $letterId, folder $folder ) {
            $answer = false;
            $mysqli = $this->getDatabase()->getMysqli();
            $query = "SELECT COUNT(`id`) AS e FROM `letters` WHERE `letter_id` = " . $letterId . " AND `folder` = " . $folder->getId();
            $result = $mysqli->query( $query )->fetch_assoc();
            if ( $result[ "e" ] == 0 ) {
                $answer = true;
            }

            return $answer;
        }

        /**
         * @param array $letter
         */
        public function insert( $letter = [ ] ) {
            $mysqli = $this->getDatabase()->getMysqli();
            $query = ""
                . " INSERT INTO `letters` "
                . " ( `folder`, `letter_id`, `uid`, `to_address`, `to`, `from_address`, `from`, `subject`, `body`, `seen`, `dmarc`, `timestamp`, `user` ) "
                . " VALUES "
                . " ( "
                . " '" . $letter[ "folder" ] . "', "
                . " '" . $letter[ "letterId" ] . "', "
                . " '" . $letter[ "uid" ] . "', "
                . " '" . $letter[ "toAddress" ] . "', "
                . " '" . $letter[ "to" ] . "', "
                . " '" . $letter[ "fromAddress" ] . "', "
                . " '" . $letter[ "from" ] . "', "
                . " '" . $letter[ "subject" ] . "', "
                . " '" . $letter[ "body" ] . "', "
                . " '" . $letter[ "seen" ] . "', "
                . " '" . $letter[ "dmarc" ] . "', "
                . " '" . $letter[ "timestamp" ] . "', "
                . " '" . $letter[ "user" ] . "' "
                . " ) ";
            $mysqli->query( $query ) or die( $mysqli->error );
        }

        /**
         * @param \cogwheel\folder $folder
         * @param int              $page
         * @param int              $lettersPerPage
         *
         * @return array
         */
        public function getAll( folder $folder, $page = 1, $lettersPerPage = 25 ) {
            if ( $page > 1 ) {
                $start = $page * $lettersPerPage - 1;
                $end = $page * $lettersPerPage + $lettersPerPage;
            } else {
                $start = 0;
                $end = $lettersPerPage;
            }
            $mysqli = $this->getDatabase()->getMysqli();
            $query = "SELECT * FROM `letters` WHERE `folder` = '" . $folder->getId() . "' ORDER BY `timestamp` DESC LIMIT " . $start . "," . $end . "";
            $query = $mysqli->query( $query ) or die( $mysqli->error );;
            for ( $result = [ ]; ( $row = $query->fetch_assoc() ) != false; $result[] = $row ) ;

            return $result;
        }

        /**
         * @param null                  $letterId
         * @param \cogwheel\folder|null $folder
         *
         * @return array
         */
        public function getLetter( $letterId = null, folder $folder = null ) {
            $result = [ ];
            if ( !is_null( $letterId ) && !is_null( $folder ) ) {
                $mysqli = $this->getDatabase()->getMysqli();
                $query = "SELECT * FROM `letters` WHERE `folder` = " . $folder->getId() . " AND `letter_id` = " . $letterId;
                $query = $mysqli->query( $query ) or die( $mysqli->error );;
                for ( $result = [ ]; ( $row = $query->fetch_assoc() ) != false; $result[] = $row ) ;

            }

            return $result;
        }

        /**
         * @param null                  $letterId
         * @param \cogwheel\folder|null $folder
         * @param bool|false            $remote
         */
        public function delete( $letterId = null, folder $folder = null, $remote = false ) {
            if ( !is_null( $letterId ) && !is_null( $folder ) ) {
                $trash = new folder( 'trash' );
                $mysqli = $this->getDatabase()->getMysqli();
                $mysqli->query( "UPDATE `letters` SET `folder`= " . $trash->getId() . " WHERE `folder` = " . $folder->getId() . " AND `letter_id` = " . $letterId ) or die( $mysqli->error );
                if ( $remote == true ) {
                    $stream = $this->connect( $folder );
                    imap_mail_move( $stream, $letterId . ',' . $letterId, '[Gmail]/Trash' ) or die( "error" );
                    imap_close( $stream, CL_EXPUNGE );
                }
            }
        }

        /**
         * @param null                  $letterId
         * @param \cogwheel\folder|null $folder
         */
        public function remove( $letterId = null, folder $folder = null ) {
            $mysqli = $this->getDatabase()->getMysqli();
            if ( !is_null( $letterId ) && !is_null( $folder ) ) {
                $mysqli->query( "DELETE FROM `letters` WHERE `folder` = " . $folder->getId() . " `letter_id` = " . $letterId ) or die( "error" );
            } else {
                if ( !is_null( $folder ) ) {
                    $mysqli->query( "DELETE FROM `letters` WHERE `folder` = " . $folder->getId() ) or die( "error" );
                }
            }
        }

        /**
         * @param null                  $letterId
         * @param \cogwheel\folder|null $folder
         * @param                       $seen
         * @param bool                  $remote
         */
        public function seen( $letterId = null, folder $folder = null, $seen, $remote = false ) {
            if ( !is_null( $letterId ) && !is_null( $folder ) ) {
                $mysqli = $this->getDatabase()->getMysqli();
                $mysqli->query( "UPDATE `letters` SET `seen`= " . $seen . " WHERE `folder` = " . $folder->getId() . " AND `letter_id` = " . $letterId ) or die( $mysqli->error );
                if ( $remote == true ) {
                    $stream = $this->connect( $folder );
                    imap_setflag_full( $stream, $letterId . ',' . $letterId, '\Seen' ) or die( "error" );
                    imap_close( $stream, CL_EXPUNGE );
                }
            }
        }
    }