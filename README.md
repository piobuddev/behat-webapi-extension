# Behat WEBAPI extension

Web Api extension for Behat helps with performing http requests and interacting with a database.

## Usage
In your `composer.json` file:
```json
    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/piobuddev/behat-webapi-extension.git"
            }
        ],
        "require-dev": {
            "piobuddev/behat-webapi-extension": "^1.0.0"
        }
    }
```
- install or update composer
```sh
$ composer install "piobuddev/behat-webapi-extension"
```

enable and configure an extension in your `behat.yml` file
```yaml
default:
  suites:
    default:
      contexts:
        - WebApi\Context\Contexts\HttpClientContext: ~
        - WebApi\Context\Contexts\RepositoryContext: ~
  extensions:
      WebApi\ServiceContainer\WebApiExtension:
          http_client:
              base_uri: 'https://foo-bar.local'
```

### Example:
In your `feature` file

```yaml
Feature: This is some feature
  In order to retrieve entity data
  as a client
  I want to be able to get the data with the HTTP request
  
  Background:
    Given the following entity(s) exist:
      | id | name     | last_name |
      | 1  | Anthony  | Doe       |
      | 2  | Audrey   | Smith     |
      | 3  | Leonardo | Donalds   |
      
  Scenario: Fetch an entity data
    When I send a GET request to "api/entity/1"
    Then the response code should be 200
    And the JSON response should contain:
      | id | name     | lastName |
      | 1  | Anthony  | Doe      |
```

## Development setup:
### Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites
* PHP >= `7.1`
* [Composer](https://getcomposer.org/): tool for dependency management in PHP

### Installing

To get the development environment running clone the repository and run the composer

```sh
$ git clone git@github.com:piobuddev/behat-webapi-extension.git
$ cd behat-webapi-extension/
$ composer install
```

## Running the tests

### Coding style tests
##### PHPStan : PHP Static Analysis Tool

```sh
$ vendor/bin/phpstan analyse -l 7 src tests -c phpstan.neon
```
##### PHPCS : Detects violations of a defined set of coding standards

```sh
$ vendor/bin/phpcs --standard=PSR2 --extensions=php --colors --severity=1 src
```

or run all tests together with:
```sh
$ composer test
```

Additionaly you can fix code formatting with:
##### PHPCBF : PHP Code Beautifier and Fixer

```sh
$ vendor/bin/phpcbf --standard=PSR2 --extensions=php --colors --severity=1 src
```

## Built With
* [Behat](http://behat.org/) BDD framework for PHP
* [Guzzle](http://docs.guzzlephp.org/) - PHP HTTP client that makes it easy to send HTTP requests
* [Repository Tester](https://github.com/piobuddev/repository-tester) - Helper that provides a simple api to create a fake data and perform a database operations.

## Code Style
* [PSR2](https://www.php-fig.org/psr/psr-2/)


## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/piobuddev/c04b7341f68da9718907cb593012d746) for details on my code of conduct, and the process for submitting pull requests to me.

## Versioning

I use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/piobuddev/behat-webapi-extension/tags). 

## Authors

* **Piotr Budny** - [piobuddev](https://github.com/piobuddev)

## License

This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/piobuddev/repository-tester/blob/master/LICENSE.md) file for details
