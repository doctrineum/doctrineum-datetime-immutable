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
     * @ORM\Column(type="datetime-immutable")
     */
    private $when;
}
