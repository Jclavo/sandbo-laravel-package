<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Jclavo\Profiles\Models\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::factory(10)->create();
    }
}
