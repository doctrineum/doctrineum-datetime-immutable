[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/doctrineum-datetime-immutable/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/doctrineum-datetime-immutable/coverage)
[![License](https://poser.pugx.org/doctrineum/datetime-immutable/license)](https://packagist.org/packages/doctrineum/datetime-immutable)

# Deprecated
**Use [Doctrine\DBAL\Types\DateTimeImmuable](https://github.com/doctrine/dbal/blob/master/lib/Doctrine/DBAL/Types/DateTimeImmutableType.php) instead.**

## Do you need it?

 - first of all, think twice if you need a new type into your application
    * isn't [Doctrine\DBAL\Types\DateTime](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/cookbook/working-with-datetime.html) enough?
 - on the other side, immutable object of any time can save you a lot of problems and time...

# Usage

 - register it
```php
\Doctrineum\DateTimeImmutable\DateTimeImmutableType::registerSelf();
```

- use it
```php
use Doctrine\ORM\Mapping as ORM;

class Foo {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="datetime-immutable")
     */
    private $when;
    
    public function __construct(\DateTimeImmutable $when)
    {
        $this->when = $when;
    }
}
```

```php
\Doctrineum\DateTimeImmutable\DateTimeImmutableType::registerSelf();

$foo = new Foo(new \DateTimeImmutable());
// ...
$entityManager->persist($foo);
$entityManager->flush();

```

## Common pitfalls

Be aware of timezone which is not persisted and therefore can not be restored on fetch.
Doctrine uses [server default timezone](http://php.net/manual/en/function.date-default-timezone-get.php) for it.
For details and most of all, for solution, see [Doctrine docs](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/cookbook/working-with-datetime.html#handling-different-timezones-with-the-datetime-type)
