<?php

class ExpressionHelper {
    public static function send_success_message( $data ) {
        if ( ! is_array($data) )
            throw new Exception('Invalid data array');
        $retval = array_merge( array( 'success' => true ), $data );
        echo json_encode( $retval );
    }

    public static function send_error_message( $data ) {
        if ( ! is_array($data) )
            throw new Exception('Invalid data array');
        $retval = array_merge( array( 'success' => false ), $data );
        echo json_encode( $retval );
    }
}
