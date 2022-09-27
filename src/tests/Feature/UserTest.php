<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();

        $this->model = 'users';
        $this->endpoint = "api/{$this->model}";

        User::factory()->count(10)->create();
    }

    public function modelStructure()
    {
        return [
            'id',
            'name',
            'email'
       ];
    }

    /**
     * test_read_users
     *
     * @return void
     */
    public function test_read_users()
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'crud.pagination'
                ])
                ->assertJsonStructure([
                    'result' => [
                        '*' => self::modelStructure()
                    ]
                ]);

        $this->assertCount(10, $response['result']);
    }

    /**
     * test_read_user
     *
     * @return void
     */
    public function test_read_user()
    {
        $user = User::all()->random();

        $response = $this->get("{$this->endpoint}/{$user->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'crud.read'
                ])
                ->assertJsonStructure([
                    'result' => self::modelStructure()
                ]);
    }

    /**
     * test_create_user_required_name
     *
     * @return void
     */
    public function test_create_user_required_name()
    {
        $user = User::factory()->make();

        $response = $this->post($this->endpoint, []);

        $response->assertStatus(500)
                ->assertJson([
                    'status' => false,
                    'message' => 'The name field is required.'
                ]);
    }

    /**
     * test_create_user
     *
     * @return void
     */
    public function test_create_user()
    {
        $user = User::factory()->make();

        $response = $this->post($this->endpoint, [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'crud.create'
                ])
                ->assertJsonStructure([
                    'result' => self::modelStructure()
                ]);


        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email
        ]);

        $response = $response['result'];
        $this->assertEquals($user->name, $response['name']);
        $this->assertEquals($user->email, $response['email']);
    }

    /**
     * test_update_user_required_name
     *
     * @return void
     */
    public function test_update_user_required_name()
    {
        $user = User::all()->random();
        $userNew = User::factory()->make();

        $response = $this->put("{$this->endpoint}/{$user->id}", []);

        $response->assertStatus(500)
                ->assertJson([
                    'status' => false,
                    'message' => 'The name field is required.'
                ]);
    }

    /**
     * test_update_user
     *
     * @return void
     */
    public function test_update_user()
    {
        $user = User::all()->random();
        $userNew = User::factory()->make();

        $response = $this->put("{$this->endpoint}/{$user->id}", [
            'name' => $userNew->name,
            'email' => $userNew->email,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'crud.update'
                ])
                ->assertJsonStructure([
                    'result' => self::modelStructure()
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $userNew->name,
            'email' => $userNew->email
        ]);

        $response = $response['result'];
        $this->assertEquals($user->id, $response['id']);
        $this->assertEquals($userNew->name, $response['name']);
        $this->assertEquals($userNew->email, $response['email']);
    }


    /**
     * test_delete_user_not_found
     *
     * @return void
     */
    public function test_delete_user_not_found()
    {
        $response = $this->delete("{$this->endpoint}/0");

        $response->assertStatus(500)
                ->assertJson([
                    'status' => false,
                    'message' => 'No query results for model [App\\Models\\User] 0'
                ]);
    }

    /**
     * test_delete_user
     *
     * @return void
     */
    public function test_delete_user()
    {
        $user = User::all()->random();

        $response = $this->delete("{$this->endpoint}/{$user->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'crud.delete'
                ])
                ->assertJsonStructure([
                    'result' => self::modelStructure()
                ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $response = $response['result'];
        $this->assertEquals($user->id, $response['id']);
        $this->assertEquals($user->name, $response['name']);
        $this->assertEquals($user->email, $response['email']);
    }
}
