<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->truncate();

        $tags = [
            ['name' => 'Milagros Malang','address' => 'Perum Griyashanta Blk. B No.116 Mojolangu','latitude' => '-7.939794','longitude' => '112.621231','description' => 'Restoran','img' => '#','active' => '1','created_at' => Carbon::now()],
            ['name' => 'SMP Negeri 18 Malang','address' => 'Jalan Soekarno Hatta No. A-394, Lowokwaru','latitude' => '-7.939397','longitude' => '112.619537','description' => 'Bar','img' => '#','active' => '1','created_at' => Carbon::now()],
            ['name' => 'Taman Krida Budaya','address' => 'Jl. Soekarno Hatta No.7 Jatimulyo','latitude' => '-7.942616','longitude' => '112.622520','description' => 'Bar','img' => '#','active' => '1','created_at' => Carbon::now()],
            
        ];
     
        DB::table('tags')->insert($tags);
    }
}
