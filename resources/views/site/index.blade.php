@extends('layout.site')
@section('content')
<div class="card big-card">
    <div class="card-body">
        <section class="carousel-card">
            <div id="bannerCarousel" class="carousel slide" data-ride="carousel">

                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <img src="{{asset('images')}}/1.png" alt="Banner 1">
                    </div>
                </div>


                <a class="carousel-control-prev" href="#bannerCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#bannerCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    </div>
</div>

<h2 class="gamehead "> Games </h2>



<div class="container game-cont">
    <div class="row">
        @foreach($games as $game)
        <div class="col-lg-5 col-md-5 col-sm-6 col-6 game-box">
            <div class="game-box-2" style="background-image: url('public/uploads/games/images/{{$game->game_banner}}');">
                <a href="/game/{{$game->id}}"></a>
            </div>
        </div>
        @endforeach
    </div>
</div>


<div class="card small-card">
    <div class="card-body small-card-body">
        <section class="carousel-card">
            <div id="bannerCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <img src="{{asset('images')}}/3.png" alt="Banner 1">
                    </div>

                </div>
                <a class="carousel-control-prev" href="#bannerCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#bannerCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    </div>
</div>
@stop