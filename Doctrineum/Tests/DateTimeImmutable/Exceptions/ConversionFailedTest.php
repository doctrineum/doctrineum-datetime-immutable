<?php
declare(strict_types = 1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\Scalar\Exceptions;

use Doctrineum\DateTimeImmutable\Exceptions\ConversionFailed;
use PHPUnit\Framework\TestCase;

class ConversionFailedTest extends TestCase
{
    /**
     * @test
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function I_can_use_it_as_doctrine_conversion_exception()
    {
        throw new ConversionFailed('Just checking this exception to be catchable as a specific type');
    }
}