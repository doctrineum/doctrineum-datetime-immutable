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