<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\TimeSlot;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function listGames(Request $request)
    {
        $games = Game::all();
        return view('admin.list_games',['games'=>$games]);
    }

    public function createGame(Request $request){

        return view('admin.create_game');
    }

    public function StoreGame(Request $request){
        $id = $request->id;
        if(!empty($id)){
            $game = Game::find($id);
        } else {
            $game = new Game();
        }
        
        $game->game_name = $request->game_name;
        $game->game_description = $request->game_description;
        
        if(!empty($request->file('game_banner'))){
            $game_banner_resp = $this->uploadFile($_FILES['game_banner'], public_path('uploads/games/images'));
            if(!empty($game_banner_resp['file_name'])){
                $game->game_banner = $game_banner_resp['file_name'];
            }
        }
        
        $game->slot_duration = $request->slot_duration;
        $game->is_available = $request->is_available;
        
        if($game->save()){
            $timeSlotsCount = TimeSlot::where('game_id', $game->id)->count();
            
            if($timeSlotsCount == 0){
                $startTimestamp = $game->created_at->format('Y-m-d H:i:s'); // Ensure correct format
                $timeSlots = $this->createTimeSlots($startTimestamp, $game->slot_duration);
                
                $processed = [];
                foreach($timeSlots as $slot){
                    $timeSlot = new TimeSlot();
                    $timeSlot->game_id = $game->id;
                    $timeSlot->slot = $slot; // Use the generated slot time
                    $timeSlot->save();
                    array_push($processed, $timeSlot->id);
                }
                
                if(count($processed) == count($timeSlots)){
                    return redirect()->route('listGames')->with('success', 'Game created successfully');
                } else {
                    return redirect()->route('listGames')->with('error', 'Error creating game');
                }
            }
            
            return redirect()->route('listGames')->with('success', 'Game updated successfully');
        }
        
        return redirect()->route('listGames');
    }

    public function editGame(Request $request, $id){
        $game = Game::find($id);
        return view('admin.create_game', ['game'=>$game]);
    }

    public function deleteGame(Request $request, $id){
        $game = Game::find($id);
        if(!empty($game)){
            $game->delete();
            return redirect()->route('listGames')->with('success', 'game deleted successfuly');
        }
        return redirect()->route('listGames');
    }
    function createTimeSlots($startTimestamp,$slot_duration) {
        $startTime = new DateTime($startTimestamp);
        $timeSlots = [];
        $slots = 24/($slot_duration/60);
        for ($i = 0; $i < $slots; $i++) { 
            $timeSlots[] = $startTime->format('H:i:s');
            $startTime->add(new DateInterval('PT15M'));
        }
        return $timeSlots;
    }
}
