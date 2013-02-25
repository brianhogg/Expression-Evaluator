<?php

require_once( dirname(__FILE__) . '/../classes/expression.class.php');

class ExpressionTest extends PHPUnit_Framework_TestCase {
    public function testSingleDigit() {
        $this->assertEquals(1, Expression::evaluate('1'));
        $this->assertEquals(2, Expression::evaluate('2'));
    }

    public function testOnePlusOne() {
        $this->assertEquals(2, Expression::evaluate('1+1'));
    }

    public function testSpaceInExpression() {
        $this->assertEquals(2, Expression::evaluate('1 + 1'));
    }

    public function testSpaceBeforeandAfterExpression() {
        $this->assertEquals(2, Expression::evaluate(' 1 + 1 '));
    }

    public function testMultiply() {
        $this->assertEquals(4, Expression::evaluate('2*2'));
    }

    public function testDivide() {
        $this->assertEquals(1, Expression::evaluate('2/2'));
    }

    public function testSubtract() {
        $this->assertEquals(2, Expression::evaluate('3-1'));
    }

    public function testNegativeNumber() {
        $this->assertEquals(-1, Expression::evaluate('-1'));
    }

    public function testMultipleOperators() {
        $this->assertEquals(3, Expression::evaluate('1+1+1'));
    }

    /**
     * @expectedException Exception
     */
    public function testEmptyExpression() {
        Expression::evaluate('');
    }

    public function testPrecedence() {
        $this->assertEquals(8, Expression::evaluate('2*2+2*2'));
    }

    public function testBracketsAroundExpression() {
        $this->assertEquals(2, Expression::evaluate('(1+1)'));
    }

    public function testInfiniteCheck() {
        $large_result = pow(23423423, 23423423);
        $this->assertTrue(is_numeric($large_result));
        $this->assertTrue(is_infinite($large_result));
    }

    public function testPrecedenceWithBrackets() {
        $this->assertEquals(16, Expression::evaluate('2*(2+2)*2'));
    }

    public function testNestedBrackets() {
        $this->assertEquals(6, Expression::evaluate('2*(2+(2/2))'));
    }

    public function testOverflow() {
        try {
            Expression::evaluate('235423432^234098324');
        } catch ( Exception $e ) {
            $this->assertEquals('Invalid expression - result too large', $e->getMessage());
        }
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidBrackets() {
        Expression::evaluate('(1+');
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidEndBrackets() {
        Expression::evaluate('(1+1))');
    }

    public function testBracketsSeparatingOperator() {
        $this->assertEquals(18, Expression::evaluate('(3*3)+(3*3)'));
    }

    public function testPower() {
        $this->assertEquals(16, Expression::evaluate('2^4'));
    }

    public function testPowerWithBrackets() {
        $this->assertEquals(16, Expression::evaluate('(2+2)^2'));
        $this->assertEquals(64, Expression::evaluate('(2+2)^(2+1)'));
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidPower() {
        $result = Expression::evaluate('-1^5.5');
        var_dump($result);
    }

    /**
     * @expectedException Exception
     */
    public function testDivideByZero() {
        Expression::evaluate('1/0');
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidExpression() {
        Expression::evaluate('alksdjf');
    }
}