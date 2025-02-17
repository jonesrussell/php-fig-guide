<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR12;

/**
 * ExampleClass demonstrates a properly formatted class according to PSR-12.
 * This class represents a person with a name and age, providing methods
 * to get and set these properties, as well as a method to introduce the person.
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
     * @param string $name The name of the person.
     * @param int $age The age of the person.
     */
    public function __construct(string $name, int $age)
    {
        $this->name = $name; // Set the name property
        $this->age = $age;   // Set the age property
    }

    /**
     * Get the name of the person.
     *
     * @return string The name of the person.
     */
    public function getName(): string
    {
        return $this->name; // Return the name property
    }

    /**
     * Get the age of the person.
     *
     * @return int The age of the person.
     */
    public function getAge(): int
    {
        return $this->age; // Return the age property
    }

    /**
     * Set the name of the person.
     *
     * @param string $name The new name of the person.
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name; // Update the name property
    }

    /**
     * Set the age of the person.
     *
     * @param int $age The new age of the person.
     * @return void
     */
    public function setAge(int $age): void
    {
        $this->age = $age; // Update the age property
    }

    /**
     * Provide a brief introduction of the person.
     *
     * @return string A string introducing the person with their name and age.
     */
    public function introduce(): string
    {
        return "Hello, my name is {$this->name} and I am {$this->age} years old."; // Return the introduction string
    }
}
