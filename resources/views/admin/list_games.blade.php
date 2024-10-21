@extends('layout.admin')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="content-wrapper">
        <!-- Responsive Table -->
        <div class="card">
            <h5 class="card-header">Games</h5> <a href="/dashboard/createGame">Create</a>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr class="text-nowrap">
                            <th>Game Id</th>
                            <th>Game Name</th>
                            <th>Game Description</th>
                            <th>Game Banner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($games as $game)
                        <tr>
                            <th scope="row">{{$game->id}}</th>
                            <td>{{$game->game_name}}</td>
                            <td>{{substr($game->game_description,0,50)."..."}}</td>
                            <td><img src="{{asset('uploads/games/images/'.$game->game_banner)}}" height="50" width="50" alt="Avatar" class="rounded-circle" /></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/dashboard/editGame/{{$game->id}}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                        <a class="dropdown-item" href="/dashboard/deleteGame/{{$game->id}}"><i class="bx bx-trash me-2"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Responsive Table -->
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
@stop