# Discovery framework

Provides a framework for class discovery.

## Install

The recommended way to install this library is [through composer](http://getcomposer.org).

```sh
composer require xylemical/discovery
```

## Usage

```php

use Xylemical\Discovery\SourceFactory;
use Xylemical\Discovery\Directory\NamespaceDiscovery;

$discovery = new NamespaceDiscovery(new SourceFactory(), 'Name\\Space\\Location', 'path/to/classes');
$sources = $discovery->discover();

```

## License

MIT, see LICENSE.
