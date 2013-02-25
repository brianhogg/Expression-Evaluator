<?php

require_once( dirname( __FILE__ ) . '/../classes/expression.class.php' );
require_once( dirname( __FILE__ ) . '/../classes/expressionhelper.class.php' );

try {
    if ( isset( $_POST, $_POST['expression'] ) ) {
        $expression = $_POST['expression'];
        $result = Expression::evaluate( $expression );
        ExpressionHelper::send_success_message( array( 'message' => $result, 'expression' => $expression ) );
    } else {
        ExpressionHelper::send_error_message( array( 'message' => 'No expression found' ) );
    }
} catch ( Exception $e ) {
    ExpressionHelper::send_error_message( array( 'message' => $e->getMessage(), 'expression' => $expression ) );
}