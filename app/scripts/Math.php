<?php

namespace App\Scripts;

/**
 * Basic Math Operations Class
 *
 * Provides simple mathematical operations for addition, subtraction,
 * multiplication, and division with error handling.
 */
class Math
{
    /**
     * Add two numbers
     *
     * @param float $a First number
     * @param float $b Second number
     * @return float Sum of the two numbers
     */
    public static function add(float $a, float $b): float
    {
        return $a + $b;
    }

    /**
     * Subtract second number from first number
     *
     * @param float $a First number (minuend)
     * @param float $b Second number (subtrahend)
     * @return float Difference of the two numbers
     */
    public static function subtract(float $a, float $b): float
    {
        return $a - $b;
    }

    /**
     * Multiply two numbers
     *
     * @param float $a First number
     * @param float $b Second number
     * @return float Product of the two numbers
     */
    public static function multiply(float $a, float $b): float
    {
        return $a * $b;
    }

    /**
     * Divide first number by second number
     *
     * @param float $a Dividend
     * @param float $b Divisor
     * @return float Quotient of the division
     * @throws \InvalidArgumentException When divisor is zero
     */
    public static function divide(float $a, float $b): float
    {
        if ($b == 0) {
            throw new \InvalidArgumentException('Division by zero is not allowed');
        }

        return $a / $b;
    }

    /**
     * Calculate the power of a number
     *
     * @param float $base Base number
     * @param float $exponent Exponent
     * @return float Result of base raised to the power of exponent
     */
    public static function power(float $base, float $exponent): float
    {
        return pow($base, $exponent);
    }

    /**
     * Calculate the square root of a number
     *
     * @param float $number Number to calculate square root of
     * @return float Square root of the number
     * @throws \InvalidArgumentException When number is negative
     */
    public static function squareRoot(float $number): float
    {
        if ($number < 0) {
            throw new \InvalidArgumentException('Cannot calculate square root of negative number');
        }

        return sqrt($number);
    }

    /**
     * Calculate the absolute value of a number
     *
     * @param float $number Number to get absolute value of
     * @return float Absolute value of the number
     */
    public static function absolute(float $number): float
    {
        return abs($number);
    }

    /**
     * Round a number to specified decimal places
     *
     * @param float $number Number to round
     * @param int $precision Number of decimal places (default: 2)
     * @return float Rounded number
     */
    public static function round(float $number, int $precision = 2): float
    {
        return round($number, $precision);
    }
}
