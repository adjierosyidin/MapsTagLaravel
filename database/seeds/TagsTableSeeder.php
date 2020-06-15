<?php

use App\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        /* $pictures = collect(range(1,3)); */
        $users = Role::findOrFail(2)->users;

        $maptags = [
            [
                'name' => 'Milagros Malang',
                'address' => 'Perum Griyashanta Blk. B No.116 Mojolangu',
                'latitude' => '-7.939794',
                'longitude' => '112.621231',
                'description' => 'Restoran',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'SMP Negeri 18 Malang',
                'address' => 'Jalan Soekarno Hatta No. A-394, Lowokwaru',
                'latitude' => '-7.939397',
                'longitude' => '112.619537',
                'description' => 'Bar',
                'created_at' => Carbon::now()],
            [
                'name' => 'Taman Krida Budaya',
                'address' => 'Jl. Soekarno Hatta No.7 Jatimulyo',
                'latitude' => '-7.942616',
                'longitude' => '112.622520',
                'description' => 'Bar',
                'created_at' => Carbon::now()
            ],
            
        ];

        $currentAddress = 0;
        $index = 1;

        foreach($users as $user)
        {
            $tag = [
                'active' => 1,
            ];
            $tag = $user->tags()->create(array_merge($tag, $maptags[$currentAddress++]));
            
    
            $tag->copyMedia(public_path("assets/images/tags/a$index.png"))->toMediaCollection('img');
            $index++;
        }

    }
}
