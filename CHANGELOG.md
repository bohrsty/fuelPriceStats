# CHANGELOG

## unreleased (master)

### Added
- add required configuration, initial files and folders
- add classes and methods to request tankerkönig api and store values in sqlite database
- add internal api to retrieve the price data from database as JSON
- add random delay in execution to fulfill the tankerkoenig.de terms of use
- add check if config is valid

### Changed
 - change access to last element of array to keep compatibility with PHP < 5.5
 - change DateTimeImmutable to DateTime to keep compatibility with PHP < 5.5


### Removed


### Fixed
- fix typo prize -> price
- fix null prices if station closed, takes the previous price
- fix new lines on cli output
