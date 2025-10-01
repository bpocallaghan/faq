<?php

require_once __DIR__ . '/Math.php';

use App\Scripts\Math;

echo "=== Math Operations Example ===\n\n";

// Basic operations
echo "Basic Operations:\n";
echo "================\n";
echo "5 + 3 = " . Math::add(5, 3) . "\n";
echo "10 - 4 = " . Math::subtract(10, 4) . "\n";
echo "6 * 7 = " . Math::multiply(6, 7) . "\n";
echo "15 / 3 = " . Math::divide(15, 3) . "\n";
echo "20 / 4 = " . Math::divide(20, 4) . "\n\n";

// Decimal operations
echo "Decimal Operations:\n";
echo "==================\n";
echo "2.5 + 3.7 = " . Math::add(2.5, 3.7) . "\n";
echo "10.8 - 4.2 = " . Math::subtract(10.8, 4.2) . "\n";
echo "3.5 * 2.4 = " . Math::multiply(3.5, 2.4) . "\n";
echo "12.6 / 2.1 = " . Math::divide(12.6, 2.1) . "\n\n";

// Advanced operations
echo "Advanced Operations:\n";
echo "===================\n";
echo "2^3 = " . Math::power(2, 3) . "\n";
echo "4^0.5 = " . Math::power(4, 0.5) . "\n";
echo "√16 = " . Math::squareRoot(16) . "\n";
echo "√25 = " . Math::squareRoot(25) . "\n";
echo "|−5| = " . Math::absolute(-5) . "\n";
echo "|3.7| = " . Math::absolute(3.7) . "\n\n";

// Rounding examples
echo "Rounding Examples:\n";
echo "=================\n";
echo "3.14159 rounded to 2 decimals = " . Math::round(3.14159) . "\n";
echo "3.14159 rounded to 3 decimals = " . Math::round(3.14159, 3) . "\n";
echo "2.7 rounded to 2 decimals = " . Math::round(2.7) . "\n\n";

// Error handling examples
echo "Error Handling Examples:\n";
echo "=======================\n";

// Division by zero
echo "Testing division by zero:\n";
try {
    $result = Math::divide(10, 0);
    echo "10 / 0 = " . $result . "\n";
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Square root of negative number
echo "\nTesting square root of negative number:\n";
try {
    $result = Math::squareRoot(-4);
    echo "√(-4) = " . $result . "\n";
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Calculator function example
echo "Calculator Function Example:\n";
echo "===========================\n";

function calculate($operation, $a, $b) {
    try {
        return match($operation) {
            'add' => Math::add($a, $b),
            'subtract' => Math::subtract($a, $b),
            'multiply' => Math::multiply($a, $b),
            'divide' => Math::divide($a, $b),
            default => throw new InvalidArgumentException('Invalid operation')
        };
    } catch (InvalidArgumentException $e) {
        return "Error: " . $e->getMessage();
    }
}

$operations = ['add', 'subtract', 'multiply', 'divide'];
$testValues = [
    [10, 5],
    [7.5, 2.5],
    [100, 0], // This will cause division by zero error
];

foreach ($operations as $op) {
    foreach ($testValues as $values) {
        $result = calculate($op, $values[0], $values[1]);
        echo "calculate('{$op}', {$values[0]}, {$values[1]}) = {$result}\n";
    }
    echo "\n";
}

echo "=== Example Complete ===\n";
