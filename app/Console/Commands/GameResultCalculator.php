<?php

namespace App\Console\Commands;

use App\Console\GameResultModel;
use App\Models\Bet;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\TimeSlot;
use Exception;
use Illuminate\Console\Command;

class GameResultCalculator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:game-result-calculator';

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
    
}
