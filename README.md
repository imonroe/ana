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
use imonroe\ana\Ana;

$formatted_date = Ana::standard_date_format(strtotime('yesterday'));

if (Ana::even_or_odd(2) == 'even'){
// do something
}

if (Ana::is_valid_link('https://www.google.com')){
// link is valid and curl-able
}

// grab a file from the web and save it to a text file.
Ana::create_file('sample.txt', Ana::quick_curl('https://www.sample.com/index.html'), true); 

```

## Available Methods:
All methods are static.

- standard_date_format($timestamp = '')
- sql_datetime($timestamp = '')
- google_datetime($timestamp = '')
- is_today($date_string)
- sooner_than($date_string)
- later_than($date_string)
- print_relative_date($date)
- fatal_handler()
- dd($var)
- array_unique_multi($arr)
- array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
- object_to_array($object)
- build_tree($flat, $pidKey, $idKey = null)
- csv_to_array($filename='', $delimiter=',')
- plural($quantity)
- word_limit($haystack, $ubound)
- convert_to_utf($input)
- plain_text($input)
- trim_string_to_length($str, $len)
- use_a_or_an($text)
- even_or_odd($number)
- random_number($lowbound = 1, $highbound = 100)
- random_hex($bytes = 8)
- generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
- create_nonce()
- current_page_url()
- get_url_segment($number)
- is_valid_link($link)
- quick_curl($link)
- get_ip()
- submit_post_request($url, $data)
- loading_spinner()
- code_safe_name($string)
- cast($destination, $sourceObject)
- ask_user($prompt)
- say($msg)
- error_out($msg)
- create_directory($directory_path, $perms = 0777)
- remove_directory($path)
- create_file($file_path_and_name, $file_content, $overwrite = false)
- append_file($file_path_and_name, $file_content, $overwrite = false)
- get_url_and_save($fully_qualified_url, $filename)
- read_file_to_string($filename)
- get_directory_list($directory_path)
- execute($cmd)
- get_arguments()
- replace_line_in_file(String $filename='', String $line_to_change='', String $change_to='')
- replace_and_save($oldFile, $search, $replace, $newFile = null)
- us_states()

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
