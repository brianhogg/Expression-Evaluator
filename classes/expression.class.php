<?php

class Expression {
    private static $_operators = array( '^', '*', '/', '+', '-' );

    public static function evaluate( $expression ) {
        $expression = trim($expression);
        if ( 'INF' == $expression or ( is_numeric( $expression ) and is_infinite( $expression ) ) )
            throw new Exception('Invalid expression - result too large');
        elseif ( is_numeric($expression) ) {
            if ( is_nan($expression) )
                throw new Exception('Invalid expression - not a number');
            else
                return $expression;
        } elseif ( FALSE !== strpos( $expression, '(' ) ) {
            $matches = false;
            // Pattern from tutorial on recursive pattern matching at:
            // http://php.net/manual/en/regexp.reference.recursive.php
            $result = preg_match( '~\( ( (?>[^()]+) | (?R) )* \)~x', $expression, $matches );
            if ( false == $result ) {
                throw new Exception('Invalid expression, improper brackets');
            } else {
                $bracketed_expression_string = $matches[0];
                $expression = str_replace( $bracketed_expression_string, self::evaluate( substr( $bracketed_expression_string, 1, strlen( $bracketed_expression_string ) - 2 ) ), $expression );
                return self::evaluate( $expression );
            }
        } else {
            foreach ( self::$_operators as $operator ) {
                if ( FALSE !== strpos( $expression, $operator ) )
                    return self::evaluate_operator_expression( $expression, $operator );
            }
            throw new Exception('Invalid expression');
        }
    }

    public static function evaluate_operator_expression( $expression, $operator ) {
        $left_side = substr( $expression, 0, strpos( $expression, $operator ) );
        $right_side = self::evaluate( substr( $expression, strpos( $expression, $operator ) + 1 ) );
        switch ( $operator ) {
            case '^':
                $retval = pow( $left_side, $right_side );
                break;
            case '/':
                if ( 0 == $right_side )
                    throw new Exception('Cannot divide by zero');
                else
                    $retval = $left_side / $right_side;
                break;
            case '*':
                $retval = $left_side * $right_side;
                break;
            case '+':
                $retval = $left_side + $right_side;
                break;
            case '-':
                $retval = $left_side - $right_side;
                break;
            default:
                throw new Exception('Invalid operator');
        }
        return self::evaluate( $retval );
    }
}