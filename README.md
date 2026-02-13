# PHP-FIG Standards Guide -- Blog API

A practical guide to PHP-FIG standards (PSRs) through a blog API example. Each PSR is implemented in context, showing how standards work together in a real project.

**Companion to the blog series:** [PSR Standards in PHP](https://jonesrussell.github.io/blog/psr-standards-in-php-practical-guide-for-developers/)

## PSR Coverage

| PSR | Standard | Where It's Used |
|-----|----------|----------------|
| 1 | Basic Coding Standard | Project-wide coding conventions |
| 3 | Logger Interface | `src/PSR3/` -- Application logging |
| 4 | Autoloading | Composer PSR-4 autoloading |
| 6 | Caching Interface | `src/PSR6/` -- Cache pools and items |
| 7 | HTTP Messages | `src/Http/Message/` -- Request/response objects |
| 11 | Container Interface | `src/PSR11/` -- Dependency injection |
| 12 | Extended Coding Style | Enforced via PHP_CodeSniffer |
| 13 | Hypermedia Links | `src/PSR13/` -- Link relations |
| 14 | Event Dispatcher | `src/Event/` -- Post events and listeners |
| 15 | HTTP Middleware | `src/Http/Middleware/` -- Auth, logging pipeline |
| 16 | Simple Cache | `src/Cache/SimpleCache/` -- File-based key-value cache |
| 17 | HTTP Factories | `src/Http/Factory/` -- Response and stream factories |
| 18 | HTTP Client | `src/Http/Client/` -- Sending HTTP requests |
| 20 | Clock | `src/Clock/` -- Testable time handling |

## Requirements

- PHP 8.2 or higher
- Composer

## Quick Start

```bash
git clone https://github.com/jonesrussell/php-fig-guide.git
cd php-fig-guide
composer install

# Run the demo
php public/index.php

# Run all tests
composer test

# Run tests for a specific PSR
composer test -- --filter=PSR7
composer test -- --filter=Clock
composer test -- --filter=Middleware

# Check coding standards
composer check-style
```

## Project Structure

```
src/
├── Blog/               # Domain model (Post)
├── Cache/
│   └── SimpleCache/    # PSR-16: File-based cache
├── Clock/              # PSR-20: System and frozen clocks
├── Event/              # PSR-14: Event dispatcher and listeners
├── Http/
│   ├── Client/         # PSR-18: HTTP client
│   ├── Factory/        # PSR-17: Response and stream factories
│   ├── Message/        # PSR-7: HTTP message implementations
│   └── Middleware/     # PSR-15: Auth, logging, pipeline
├── PSR1/               # PSR-1: Coding standard examples
├── PSR3/               # PSR-3: Logger implementation
├── PSR4/               # PSR-4: Autoloading examples
├── PSR6/               # PSR-6: Cache pool implementation
├── PSR7/               # PSR-7: Original message examples
├── PSR11/              # PSR-11: Container implementation
├── PSR12/              # PSR-12: Coding style examples
└── PSR13/              # PSR-13: Hypermedia link examples
tests/                  # PHPUnit tests mirroring src/ structure
```

## License

MIT
