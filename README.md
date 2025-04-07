# Testing Accessible Trait

A helper class for testing,
especially for unit tests to access inaccessible methods
or to get/inject properties.

## Installation

Install via composer:

```
dcrr composer req --dev r83dev/test-accessible
```

## Usage

Use the `AccessibleTrait` in your test:

```php
class MyTest {
    use R83Dev\TestAccessible\AccessibleTrait;
}
```

Use one of the helper function
to access private or protected stuff of an instantiated object.

### Methods

#### Call inaccessible method

```php
$this->callInaccessibleMethod($object, 'method_name', 'argument1', 'argument2', ...);
```

#### Get inaccessible property

```php
$this->getInaccessibleProperty($object, 'property_name');
```

#### Set inaccessible property

```php
$this->setInaccessibleProperty($object, 'property_name', 'new_property_value');
```

#### Get inaccessible constant

```php
$this->getInaccessibleConstant($object, 'CONSTANT_NAME');
```

### PHPUnitTest Example

This is just a features demo, not useful in terms of content.

Example of a unit to be tested:

```php

class MyUnit
{
    protected const STATE_ON = 'on';
    protected const STATE_OFF = 'off';
    private string $state = self::STATE_ON;
    
    protected function setState(string $state): void
    {
        $this->state = $state;
    }
    
    public function getState(): string
    {
        return $this->state;
    }
}
```

Example unittest:

```php
class MyUnitTest extends PHPUnit\Framework\TestCase
{
    use R83Dev\TestAccessible\AccessibleTrait;
    
    private ?MyUnit $unit;

    protected function setUp(): void
    {
        $this->unit = new MyUnit();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function getStateShouldReturnCorrectInitialValue(): void
    {
        $this->assertSame(
            $this->getInaccessibleConstant($this->unit, 'STATE_ON'),
            $this->getInaccessibleProperty($this->unit, 'state')
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function getStateReturnsNewValueFromSetter(): void
    {
        $off = $this->getInaccessibleConstant($this->unit, 'STATE_OFF');
        $this->callInaccessibleMethod($this->unit, 'setState', $off)
        $this->assertSame($off, $this->unit->getState());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function getStateReturnsNewValueFromPropertyAllocation(): void
    {
        $this->assertSame(
            'unknown', 
            $this->setInaccessibleProperty($this->unit, 'state', 'unknown')->getState()
        );
    }
}
```
