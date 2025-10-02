# Chaos Core

The core utilities and base classes for the Chaos Framework.  
Includes support for configuration, console commands, sockets, and framework bootstrapping.

---

## Installation

Require via Composer:

```
composer require chaoswd/core
```

---

## Features

- Configuration loader (`Config::loadFromPath()`)
- Environment variable support via `chaos/dotenv`
- Console command kernel (`chaos` CLI)
  - Currently has 3 commands. More in the works.
- Socket server foundation
- Base support classes for Chaos applications

---

## Usage

Example bootstrap (skeleton project):

```php
<?php

use Chaos\Dotenv\Dotenv;
use Chaos\Core\Support\Config;

new Dotenv();
Config::loadFromPath(__DIR__ . '/../config');

echo config('app.name'); // output the value listed with the key 'name' from config/app.php
```

Run the console kernel:

```bash
php chaos help
```

---

## Roadmap

- [ ] Command auto-discovery
- [ ] Extended socket server events
- [ ] Service container
- [ ] Middleware layer
- [ ] Additional Commands

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

---

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
