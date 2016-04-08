<?php
namespace Doctrineum\DateTimeImmutable\Exceptions;

use Doctrine\DBAL\Types\ConversionException;

class ConversionFailed extends ConversionException implements Exception
{

}