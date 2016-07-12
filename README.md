# Aliyun MNS Queue Driver For Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require lokielse/laravel-mns
```

## Config

Add following service providers into your providers array in `config/app.php`

``` php
Lokielse\LaravelMNS\LaravelMNSServiceProvider::class
```

Edit your `config/queue.php`, add `mns` connection

```php
'mns'        => [
	'driver'       => 'mns',
	'key'          => env('MNS_ACCESS_KEY'),
	'secret'       => env('MNS_SECRET_KEY'),
	'endpoint'     => env('MNS_ENDPOINT'),
	'queue'        => env('QUEUE_NAME'),
	'wait_seconds' => 30,
]
```
About [wait_seconds](https://help.aliyun.com/document_detail/35136.html)

Edit your `.env` file

```bash
QUEUE_DRIVER=mns
QUEUE_NAME=foobar-local
MNS_ACCESS_KEY=your_acccess_key
MNS_SECRET_KEY=your_secret_key
MNS_ENDPOINT=http://12345678910.mns.cn-hangzhou.aliyuncs.com/
```

## Usage

First create queues at [Aliyun MNS Console](https://mns.console.aliyun.com/)

Then update `MNS_ENDPOINT` in `.env`

Push a test message to queue

```php
Queue::push(function($job){
	/**
	 * Your statments go here
	 */
	$job->delete();
});
```

Create queue listener, run command in terminal

```bash
$ php artisan queue:listen
```

## Commands
Flush MNS messages on Aliyun

```bash
$ php artisan queue:mns:flush
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email lokielse@gmail.com instead of using the issue tracker.

## Credits

- [lokielse][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lokielse/laravel-mns.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/lokielse/laravel-mns/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/lokielse/laravel-mns.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/lokielse/laravel-mns.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lokielse/laravel-mns.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/lokielse/laravel-mns
[link-travis]: https://travis-ci.org/lokielse/laravel-mns
[link-scrutinizer]: https://scrutinizer-ci.com/g/lokielse/laravel-mns/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/lokielse/laravel-mns
[link-downloads]: https://packagist.org/packages/lokielse/laravel-mns
[link-author]: https://github.com/lokielse
[link-contributors]: ../../contributors
