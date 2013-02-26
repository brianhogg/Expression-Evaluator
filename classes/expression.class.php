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
                if ( FALSE !== strpos( $expression, $operator, 1 ) )
                    return self::evaluate_operator_expression( $expression, $operator );
            }
            throw new Exception('Invalid expression');
        }
    }

    public static function get_position_of_next_operator( $operator_position, $expression ) {
        $retval = false;
        foreach ( self::$_operators as $operator ) {
            $position = strpos( $expression, $operator, $operator_position + 1 );
            if ( FALSE !== $position and $position != strlen( $expression ) and $position != ( $operator_position + 1 ) and ( false === $retval or $position < $retval ) ) {
                $retval = $position;
            }
        }
        return $retval;
    }

    public static function get_position_of_previous_operator( $operator_position, $expression ) {
        $retval = false;
        foreach ( self::$_operators as $operator ) {
            $position = strrpos( substr( $expression, 0, $operator_position ), $operator );
            if ( FALSE !== $position and 0 != $position and $position != ( $operator_position - 1 ) and ( false === $retval or $position > $retval ) ) {
                $retval = $position;
            }
        }
        return $retval;
    }

    public static function evaluate_operator_expression( $expression, $operator ) {
        $operator_position = strpos( $expression, $operator, 1 );
        $preceding_operator_position = self::get_position_of_previous_operator( $operator_position, $expression );
        $next_operator_position = self::get_position_of_next_operator( $operator_position, $expression );
        if ( false === $preceding_operator_position )
            $left_side_of_operator = substr( $expression, 0, $operator_position );
        else
            $left_side_of_operator = substr( $expression, $preceding_operator_position + 1, $operator_position - $preceding_operator_position - 1 );
        if ( false === $next_operator_position )
            $right_side_of_operator = substr( $expression, $operator_position + 1 );
        else
            $right_side_of_operator = substr( $expression, $operator_position + 1, $next_operator_position - $operator_position - 1 );
        switch ( $operator ) {
            case '^':
                $result = pow( self::evaluate( $left_side_of_operator ), self::evaluate( $right_side_of_operator ) );
                break;
            case '/':
                if ( 0 == self::evaluate( $right_side_of_operator ) )
                    throw new Exception('Cannot divide by zero');
                else
                    $result = self::evaluate( $left_side_of_operator ) / self::evaluate( $right_side_of_operator );
                break;
            case '*':
                $result = self::evaluate( $left_side_of_operator ) * self::evaluate( $right_side_of_operator );
                break;
            case '+':
                $result = self::evaluate( $left_side_of_operator ) + self::evaluate( $right_side_of_operator );
                break;
            case '-':
                $result = self::evaluate( $left_side_of_operator ) - self::evaluate( $right_side_of_operator );
                break;
            default:
                throw new Exception('Invalid operator');
        }
        $expression = substr_replace( $expression, $result, $operator_position - strlen( $left_side_of_operator ), strlen( $left_side_of_operator . $operator . $right_side_of_operator ) );
        return self::evaluate( $expression );
    }
}