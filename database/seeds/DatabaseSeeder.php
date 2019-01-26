<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(PeopleTableSeeder::class);
        // $this->call(MoviesTableSeeder::class);
        // $this->call(SeriesTableSeeder::class);
        // $this->call(DirectionsTableSeeder::class);
        // $this->call(CastsTableSeeder::class);
        // $this->call(ProductionsTableSeeder::class);
        factory(App\Director::class, 19)->create();
        factory(App\Cast::class, 100)->create();
        factory(App\Genre::class, 20)->create();


        factory(App\Movie::class, 100)->create()->each(function ($movie) {
            $boolean = random_int(0, 1);

            $ids_directors = range(1, 19);
            $ids_casts = range(1, 100);
            $ids_genres = range(1, 20);


            shuffle($ids_directors);
            shuffle($ids_casts);
            shuffle($ids_genres);
            

            if ($boolean)
            { 
                $sliced_directors = array_slice($ids_directors, 0, 2);
                $sliced_casts = array_slice($ids_casts, 0, 2);
                $sliced_genres = array_slice($ids_genres, 0, 2);

                $movie->directors()->attach($sliced_directors);
                $movie->casts()->attach($sliced_casts);
                $movie->genres()->attach($sliced_genres);

            } else {
                $movie->directors()->attach(mt_rand(1, 19));
                $movie->casts()->attach(mt_rand(1, 100));
                $movie->genres()->attach(mt_rand(1, 20));
            }
        });

        factory(App\Series::class, 50)->create()->each(function ($series) {
            $boolean = random_int(0, 1);

            $ids_directors = range(1, 19);
            $ids_casts = range(1, 100);
            $ids_genres = range(1, 20);


            shuffle($ids_directors);
            shuffle($ids_casts);
            shuffle($ids_genres);
            

            if ($boolean)
            { 
                $sliced_directors = array_slice($ids_directors, 0, 2);
                $sliced_casts = array_slice($ids_casts, 0, 2);
                $sliced_genres = array_slice($ids_genres, 0, 2);

                $series->directors()->attach($sliced_directors);
                $series->casts()->attach($sliced_casts);
                $series->genres()->attach($sliced_genres);

            } else {
                $series->directors()->attach(mt_rand(1, 19));
                $series->casts()->attach(mt_rand(1, 100));
                $series->genres()->attach(mt_rand(1, 20));
            }
        });
    }
}