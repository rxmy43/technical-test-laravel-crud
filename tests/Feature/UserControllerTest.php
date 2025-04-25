<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_users_and_respects_pagination_sort_and_search()
    {
        // Create 15 users to test pagination (10 per page)
        User::factory()->count(15)->create();

        // Test default index (page 1)
        $response = $this->get(route('users.index'));
        $response->assertStatus(200)
            ->assertViewIs('users.index')
            ->assertViewHas('users');
        $this->assertCount(10, $response->viewData('users')); // 10 per page

        // Test second page
        $response = $this->get(route('users.index', ['page' => 2]));
        $response->assertStatus(200);
        $this->assertCount(5, $response->viewData('users')); // remainder

        // Test search by name
        $target = User::factory()->create(['name' => 'UniqueName']);
        $response = $this->get(route('users.index', ['q' => 'UniqueName']));
        $response->assertSee('UniqueName')
            ->assertViewHas('searchTerm', 'UniqueName');

        // Test sort by age descending
        $u1 = User::factory()->create(['age' => 20]);
        $u2 = User::factory()->create(['age' => 30]);
        $response = $this->get(route('users.index', [
            'sort_by' => 'age',
            'sort_direction' => 'desc',
        ]));
        $users = $response->viewData('users');
        $this->assertTrue($users->first()->age >= $users->last()->age);
    }

    #[Test]
    public function store_creates_user_with_valid_data()
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'age' => rand(18, 80),
        ];

        $response = $this->post(route('users.store'), $data);

        $response->assertRedirect()
            ->assertSessionHas('success', 'User created successfully');
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post(route('users.store'), []); // no data

        $response->assertSessionHasErrors(['name', 'email', 'age']);
    }

    #[Test]
    public function update_modifies_existing_user_with_valid_data()
    {
        $user = User::factory()->create();

        $update = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'age' => 42,
        ];

        $response = $this->put(route('users.update', $user->id), $update);

        $response->assertRedirect()
            ->assertSessionHas('success', 'User updated successfully');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'age' => 42,
        ]);
    }

    #[Test]
    public function update_fails_with_invalid_data()
    {
        $user = User::factory()->create();

        // Missing fields
        $response = $this->put(route('users.update', $user->id), []);
        $response->assertSessionHasErrors(['name', 'email', 'age']);

        // Email collision
        $other = User::factory()->create(['email' => 'other@example.com']);
        $response = $this->put(route('users.update', $user->id), [
            'name' => 'Foo',
            'email' => 'other@example.com', // duplicate
            'age' => 25,
        ]);
        $response->assertSessionHasErrors('email');
    }

    #[Test]
    public function destroy_deletes_user_and_redirects()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user->id));

        $response->assertRedirect()
            ->assertSessionHas('success', 'User deleted successfully');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
