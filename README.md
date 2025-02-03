# PHP-FIG Standards Guide

This repository contains practical examples and implementations of PHP-FIG standards (PSRs).
Each standard is implemented in its own directory with examples and tests.

## Standards Covered

- [PSR-1: Basic Coding Standard](src/PSR1)
- [PSR-3: Logger Interface](src/PSR3)
- [PSR-4: Autoloading Standard](src/PSR4)
- [PSR-6: Caching Interface](src/PSR6)
- [PSR-7: HTTP Message Interfaces](src/PSR7)
- [PSR-11: Container Interface](src/PSR11)
- More coming soon...

## Requirements

- PHP 8.1 or higher
- Composer

## Installation

```bash
composer install
```

## Usage

Each PSR implementation is in its own namespace under `src/`. Check the individual directories for specific examples and documentation.

## Development

```bash
# Run tests
composer test

# Check coding standards
composer check-style

# Fix coding standards
composer fix-style
```

## License

MIT
