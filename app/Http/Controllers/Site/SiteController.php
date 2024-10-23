<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $usr = Auth::user();
        $allGames = Game::where('is_available',1)->get();
        return view('site.index', ['user' => $usr, 'games' => $allGames]);
    }
    public function game(Request $request, $id)
    {
        $usr = Auth::user();
        $game = Game::where('id', $id)->first();
        $timeslots = TimeSlot::select('slot')->where('game_id', $id)->get()->toArray();
        $current_time = date('H:i:s');
        $allSlots = [];
        foreach ($timeslots as $slot) {
            array_push($allSlots, $slot['slot']);
        }

        $timeIntervals = $this->convertToTimeIntervals($allSlots);

        $timeSlot = $this->findTimeslot($current_time, $timeIntervals);
        $slot = TimeSlot::select('id')->where('game_id', $id)->where('slot', $timeSlot[0])->first();
        $slot_id = $slot->id;

        $current_date = date('Y-m-d');

        $gameResults = GameResult::
        join('timeslots', 'timeslots.id', '=', 'game_results.slot_id')
        ->join('users','users.id','=','game_results.winning_user_id')
        ->select('game_results.*', 'timeslots.slot', 'users.name')
        ->where('game_results.game_date', $current_date)->get();
        return view('site.game', ['results'=>$gameResults,'user' => $usr, 'game' => $game, 'end_time' => $timeSlot[1], 'current_time' => $current_time, 'slot' => $slot]);
    }
    public function findTimeslot($current_time, $slots)
    {
        // Convert current time to timestamp (assuming it's a time string)
        $current_timestamp = strtotime(date('Y-m-d') . ' ' . $current_time);

        // Sort slots based on start_time
        usort($slots, function ($a, $b) {
            return strtotime($a['start_time']) - strtotime($b['start_time']);
        });

        // Iterate over sorted slots to find the correct timeslot
        for ($index = 0; $index < count($slots); $index++) {
            // Convert start and end times to timestamps
            $start_timestamp = strtotime(date('Y-m-d') . ' ' . $slots[$index]['start_time']);
            $end_timestamp = strtotime(date('Y-m-d') . ' ' . $slots[$index]['end_time']);

            // Handle wrap around midnight
            if ($end_timestamp <= $start_timestamp) {
                $end_timestamp += 24 * 60 * 60; // Add 24 hours
            }

            // Check if the current timestamp falls within this slot
            if ($current_timestamp >= $start_timestamp && $current_timestamp < $end_timestamp) {
                return [$slots[$index]['start_time'], $slots[$index]['end_time']];
            }

            // Special case for the last slot to wrap around midnight
            if ($index === count($slots) - 1 && $current_timestamp >= $end_timestamp) {
                // Check if current time is between the last slot and the first slot of the day
                $start_time_first_of_day = $slots[0]['start_time'];
                $start_timestamp_first_of_day = strtotime(date('Y-m-d', strtotime('+1 day')) . ' ' . $start_time_first_of_day);

                if ($current_timestamp >= $end_timestamp && $current_timestamp < $start_timestamp_first_of_day) {
                    return [$slots[$index]['end_time'], $start_time_first_of_day];
                }
            }
        }

        // Return null if no slot is found
        return null;
    }
    public function convertToTimeIntervals($slots)
    {
        // Initialize an empty array to store intervals
        $intervals = [];

        // Loop through the slots array
        for ($i = 0; $i < count($slots); $i++) {
            $start = $slots[$i];
            // Get the next slot (or wrap around to the first slot)
            $end = $slots[($i + 1) % count($slots)];

            // Store the interval as an associative array
            $intervals[] = [
                'start_time' => $start,
                'end_time' => $end
            ];
        }

        return $intervals;
    }
    public function placeBet(Request $request)
    {
        $slot_id = $request->input('slot_id');
        if (!empty($slot_id)) {
            $usr = Auth::user();
            $amount = $request->input('amount');
            $game_id = $request->input('game_id');
            $user = User::where('id', $usr->id)->first();
            $slot = TimeSlot::where('id', $slot_id)->first();
            $wallet_transaction = new WalletTransaction();
            $wallet_transaction->user_id = $usr->id;
            $wallet_transaction->transaction_amount = $amount;
            $wallet_transaction->transaction_type = 1;
            $wallet_transaction->slot_id = $slot->id;
            $wallet_transaction->game_id = $game_id;
            $wallet_transaction->current_balance = $user->wallet;
            if ($wallet_transaction->save()) {
                $wallet = $user->wallet;
                $wallet = $wallet - $amount;
                $user->wallet = $wallet;
                if($user->save()){
                    $bet = Bet::where('user_id', $usr->id)
                    ->where('game_id',$game_id)
                    ->where('slot_id', $slot->id)
                    ->first();
                    if(!empty($bet)){
                        $bet->bet_amount += $amount;
                    }else{
                        $bet = new Bet();
                        $bet->user_id = $usr->id;
                        $bet->bet_amount = $amount;
                        $bet->game_id = $game_id;
                        $bet->slot_id = $slot->id;
                    }
                    if($bet->save()){
                        return redirect()->back()->with('success', 'Bet placed successfully!');
                    }else{
                        return redirect()->back()->with('error', 'Failed to place bet!');
                    }
                }else{
                    return redirect()->back()->with('error', 'Failed to place bet!');
                }
            } else {
                return redirect()->back()->with('error', 'Failed to place bet!');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid slot!');
        }
    }
    public function accountLedger(Request $request)
    {
        $usr = Auth::user();
        $user = User::where('id', $usr->id)->first();
        $transactions = WalletTransaction::join('games', 'games.id', '=', 'wallet_transaction.game_id')
            ->join('timeslots', 'timeslots.id', '=', 'wallet_transaction.slot_id')
            ->where('user_id', $usr->id)->get();
        return view('site.accountLedger', ['user' => $usr,'wallet_balance'=>$user->wallet, 'transactions' => $transactions]);
    }
}
