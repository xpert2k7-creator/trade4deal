<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\PhoneNumber;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public function test_india_numbers_normalize_to_e164_digits(): void
    {
        $this->assertSame('919876543210', PhoneNumber::normalize('9876543210', 'India'));
        $this->assertSame('919876543210', PhoneNumber::normalize('+91 (98765) 43210', 'India'));
        $this->assertSame('919876543210', PhoneNumber::normalize('+91 9876543210'));
        $this->assertSame('919876543210', PhoneNumber::normalize('09876543210', 'India'));
    }

    public function test_us_numbers_normalize_to_e164_digits(): void
    {
        $this->assertSame('12025550104', PhoneNumber::normalize('2025550104', 'United States'));
        $this->assertSame('12025550104', PhoneNumber::normalize('+1 202-555-0104'));
    }

    public function test_same_indian_number_in_different_formats_matches(): void
    {
        $a = PhoneNumber::normalize('9876543210', 'India');
        $b = PhoneNumber::normalize('+91 9876543210', 'India');

        $this->assertNotNull($a);
        $this->assertSame($a, $b);
    }

    public function test_normalize_returns_null_for_blank_or_invalid(): void
    {
        $this->assertNull(PhoneNumber::normalize(null));
        $this->assertNull(PhoneNumber::normalize(''));
        $this->assertNull(PhoneNumber::normalize('   '));
        $this->assertNull(PhoneNumber::normalize('123', 'India'));
        $this->assertNull(PhoneNumber::normalize('9876543210', 'Unknown Country XYZ'));
    }
}
