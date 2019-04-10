@extends('layouts.master') 
@section('content')

<h1 class="text-center my-2">{{$movie->title}}</h1>
<div class="movie-card card mb-4">
  <div class="d-flex flex-wrap justify-content-around py-2">
    <div class="movie-card card mw-500px align-self-start">
      @if($movie->poster_path !== null)
      <img class="card-img-top" src="http://image.tmdb.org/t/p/w500//{{$movie->poster_path}}" alt="{{$movie->title}}" />      @else
      <img class="card-img-top" src="https://via.placeholder.com/500x250.png?text=No+Poster+Available" alt="{{$movie->title}}"
      /> @endif
      <div class="card-body">
        <h5 class="card-title">{{ $movie->original_title }} ({{ $movie->release_date }})</h5>
        <p class="card-text">{{ $movie->overview }}</p>
        <div class="mb-3">
          @if ($tot_rating && count($reviews) > 0)
          <div> BMD score: <img width="30%" src="{{ asset('assets/'.$tot_rating.'.svg') }}" /> </div> @else
          <p>This movie has not yet been rated at BMD</p>
          @endif @if($movie->vote_average)
          <p>TMDb score: <span style="font-weight: bold; font-size: 2em;">{{$movie->vote_average}}</span></p>
          @else
          <p>This movie has not yet been rated at TMDb</p>
          @endif

        </div>
        @if($user_id !== null && count($watchlists)>0)
        <form class="form-inline my-2 my-lg-0" method="POST" action="/watchlists">
          @csrf
          <input name="title" value="{{ $movie->original_title }}" hidden />
          <input name="movie_id" value="{{ $movie->id }}" hidden />
          <input name="poster_url" value="{{ $movie->poster_path }}" hidden />
          <select class="browser-default custom-select mr-sm-2" name="list_id" required>
            <option selected value="">Select watchlist:</option>
            @foreach ($watchlists as $wl)
            <option value="{{$wl->id}}">{{$wl->title}}</option>   
            @endforeach
        </select>
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Add to list</button>
        </form>
        @endif
        @if($user_id !== null)
        <p>
          <a class="btn btn-outline-success my-2 my-sm-0" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false"
            aria-controls="multiCollapseExample1">Create a new watchlist</a>
        </p>
        <div class="row">
          <div class="col">
            <div class="collapse multi-collapse" id="multiCollapseExample1">
              <div class="card card-body">
                <form class="form-inline my-2 my-lg-0" method="GET" action="/watchlists/create">
                  @csrf
                  <input type="text" name="title" class="form-control mr-sm-2" value="" placeholder="Enter List Title" required/>
                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Add list</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        @elseif ($user_id === null)
        <a href="/login" class="btn btn-warning my-2 my-sm-0">Login to create a watchlist and add this movie</a>
         @endif
      </div>
    </div>

    <div class="videos">
      @foreach ($trailers as $trailer)
      <h5 class="text-center">{{$trailer->name}}</h5>
      <div class="videocontainer mb-2">
        <div class="videowrapper">
          <iframe class="responsiveiframe" title="{{$trailer->name}}" src="https://www.youtube.com/embed/{{$trailer->key}}" allowfullscreen></iframe>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div id="accordion">
    <div class="card mx-auto">
      <div class="card-header" id="headingOne">
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Write Review
          </button>
        </h5>
      </div>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
          @if($user_id !== null)
          <form method="POST" action="/reviews">
            @csrf
            <input type="hidden" name="movie_tmdb_id" value="{{$movie->id}}">
            <input type="hidden" name="movie_title" value="{{$movie->title}}">
            <div class="row my-2">
              <label class="px-2" for="headline">Headline</label>
              <input type="text" class="form-control mx-3" name="headline" placeholder="Enter headline" required/>
            </div>
            <div class="row my-2">
              <label class="px-2" for="headline">Content</label>
              <textarea name="content" class="form-control mx-3" rows="5" placeholder="Enter content" required></textarea>
            </div>
            <select class="form-control mx-auto" name="rating" required>
                  <option selected disabled>Rate the movie</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
            <button class="btn btn-danger mt-2" type="submit">Submit</button>
          </form>
          @else
          <a href="/login" class="btn btn-warning my-2 my-sm-0">Login to write a review</a> @endif
        </div>
      </div>
    </div>
    <div class="card mx-auto">
      <div class="card-header" id="headingTwo">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
           User Reviews
          </button>
        </h5>
      </div>
      <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
          @if (count($reviews) > 0) @foreach ($reviews as $review)
          <div class="container bg-lighter text-dark text-dark mb-2 p-2">
            <div class="d-flex flex-row">
              <h4>{{$review->headline}}</h4>
              @if ($user_id && $review->user_id === $user_id)
              <div class="review-delete-btn">
                <form class="delete-review">
                  @csrf @method('DELETE')
                  <input type="hidden" name="id" value="{{$review->id}}" />
                  <button type="submit" class="btn btn-danger">X</button>
                </form>
              </div>
            </div>
            <p>{{$review->content}}</p>
            @endif
            <div class="mb-3">
              @if($review->rating === null)
              <img src="{{ asset('assets/null.svg') }}" /> @else
              <img src="{{ asset('assets/'.($review->rating*10).'.svg') }}" /> @endif
            </div>
            @if (count($comments) > 0)
            <button class="btn btn-success mb-2" type="button" data-toggle="collapse" data-target="#collapseComments{{$review->id}}"
              aria-expanded="false" aria-controls="collapseComments{{$review->id}}">
            Toggle comments
          </button>
            <div class="collapse" id="collapseComments{{$review->id}}">
              @foreach($comments as $comment) @if($comment->review_id === $review->id)
              <div class="card mb-2 bg-light text-dark p-2">
                <div class="d-flex flex-row">
                  <div class="w-100">
                    <p>{{ $comment->content }}</p>
                  </div>
                  @if ($user_id && $comment->user_id === $user_id)
                  <div class="comment-delete-btn">
                    <form class="delete-comment">
                      @csrf @method('DELETE')
                      <input type="hidden" name="id" value="{{$comment->id}}" />
                      <button type="submit" class="btn btn-danger">X</button>
                    </form>
                  </div>
                  @endif
                </div>
                <small>By: {{ $comment->user_name }}</small>
                <small>{{ $comment->created_at }}</small> @if($comment->created_at != $comment->updated_at)
                <small>Edited at:{{ $comment->updated_at }}</small> @endif
              </div>
              @endif @endforeach
            </div>
            @endif
            <div class="card mb-2 bg-light text-dark p-2 mt-2">
              @if ($user_id !== null)
              <h5 class="mt-2">Add a comment:</h5>
              <form method="POST" action="/comments">
                @csrf
                <input type="hidden" name="movie_tmdb_id" value="{{$movie->id}}">
                <input type="hidden" name="review_id" value="{{$review->id}}">
                <div class="row my-2 p-2">
                  <input type="text" name="content" class="form-control mx-3" placeholder="Enter content" required/>
                </div>
                <button class="btn btn-danger my-2 mx-3" type="submit">Submit</button>
              </form>
              @else
              <a href="/login" class="btn btn-warning my-2 my-sm-0">Login to comment</a> @endif
            </div>
          </div>
        </div>
        @endforeach @endif @if (count($reviews) == 0)
        <h5>No reviews for this movie yet!</h5>
        @endif
      </div>
    </div>
  </div>
</div>
</div>
<script>
  const commentToDelete = $('.delete-comment');
     function deleteComment (event) {
        event.preventDefault();
        $.ajax(
          {
          url: `/comments/${event.target[2].value}`,
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }).done(() => {
        $(this).closest('.card').remove();
        });
      }
      commentToDelete.on('submit', deleteComment)


      const reviewToDelete = $('.delete-review');
     function deleteReview (event) {
        event.preventDefault();
        console.log(event.target[2].value);
        
        $.ajax(
          {
          url: `/reviews/${event.target[2].value}`,
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }).done(() => {
        $(this).closest('.container').remove();
        });
      }
      reviewToDelete.on('submit', deleteReview)

</script>
@endsection