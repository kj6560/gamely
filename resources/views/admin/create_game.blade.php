@extends('layout.admin')
@section('content')
<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="col-xl-12">
			<div class="row">
				<!-- HTML5 Inputs -->
				<form action="/dashboard/storeGame" enctype="multipart/form-data" method="POST">
					@csrf
					@if(isset($game))
					<input type="hidden" name="id" value="{{$game->id}}">
					@endif
					<div class="card mb-4">
						<h5 class="card-header">Create Game</h5>

						<div class="card-body">
							<div class="mb-3 row">
								<label for="html5-text-input" class="col-md-2 col-form-label">Game Name</label>
								<div class="col-md-10">
									<input class="form-control" type="text" name="game_name"
										value="{{isset($game) && $game->game_name?$game->game_name:''}}"
										placeholder="Enter Game Name" id="game_name" />
								</div>
							</div>
							<div class="mb-3 row">
								<label for="html5-text-input" class="col-md-2 col-form-label">Game Description</label>
								<div class="col-md-10">
									<textarea id="game_description" name="game_description" rows="10"
										cols="75">{{isset($game) && $game->game_description?$game->game_description:''}}</textarea>
								</div>
							</div>
							
							<div class="mb-3">
								<label for="formFile" class="form-label">Game Banner</label>
								@if( isset($game) && $game->game_banner)
								<img src="{{asset('uploads/games/images/'.$game->game_banner)}}" height="50"
									width="50" alt="Avatar" class="rounded-circle" />
								@endif
								<input type="file" name="game_banner" placeholder="Select Game Banner" id="inputImage"
									class="form-control">
							</div>

							<div class="mb-3 row">
                                <label for="exampleFormControlSelect1" class="form-label">Select Active</label>
                                <select class="form-select" id="exampleFormControlSelect1" aria-label="Select Active" name="is_available">
                                    <option selected>Select Active</option>
                                    <option value="1" @if(isset($game) && $game->is_available== 1) selected @endif>YES</option>
                                    <option value="0" @if(isset($game) && $game->is_available== 0) selected @endif>NO</option>
                                </select>
                            </div>
                            <div class="mb-3 row">
								<label for="html5-text-input" class="col-md-2 col-form-label">Game Slot Duration</label>
								<div class="col-md-10">
									<input class="form-control" type="text" name="slot_duration"
										value="{{isset($game) && $game->slot_duration?$game->slot_duration:''}}"
										placeholder="Enter Game slot duration" id="slot_duration" />
								</div>
							</div>
							<div class="mb-3 row">
								<label for="html5-search-input" class="col-md-2 col-form-label"></label>
								<div class="col-md-10">
									<input class="btn btn-primary" type="submit" value="submit" id="submit" />
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop