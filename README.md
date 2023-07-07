# Nova List Card

![Packagist License](https://img.shields.io/packagist/l/think.studio/nova-list-card?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/nova-list-card)](https://packagist.org/packages/think.studio/nova-list-card)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/nova-list-card)](https://packagist.org/packages/think.studio/nova-list-card)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/?branch=main)

| Nova  | Package |
|-------|---------|
| V1-V3 | V1-V3   |
| V4    | V4      |

### Install

```bash
composer require think.studio/nova-list-card
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
             ->value( 'contacts_count' )
             ->limit( 100 )
             // Display timestamps
             ->timestamp('updated_at');
    }

    public function cacheFor(): int|Carbon
    {
        return now()->addMinutes( 10 );
    }
}
```

![nova-list-card](./docs/assets/images/nova-list-card.png)


## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
