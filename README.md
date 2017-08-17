# ana

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require imonroe/ana
```

## Usage

``` php
$formatted_date = Ana::standard_date_format(strtotime('yesterday'));

if (Ana::even_or_odd(2) == 'even'){
// do something
}

if (Ana::is_valid_link('https://www.google.com')){
// link is valid and curl-able
}

// grab a file from the web and save it to a text file.
Ana::create_file('sample.txt', Ana:: quick_curl('https://www.sample.com/index.html'), true); 

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ian@ianmonroe.com instead of using the issue tracker.

## Credits

- [Ian Monroe][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/imonroe/ana.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/imonroe/ana.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/imonroe/ana
[link-downloads]: https://packagist.org/packages/imonroe/ana
[link-author]: https://github.com/imonroe
[link-contributors]: ../../contributors
