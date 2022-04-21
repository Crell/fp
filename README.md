# Functional utilities

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

This library contains functional utilities intended for use with PHP 8.1 and later.  Its primary tool is the `pipe()` function, which takes a starting argument and then a series of callables to "pipe" that argument through.  Most other functions are utilities that produce a closure that takes the return from a previous `pipe()` step as its only argument.

That provides a reasonably good experience for building multi-step functional pipelines and composition, at least until PHP itself gets a proper pipe operator. :-)

## Install

Via Composer

``` bash
$ composer require crell/fp
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email larry at garfieldtech dot com instead of using the issue tracker.

## Credits

- [Larry Garfield][link-author]
- [All Contributors][link-contributors]

## License

The Lesser GPL version 3 or later. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/crell/fp.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/License-LGPLv3-green.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/crell/fp.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/crell/fp
[link-scrutinizer]: https://scrutinizer-ci.com/g/Crell/AttributeUtils/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Crell/AttributeUtils
[link-downloads]: https://packagist.org/packages/crell/fp
[link-author]: https://github.com/Crell
[link-contributors]: ../../contributors
