<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['10', '50'],
            ['20', '68'],
            ['25', '77'],
            ['-10', '14'],
            ['36.5', '97.7'],
            ['-40', '-40'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(
        string $celsius,
        string $expectedFahrenheit
    ): void {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);

        $this->assertEquals(
            $expectedFahrenheit,
            $measurement->getFahrenheit()
        );
    }
}
