# Container framework

Provides a framework for containers and compilation.

## Install

The recommended way to install this library is [through composer](http://getcomposer.org).

```sh
composer require xylemical/container
```

## Usage

```php

use Xylemical\Container\ContainerBuilder;

$source = ...; // A source defined by \Xylemical\Container\Source\SourceInterface.
$builder = new ContainerBuilder($source, 'config/container.php');
$container = $builder->getContainer();

```

## License

MIT, see LICENSE.
