@extends('layout.site')
@section('content')


<div class="card">
    <div class="card-body" style="background-color: black;">
        <h3 class="card-title" style="color: #fff;">{{$game->game_name}}</h3>
        <p class="card-text" style="color: #fff;">{{$game->game_description}}</p>
        <p class="card-text" style="color: #fff;">Start Time: {{convertUtcToIst($current_time,'H:i:s')}} End Time: {{convertUtcToIst($end_time,'H:i:s')}}</p>
        <form action="/placeBet" method="POST">
            @csrf
            <input type="text" name="slot_id" value="{{$slot->id}}" hidden>
            <input type="text" name="game_id" value="{{$game->id}}" hidden>
            <div class="mb-3">
                <label for="" class="form-label">
                    <p class="card-text" style="color: #fff;">Enter Bet</p>
                </label>
                <input type="text" class="form-control" name="amount" id="bet" aria-describedby="helpId" placeholder="Enter Bet" />
                <small id="helpId" class="form-text text-muted">Place Bet</small>
            </div>
            <button type="submit" class="btn btn-primary">
                Bet
            </button>
        </form>
    </div>
    <br>
    <div class="card-body" style="background-color: black;">
        <h3 class="card-title" style="color: #fff;">Results</h3>
        <h3 class="card-title" style="color: #fff;">{{$game->game_name}}</h3>
        <p class="card-text" style="color: #fff;">{{$game->game_description}}</p>
        <div
            class="table-responsive">
            <table
                class="table table-primary">
                <thead>
                    <tr>
                        <th scope="col">Slot Start Time</th>
                        <th scope="col">Game Date</th>
                        <th scope="col">Winning User</th>
                    </tr>
                </thead> 
                <tbody>
                    @foreach ($results as $result)
                    <tr class="">
                        <td scope="row">{{convertUtcToIst($result->slot,'H:i:s')}}</td>
                        <td>{{convertUtcToIst($result->game_date,'d-m-Y')}}</td>
                        <td>{{ucfirst($result->name)}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>


    </div>
</div>

<script>
    var current_time = "{{convertUtcToIst($current_time)}}"; // Replace with your PHP variable holding current time
    var end_time = "{{convertUtcToIst($end_time)}}"; // Replace with your PHP variable holding end time
    var slot_interval = "{{$game->slot_interval}}";
    // Split current_time into hours, minutes, and seconds
    let [hours, minutes, seconds] = current_time.split(':').map(Number);

    // Create a new Date object and set the time components
    let currentTime = new Date();
    currentTime.setHours(hours);
    currentTime.setMinutes(minutes);
    currentTime.setSeconds(seconds);

    // Split end_time into hours, minutes, and seconds
    let [hours1, minutes1, seconds1] = end_time.split(':').map(Number);

    // Create a new Date object and set the time components
    let endTime = new Date();
    endTime.setHours(hours1);
    endTime.setMinutes(minutes1);
    endTime.setSeconds(seconds1);

    // Calculate time difference in milliseconds
    var time_difference = endTime.getTime() - currentTime.getTime();

    // Function to be executed on each tick
    function myFunction() {
        console.log("Hello");
    }

    // Execute myFunction immediately
    myFunction();

    // Schedule myFunction to run every second until endTime is reached
    var intervalId = setInterval(myFunction, 1000); // Run every 1000 milliseconds (1 second)

    // Calculate the remaining time until endTime
    var remainingTime = time_difference;

    // Set the reload time (2 seconds before endTime)
    var reloadTime = remainingTime - 2000;

    // If remainingTime is negative or zero, clear the interval and stop execution
    if (remainingTime <= 0) {
        clearInterval(intervalId);
    } else {
        // Otherwise, set a timeout to stop the interval when the remainingTime is up
        if (time_difference == slot_interval) {
            setTimeout(function() {
                clearInterval(intervalId);
                // Reload the page
                location.reload();
            }, reloadTime);
        }
    }
</script>
@stop