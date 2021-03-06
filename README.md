# Indigo Console

[![Latest Version](https://img.shields.io/github/release/indigophp/console.svg?style=flat-square)](https://github.com/indigophp/console/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/indigophp/console/develop.svg?style=flat-square)](https://travis-ci.org/indigophp/console)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/indigophp/console.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/console)
[![Quality Score](https://img.shields.io/scrutinizer/g/indigophp/console.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/console)
[![HHVM Status](https://img.shields.io/hhvm/indigophp/console.svg?style=flat-square)](http://hhvm.h4cc.de/package/indigophp/console)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/console.svg?style=flat-square)](https://packagist.org/packages/indigophp/console)

**Console built with separated CLI components.**

This package is a simple console implementation concentrating on the running logic. The following console related tasks are solved by some awesome libraries:

- Output formatting ([CLImate](http://climate.thephpleague.com))
- Events ([League\Event](http://event.thephpleague.com))
- Exception handling ([Whoops](http://filp.github.io/whoops/))
- Option parsing ([Getopt.php](http://ulrichsg.github.io/getopt-php/))


**Note:** The main reason for using 3rd party libraries is to avoid the not-invented-here syndrome and its consequences. Since they are 3rd party, I cannot support them. That said I am going to report any upcoming issues, also will try to fix them if possible.


## Install

Via Composer

``` bash
$ composer require indigophp/console
```

## Usage


## Testing

``` bash
$ phpspec run
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/console/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
