<?php

require_once( dirname( __FILE__ ) . '/../classes/expression.class.php' );

try {
    if ( isset( $_POST, $_POST['expression'] ) ) {
        $expression = $_POST['expression'];
        $result = Expression::evaluate( $expression );
        echo json_encode( array( 'success' => true, 'message' => $result, 'expression' => $expression ) );
    }
} catch ( Exception $e ) {
    echo json_encode( array( 'success' => false, 'message' => $e->getMessage(), 'expression' => $expression ) );
}