<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR12;

/**
 * ExampleClass demonstrates a properly formatted class according to PSR-12.
 */
class ExampleClass
{
    // Property to hold a name
    private string $name;

    // Property to hold an age
    private int $age;

    /**
     * Constructor to initialize the ExampleClass.
     *
     * @param string $name
     * @param int $age
     */
    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    /**
     * Get the name of the person.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the age of the person.
     *
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * Set the name of the person.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Set the age of the person.
     *
     * @param int $age
     * @return void
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * Provide a brief introduction of the person.
     *
     * @return string
     */
    public function introduce(): string
    {
        return "Hello, my name is {$this->name} and I am {$this->age} years old.";
    }
} 