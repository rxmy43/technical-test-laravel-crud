<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    /**
     * Test that the User model has the correct fillable attributes.
     */
    public function test_fillable_contains_expected_fields(): void
    {
        $user = new User();

        $this->assertEquals(
            ['name', 'email', 'age'],
            $user->getFillable(),
            'User::$fillable does not match expected fields.'
        );
    }

    /**
     * Test that a User instance can be instantiated with attributes.
     */
    public function test_can_set_and_get_attributes(): void
    {
        $name = 'John Doe';
        $email = 'john.doe@gmail.com';
        $age = 25;

        $user = new User([
            'name' => $name,
            'email' => $email,
            'age' => $age,
        ]);

        $this->assertSame($name, $user->name);
        $this->assertSame($email, $user->email);
        $this->assertSame($age, $user->age);
    }

    /**
     * Test the age attribute is always cast to integer.
     */
    public function test_age_is_integer_cast(): void
    {
        $user = new User(['age' => '42']);

        $this->assertIsInt($user->age);
        $this->assertSame(42, $user->age);
    }

    /**
     * Test that the model’s email attribute passes a simple format check.
     */
    public function test_email_format_validation(): void
    {
        $user = new User(['email' => 'invalid-email']);

        // We're not hitting the database, so Model::create() won't run validation.
        // You can test your own email-checking helper/mutator here, if you have one.
        // For demonstration, let's assert that PHP’s filter_var detects it as invalid:
        $this->assertFalse(filter_var($user->email, FILTER_VALIDATE_EMAIL));
    }
}
