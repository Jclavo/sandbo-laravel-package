<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Jclavo\Profiles\Models\Profile;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();

        $this->model = 'profiles';
        $this->endpoint = "api/{$this->model}";
        $this->records = 2; 

        Profile::factory()->count($this->records)->create();
    }

    public function modelStructure()
    {
        return [
            'id',
            'name',
            'description',
            'activated',
            'fixed',
       ];
    }

    /**
     * test_read_profiles
     *
     * @return void
     */
    public function test_read_profiles()
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => self::modelStructure()
                ]);

        $this->assertCount($this->records, $response->decodeResponseJson());
    }

    /**
     * test_read_profile
     *
     * @return void
     */
    public function test_read_profile()
    {
        $profile = Profile::all()->random();

        $response = $this->get("{$this->endpoint}/{$profile->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(self::modelStructure());
    }

    /**
     * test_create_profile_required_name
     *
     * @return void
     */
    public function test_create_profile_required_name()
    {
        $profile = Profile::factory()->make();

        $response = $this->post($this->endpoint, []);

        $response->assertStatus(500)
                ->assertJson([
                    'status' => false,
                    'message' => 'The name field is required.'
                ]);
    }

    /**
     * test_create_profile
     *
     * @return void
     */
    public function test_create_profile()
    {
        $profile = Profile::factory()->make();

        $response = $this->post($this->endpoint, [
            'name' => $profile->name,
            'description' => $profile->description,
            'activated' => $profile->activated,
            'fixed' => $profile->fixed
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(self::modelStructure());


        $this->assertDatabaseHas('profiles', [
            'name' => $profile->name,
            'description' => $profile->description,
            'activated' => $profile->activated,
            'fixed' => $profile->fixed
        ]);

        $response = $response->decodeResponseJson();
        $this->assertEquals($profile->name, $response['name']);
        $this->assertEquals($profile->description, $response['description']);
        $this->assertEquals($profile->activated, $response['activated']);
        $this->assertEquals($profile->fixed, $response['fixed']);
    }

    /**
     * test_update_profile_required_name
     *
     * @return void
     */
    public function test_update_profile_required_name()
    {
        $profile = Profile::all()->random();
        $profileNew = Profile::factory()->make();

        $response = $this->put("{$this->endpoint}/{$profile->id}", []);

        $response->assertStatus(500)
                ->assertJson([
                    'status' => false,
                    'message' => 'The name field is required.'
                ]);
    }

    /**
     * test_update_profile
     *
     * @return void
     */
    public function test_update_profile()
    {
        $profile = Profile::all()->random();
        $profileNew = Profile::factory()->make();

        $response = $this->put("{$this->endpoint}/{$profile->id}", [
            'name' => $profileNew->name,
            'description' => $profile->description,
            'activated' => $profile->activated,
            'fixed' => $profile->fixed
        ]);

        $response->assertStatus(200)
                  ->assertJsonStructure(self::modelStructure());

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'name' => $profileNew->name,
            'description' => $profile->description,
            'activated' => $profile->activated,
            'fixed' => $profile->fixed
        ]);

        $response = $response->decodeResponseJson();
        $this->assertEquals($profile->id, $response['id']);
        $this->assertEquals($profileNew->name, $response['name']);
        $this->assertEquals($profile->description, $response['description']);
        $this->assertEquals($profile->activated, $response['activated']);
        $this->assertEquals($profile->fixed, $response['fixed']);
    }


    /**
     * test_delete_profile_not_found
     *
     * @return void
     */
    public function test_delete_profile_not_found()
    {
        $response = $this->delete("{$this->endpoint}/0");

        $response->assertStatus(500)
                ->assertJson([
                    'status' => false
                ]);
    }

    /**
     * test_delete_profile
     *
     * @return void
     */
    public function test_delete_profile()
    {
        $profile = Profile::all()->random();

        $response = $this->delete("{$this->endpoint}/{$profile->id}");

        $response->assertStatus(200)
                ->assertJsonStructure(self::modelStructure());

        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id,
        ]);

        $response = $response->decodeResponseJson();
        $this->assertEquals($profile->id, $response['id']);
        $this->assertEquals($profile->name, $response['name']);
        $this->assertEquals($profile->description, $response['description']);
        $this->assertEquals($profile->activated, $response['activated']);
        $this->assertEquals($profile->fixed, $response['fixed']);
    }

    // /**
    //  * test_activate_profile
    //  *
    //  * @return void
    //  */
    // public function test_activate_profile()
    // {
    //     $profile = Profile::factory()->create(['activated' => false]);

    //     $response = $this->get("{$this->endpoint}/activate/{$profile->id}");

    //     $response->assertStatus(200)
    //             ->assertJsonStructure(self::modelStructure());

    //     $this->assertDatabaseHas('profiles', [
    //         'id' => $profile->id,
    //         'activated' => true
    //     ]);

    //     $response = $response->decodeResponseJson();
    //     $this->assertEquals($profile->id, $response['id']);
    //     $this->assertEquals(true, $response['activated']);
    // }

    // /**
    //  * test_desactivate_profile
    //  *
    //  * @return void
    //  */
    // public function test_desactivate_profile()
    // {
    //     $profile = Profile::factory()->create();

    //     $response = $this->get("{$this->endpoint}/desactivate/{$profile->id}");

    //     $response->assertStatus(200)
    //             ->assertJson([
    //                 'status' => true,
    //                 'message' => 'change.activated.status'
    //             ])
    //             ->assertJsonStructure([
    //                 'result' => self::modelStructure()
    //             ]);

    //     $this->assertDatabaseHas('profiles', [
    //         'id' => $profile->id,
    //         'activated' => false
    //     ]);

    //     $response = $response['result'];
    //     $this->assertEquals($profile->id, $response['id']);
    //     $this->assertEquals(false, $response['activated']);
    // }
}
