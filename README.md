# Nova List Card

Fork of [dillingham/nova-list-card](https://packagist.org/packages/dillingham/nova-list-card) but with a different
frontend and a partially modified backend, so it cannot be merged with the main repo. Subtitle and footer features
removed.

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
