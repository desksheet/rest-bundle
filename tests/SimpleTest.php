<?php

declare(strict_types=1);

namespace Desksheet\RestBundle\Tests;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testSum(): void
    {
        $this->assertSame(4, 2 + 2);
    }
}
