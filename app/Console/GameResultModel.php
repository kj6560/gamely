<?php

namespace App\Console;

use App\Models\Bet;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\TimeSlot;
use Exception;

class GameResultModel
{
    public function run()
    {
        try {
            $games = Game::all();
            foreach ($games as $game) {
                $timeslots = TimeSlot::select('slot')->where('game_id', $game->id)->get()->toArray();
                $allSlots = [];
                foreach ($timeslots as $slot) {
                    array_push($allSlots, $slot['slot']);
                }
                $timeIntervals = $this->convertToTimeIntervals($allSlots);
                $current_time = date('H:i:s');

                $timeSlot = $this->findTimeslot($current_time, $timeIntervals);
                $current_time = strtotime($current_time);
                $start_time = strtotime($timeSlot[0]);
                $end_time = strtotime($timeSlot[1]);
                $time_left = round(($end_time - $current_time) / 60, 1);
                echo $time_left;
                if ($time_left <= 3) {
                    $slot = TimeSlot::where('game_id', $game->id)->where('slot', $timeSlot[0])->first();
                    $slot->expired_for_the_day = 1;
                    $slot->save();
                    $allBets = Bet::where('game_id', $game->id)->where('slot_id', $slot->id)->get();
                    $betArr = [];
                    foreach ($allBets as $bet) {
                        $betArr[$bet->id] = $bet->bet_amount;
                    }
                    $maxValue = max($betArr);
                    $key = array_search($maxValue, $betArr);
                    $winningBet = Bet::find($key);
                    $winningUserId = $winningBet->user_id;
                    $current_date = date('Y-m-d');
                    $gameResult = GameResult::where('game_id', $game->id)->where('game_date', $current_date)->where('slot_id', $slot->id)->first();
                    if (empty($gameResult)) {
                        $game_result = new GameResult();
                    }
                    $game_result->game_id = $game->id;
                    $game_result->winning_user_id = $winningUserId;
                    $game_result->game_date = $current_date;
                    $game_result->slot_id = $slot->id;
                    $game_result->save();
                }
            }
            return true;
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
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
    }
}
