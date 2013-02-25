<?php

class Expression {
    function evaluate($expression) {
        $expression = trim($expression);
        if ( is_numeric($expression) )
            return $expression;
        elseif ( FALSE !== strpos( $expression, '(' ) ) {
            $matches = false;
            // Pattern from tutorial on recursive pattern matching at:
            // http://php.net/manual/en/regexp.reference.recursive.php
            preg_match( '~\( ( (?>[^()]+) | (?R) )* \)~x', $expression, $matches );
            $bracketed_expression_string = $matches[0];
            $expression = str_replace( $bracketed_expression_string, self::evaluate( substr( $bracketed_expression_string, 1, strlen( $bracketed_expression_string ) - 2 ) ), $expression );
            return self::evaluate( $expression );
        } elseif ( FALSE !== strpos( $expression, '^' ) ) {
            return pow( substr( $expression, 0, strpos( $expression, '^' ) ), self::evaluate( substr( $expression, strpos( $expression, '^' ) + 1 ) ) );
        } elseif ( FALSE !== strpos( $expression, '*' ) )
            return substr( $expression, 0, strpos( $expression, '*' ) ) * self::evaluate( substr( $expression, strpos( $expression, '*' ) + 1 ) );
        elseif ( FALSE !== strpos( $expression, '/' ) ) {
            $dividend = substr( $expression, 0, strpos( $expression, '/' ) );
            $divisor = self::evaluate( substr( $expression, strpos( $expression, '/' ) + 1 ) );
            if ( 0 == $divisor )
                throw new Exception('Cannot divide by zero');
            else
                return $dividend / $divisor;
        } elseif ( FALSE !== strpos( $expression, '+' ) )
            return substr( $expression, 0, strpos( $expression, '+' ) ) + self::evaluate( substr( $expression, strpos( $expression, '+' ) + 1 ) );
        elseif ( FALSE !== strpos( $expression, '-' ) )
            return substr( $expression, 0, strpos( $expression, '-' ) ) - self::evaluate( substr( $expression, strpos( $expression, '-' ) + 1 ) );
        else
            throw new Exception('Invalid expression');
    }
}
