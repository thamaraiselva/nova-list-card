# Nova List Card

[![Packagist License](https://img.shields.io/packagist/l/yaroslawww/nova-list-card?color=%234dc71f)](https://github.com/yaroslawww/nova-list-card/blob/master/LICENSE.md)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/nova-list-card)](https://packagist.org/packages/yaroslawww/nova-list-card)
[![Total Downloads](https://img.shields.io/packagist/dt/yaroslawww/nova-list-card)](https://packagist.org/packages/yaroslawww/nova-list-card)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/nova-list-card/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/nova-list-card/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/nova-list-card/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/nova-list-card/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/nova-list-card/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/nova-list-card/?branch=master)

| Nova  | Package |
|-------|---------|
| V1-V3 | V1-V3   |
| V4    | V4      |

### Install

```bash
composer require yaroslawww/nova-list-card
```

### Usage

```php
<?php
namespace App\Nova\Metrics;

use NovaListCard\ListCard;

class ContactsPerJobGroup extends ListCard {

    public function __construct( $component = null ) {
        parent::__construct( $component );

        $this->resource( \App\Nova\Resources\JobGroup::class )
             ->heading( $this->name(), 'Contacts' )
             ->withCount( 'contacts' )
             ->orderBy( 'contacts_count', 'desc' )
             ->limit( 100 )
             ->zebra()
             ->value( 'contacts_count' );
    }

    public function cacheFor() {
        return now()->addMinutes( 10 );
    }

    public function uriKey() {
        return 'contacts-per-job-groups';
    }

    public function name() {
        return 'Job Groups';
    }
}
```

![nova-list-card](./assets/images/nova-list-card.png)
