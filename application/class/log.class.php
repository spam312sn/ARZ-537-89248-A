<?php
    namespace cogwheel;

    class log {

        /**
         * @param string    $text
         * @param bool|null $status
         * @param int       $length
         */
        public function eco( $text = '', $status = null, $length = 80 ) {
            $log = "<tt>" . ">&emsp;";
            $log .= $text . "&emsp;";
            $textLength = strlen( $text );
            for ( $i = 0; $i < ( $length - $textLength ); $i++ ) {
                if ( !is_null( $status ) ) {
                    $log .= "=";
                }
            }
            if ( !is_null( $status ) ) {
                $log .= "&gt;&emsp;[&nbsp;" . strtoupper( $status == true ? "ok" : "error" ) . "&nbsp;]";
            }

            $log .= "</tt><br/>";

            print $log;
        }
    }