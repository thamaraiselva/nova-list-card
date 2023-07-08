# Nova List Card

![Packagist License](https://img.shields.io/packagist/l/think.studio/nova-list-card?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/nova-list-card)](https://packagist.org/packages/think.studio/nova-list-card)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/nova-list-card)](https://packagist.org/packages/think.studio/nova-list-card)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-list-card/?branch=main)

![nova-list-card](./docs/assets/images/nova-list-card.png)

| Nova  | Package |
|-------|---------|
| V1-V3 | V1-V3   |
| V4    | V4      |

### Install

```bash
composer require think.studio/nova-list-card
```

### Usage

![nova-list-card](./docs/assets/images/list-card-count.png)

```php
class FundsWithReportsCount extends ListCard
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->resource(\App\Nova\Resources\Fund::class)
            ->heading($this->name(), 'Reports')
            ->withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->limit(100)
            ->value('reports_count');
    }

    public function cacheFor(): int|Carbon
    {
        return Carbon::now()->addMinutes(2);
    }
}
```

![nova-list-card](./docs/assets/images/list-card-sum.png)

```php
class FundsWithReportIncomeSum extends ListCard
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->resource(\App\Nova\Resources\Fund::class)
            ->heading($this->name(), 'Total Income')
            ->withSum('reports', 'income')
            ->orderBy('reports_sum_income', 'desc')
            ->limit(100)
            ->value('reports_sum_income');
    }
}
```

![nova-list-card](./docs/assets/images/list-card-query.png)

```php
class FundsCustomList extends ListCard
{

    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->resource(\App\Nova\Resources\Fund::class)
            ->heading($this->name())
            ->limit(100)
            ->timestamp('updated_at', 'm/Y')
            ->queryCallback(fn (Builder $q) => $q->where('publication_status', 'draft'));
    }

    public function name(): string
    {
        return 'Draft funds';
    }
}
```

![nova-list-card](./docs/assets/images/list-card-format.png)

```php
class FundsWithValueFormat extends ListCard
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->resource(\App\Nova\Resources\Fund::class)
            ->heading($this->name(), 'Created at')
            ->limit(100)
            ->timestamp('updated_at', 'm/Y')
            ->value('created_at', 'datetime', 'm/Y')
            ->classes('bg-yellow-300')
            ->noMaxHeight();
    }
}
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
