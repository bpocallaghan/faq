# Math Operations Script

This directory contains a basic Math class with common mathematical operations for use in Laravel applications.

## Files

-   `Math.php` - Main Math class with static methods
-   `example.php` - Example script demonstrating usage
-   `usage.md` - This documentation file

## Math Class Methods

### Basic Operations

#### `add(float $a, float $b): float`

Adds two numbers together.

```php
$result = Math::add(5, 3); // Returns 8.0
$result = Math::add(10.5, 2.3); // Returns 12.8
```

#### `subtract(float $a, float $b): float`

Subtracts the second number from the first.

```php
$result = Math::subtract(10, 3); // Returns 7.0
$result = Math::subtract(5.5, 2.2); // Returns 3.3
```

#### `multiply(float $a, float $b): float`

Multiplies two numbers.

```php
$result = Math::multiply(4, 5); // Returns 20.0
$result = Math::multiply(2.5, 3.2); // Returns 8.0
```

#### `divide(float $a, float $b): float`

Divides the first number by the second.

```php
$result = Math::divide(15, 3); // Returns 5.0
$result = Math::divide(10, 2.5); // Returns 4.0

// Throws InvalidArgumentException for division by zero
try {
    $result = Math::divide(10, 0);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // "Division by zero is not allowed"
}
```

### Advanced Operations

#### `power(float $base, float $exponent): float`

Calculates base raised to the power of exponent.

```php
$result = Math::power(2, 3); // Returns 8.0 (2^3)
$result = Math::power(4, 0.5); // Returns 2.0 (4^0.5 = √4)
```

#### `squareRoot(float $number): float`

Calculates the square root of a number.

```php
$result = Math::squareRoot(16); // Returns 4.0
$result = Math::squareRoot(25); // Returns 5.0

// Throws InvalidArgumentException for negative numbers
try {
    $result = Math::squareRoot(-4);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // "Cannot calculate square root of negative number"
}
```

#### `absolute(float $number): float`

Returns the absolute value of a number.

```php
$result = Math::absolute(-5); // Returns 5.0
$result = Math::absolute(3.7); // Returns 3.7
```

#### `round(float $number, int $precision = 2): float`

Rounds a number to specified decimal places.

```php
$result = Math::round(3.14159); // Returns 3.14 (default 2 decimal places)
$result = Math::round(3.14159, 3); // Returns 3.142
$result = Math::round(2.7); // Returns 2.7
```

## Usage Examples

### Basic Calculator Function

```php
use App\Scripts\Math;

function calculate($operation, $a, $b) {
    switch ($operation) {
        case 'add':
            return Math::add($a, $b);
        case 'subtract':
            return Math::subtract($a, $b);
        case 'multiply':
            return Math::multiply($a, $b);
        case 'divide':
            return Math::divide($a, $b);
        default:
            throw new InvalidArgumentException('Invalid operation');
    }
}

// Usage
echo calculate('add', 10, 5); // 15
echo calculate('multiply', 3, 4); // 12
```

### Error Handling Example

```php
use App\Scripts\Math;

try {
    $result = Math::divide(10, 0);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage();
    // Handle the error appropriately
}

try {
    $result = Math::squareRoot(-4);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage();
    // Handle the error appropriately
}
```

### Running the Example Script

To see the Math class in action, run the example script:

```bash
php app/scripts/example.php
```

This will demonstrate all the available operations with sample calculations.

## Integration with Laravel

### Using in Controllers

```php
use App\Scripts\Math;

class CalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $a = $request->input('a');
        $b = $request->input('b');
        $operation = $request->input('operation');

        try {
            $result = match($operation) {
                'add' => Math::add($a, $b),
                'subtract' => Math::subtract($a, $b),
                'multiply' => Math::multiply($a, $b),
                'divide' => Math::divide($a, $b),
                default => throw new InvalidArgumentException('Invalid operation')
            };

            return response()->json(['result' => $result]);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
```

### Using in Blade Templates

```php
// In your controller
$calculation = Math::add(10, 5);

// In your Blade template
<p>The result is: {{ $calculation }}</p>
```

## Notes

-   All methods are static, so you can call them directly without instantiating the class
-   All methods return float values for consistency
-   Error handling is included for division by zero and negative square roots
-   The class follows PSR-4 autoloading standards
-   Methods include proper PHPDoc documentation for IDE support
