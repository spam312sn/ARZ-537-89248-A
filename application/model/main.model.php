<?php
    use cogwheel\folder;
    use cogwheel\gmail;
    use cogwheel\user;
    use core\core;

    class MainModel extends core {
        private $data;

        public function __construct( gmail $gmail ) {
            parent::__construct();
            $this->gmail = $gmail;
            $this->folder = new folder();
            $folders = $this->folder->getAllNames();
            $data = [ ];
            foreach ( $folders as $folder ) {
                $folderName = $folder[ "folder_name" ];
                $folder = new folder( $folderName );
                $gmail->getMessages( $folder );
                $count = $this->gmail->count( $folder );
                $data[ $folderName . "_badge" ] = ( $count > 0 ? '<span class="badge">' . $count . '</span>' : '' );
            }
            $this->setData( $data );
        }

        /**
         * @return array
         */
        public function getData() {
            return $this->data;
        }

        /**
         * @param       $index
         * @param mixed $data
         *
         * @return MainModel
         */
        public function addData( $index, $data ) {
            if ( !empty( $index ) ) {
                $this->data[ $index ] = $data;
            } else {
                $this->data[] = $data;
            }

            return $this;
        }

        /**
         * @param mixed $data
         */
        public function setData( $data ) {
            $this->data = $data;
        }

        /**
         * @param \cogwheel\folder $folder
         * @param                  $page
         */
        public function show( folder $folder, $page ) {
            $totalLetters = $this->gmail->count( $folder );

            $lettersPerPage = 25; // TODO Settings

            $totalPages = intval( ceil( $totalLetters / $lettersPerPage ) );

            if ( $totalPages > 1 && $page > $totalPages ) {
                header( "location: /u/" . $folder->getName() . "/" . strval( $page - 1 ) );
            }

            $letters = $this->gmail->getAll( $folder, $page, $lettersPerPage );
            if ( count( $letters ) > 0 ) {
                $center = '<div class="col s9">';
                $center .= '<div class="row">';
                $center .= '<div class="col s12">';
                $center .= '<ul class="collapsible popout" data-collapsible="accordion">';
                foreach ( $letters as $letter ) {
                    $center .= '<li>';
                    $center .= '<div class="collapsible-header letter" data-folderId="' . $letter[ "folder" ] . '" id="' . $letter[ "letter_id" ] . '">';
                    if ( $letter[ "seen" ] == 0 ) {
                        $center .= '<b>';
                    }

                    $center .= '<div class="left-align col s8 ' . ( $letter[ "dmarc" ] == 1 ? 'green-text text-darken-1 ' : 'red-text text-lighten-2' ) . '">' . $letter[ "subject" ] . '</div>';
                    $date = date_create( $letter[ "timestamp" ] );

                    $center .= '<div class="right-align col s4">' . $date->format( "Y-m-d H:i" ) . '</div>';
                    if ( $letter[ "seen" ] == 0 ) {
                        $center .= '</b>';
                    }
                    $center .= '</div>';
                    $center .= '<div class="collapsible-body">';
                    $center .= '<p><small>';
                    $center .= 'From: <b><a href="mailto:' . $letter[ "from_address" ] . '">' . $letter[ "from_address" ] . '</a></b><br/>';
                    $center .= 'To: <b>' . $letter[ "to_address" ] . '</b><br/>';
                    $center .= 'Date & Time: <b>' . $date->format( 'Y-m-d H:i:s' ) . '</b><br/>';
                    $center .= '</small></p>';
                    $center .= '<p style="font-size: 22px;"><b>' . $letter[ "subject" ] . '</b></p>';
                    $center .= '<p>' . $letter[ "body" ] . '</p>';
                    if ( $folder->getId() == 1 ) {
                        $center .= '<p class="right-align" style="padding-right: 30px;">';
                        $center .= '<button id="' . $letter[ "letter_id" ] . '" data-folderId="' . $letter[ "folder" ] . '" class="waves-effect waves-light btn delete">Delete</button>';
                        $center .= '</p>';
                    }
                    $center .= '</div>';
                    $center .= '</li>';
                }
                $center .= '</ul>';
                $center .= '</div>';
                $center .= '</div>';

                if ( $totalLetters > $lettersPerPage ) {
                    $previousPage = $page - 1;
                    $nextPage = $page + 1;
                    $center .= '<div class="row">';
                    $center .= '<div class="col s12 center-align">';
                    $center .= '<ul class="pagination">';

                    $path = '/u/' . $folder->getName() . '/'; //TODO: Global path for include

                    $center .= '<li class="' . ( $page == 1 ? 'disabled' : 'waves-effect' ) . '"><a href="' . ( $page == 1 ? '#' : $path . $previousPage ) . '"><i class="material-icons">chevron_left</i></a></li>';

                    for ( $i = 1; $i <= $totalPages; $i++ ) {
                        $visibleLeft = $page - 3;
                        $visibleRight = $page + 3;

                        if ( $i == $visibleLeft ) {
                            $center .= '<li class="disabled">&hellip;</li>';
                        } elseif ( $i == $visibleRight ) {
                            $center .= '<li class="disabled">&hellip;</li>';
                        } else {
                            if ( $i > $visibleLeft && $i < $visibleRight ) {
                                $center .= '<li class="' . ( $i == $page ? 'active' : 'waves-effect' ) . '"><a href="' . $path . $i . '">' . $i . '</a></li>';
                            }
                        }
                    }

                    $center .= '<li class="' . ( $page == $totalPages ? 'disabled' : 'waves-effect' ) . '"><a href="' . ( $page == $totalPages ? '#' : $path . $nextPage ) . '"><i class="material-icons">chevron_right</i></a></li>';

                    $center .= '</ul>';
                    $center .= '</div>';
                    $center .= '</div>';
                }
                $center .= '</div>';
            } else {
                $center = '<div class="col s9 center-align"><h5 class="grey-text text-darken-1" style="margin-top: 50px">No messages in ' . strtoupper( $folder->getName() ) . '</h5></div>';
            }

            $this->addData( 'center', $center );
        }
    }