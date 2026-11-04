<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\Validators;

class ValidatorsTest extends TestCase
{
    // --- Tests isValidEmail ---
    public function test_should_return_true_when_valid_standard_email()
    {
        $this->assertTrue(Validators::isValidEmail('user@example.com'));
    }

    public function test_should_return_true_when_complex_valid_email()
    {
        $this->assertTrue(Validators::isValidEmail('user.name+tag@domain.co'));
    }

    public function test_should_return_false_when_invalid_email_format()
    {
        $this->assertFalse(Validators::isValidEmail('invalid'));
    }

    public function test_should_return_false_when_email_missing_local_part()
    {
        $this->assertFalse(Validators::isValidEmail('@domain.com'));
    }

    public function test_should_return_false_when_email_missing_domain()
    {
        $this->assertFalse(Validators::isValidEmail('user@'));
    }

    public function test_should_return_false_when_empty_email()
    {
        $this->assertFalse(Validators::isValidEmail(''));
    }

    public function test_should_return_false_when_null_email()
    {
        $this->assertFalse(Validators::isValidEmail(null));
    }

    // --- Tests isValidPassword ---
    public function test_should_return_valid_when_strong_password()
    {
        $result = Validators::isValidPassword('Passw0rd!');
        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    public function test_should_return_errors_when_short_password()
    {
        $result = Validators::isValidPassword('short');
        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
    }

    public function test_should_fail_when_missing_uppercase()
    {
        $result = Validators::isValidPassword('alllowercase1!');
        $this->assertFalse($result['valid']);
    }

    public function test_should_fail_when_missing_lowercase()
    {
        $result = Validators::isValidPassword('ALLUPPERCASE1!');
        $this->assertFalse($result['valid']);
    }

    public function test_should_fail_when_missing_digit()
    {
        $result = Validators::isValidPassword('NoDigits!here');
        $this->assertFalse($result['valid']);
    }

    public function test_should_fail_when_missing_special_char()
    {
        $result = Validators::isValidPassword('NoSpecial1here');
        $this->assertFalse($result['valid']);
    }

    public function test_should_fail_when_empty_or_null_password()
    {
        $this->assertFalse(Validators::isValidPassword('')['valid']);
        $this->assertFalse(Validators::isValidPassword(null)['valid']);
    }

    // --- Tests isValidAge ---
    public function test_should_return_true_when_valid_age()
    {
        $this->assertTrue(Validators::isValidAge(25));
        $this->assertTrue(Validators::isValidAge(0));
        $this->assertTrue(Validators::isValidAge(150));
    }

    public function test_should_return_false_when_age_out_of_bounds()
    {
        $this->assertFalse(Validators::isValidAge(-1));
        $this->assertFalse(Validators::isValidAge(151));
    }

    public function test_should_return_false_when_decimal_age()
    {
        $this->assertFalse(Validators::isValidAge(25.5));
    }

    public function test_should_return_false_when_string_or_null_age()
    {
        $this->assertFalse(Validators::isValidAge('25'));
        $this->assertFalse(Validators::isValidAge(null));
    }
}