<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $reviews = Review::where('user_id', $user_id)->take(20)->get();
        return view('reviews.reviews')->with('reviews', $reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $headline = $request->input('headline');
        $content = $request->input('content');
        $rating = $request->input('rating');
        $movie_tmdb_id = $request->input('movie_tmdb_id');
        $user_id = $request->user()->id;

        // store
        $review = new Review;
        $review->headline = $headline;
        $review->content = $content;
        $review->rating = $rating;
        $review->movie_tmdb_id = $movie_tmdb_id;
        $review->user_id = $user_id;
        $review->save();
    
        // ADD PROPER REDIRECT HERE
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show($review_id, Request $request)
    {
        $user_id = $request->user()->id;
        $movie_tmdb_id = $request->movie_id;
        $review = Review::where('user_id', $user_id)->find($review_id);

        $client = new \GuzzleHttp\Client();
        $apikey = env('TMDB_API_KEY', '');

        $movie_fetch = $client->get("https://api.themoviedb.org/3/movie/$movie_tmdb_id?api_key=$apikey");

        $response = json_decode($movie_fetch->getBody());
    
        return view('reviews.review', [
            'movie' => $response,
            'review' => $review
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}