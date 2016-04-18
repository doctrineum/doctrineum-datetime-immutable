## Do you need it?

 - first of all, think twice if you need a new type into your application
    * isn't [Doctrine \DateTime](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/cookbook/working-with-datetime.html) enough?
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