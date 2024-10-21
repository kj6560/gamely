<?php

namespace App\Console\Commands;

use App\Console\GameResultModel;
use App\Models\Bet;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\TimeSlot;
use Illuminate\Console\Command;

class CalculateResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gameResultModel = new GameResultModel;
        $gameResultModel->run();
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

        // Return null if no slot is found
        return null;
    }
}
