# Changelog
All notable changes to this project will be documented in this file.  
This project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]
- Planned: service container support
- Planned: middleware layer
- Planned: better console command discovery
- Planned: additional console commands

## [1.0.0] - 2025-10-02
### Added
- Initial release of `chaos/core`
- Config loader (`Config::loadFromPath()`)
- Console kernel and base command system
- Socket server foundation integrated
- Autoload support for `Chaos\Core` and `Chaos\Sockets`

### Fixed
- Improved autoloading for nested namespaces (`Chaos\Sockets`)

