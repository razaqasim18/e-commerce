<?php

namespace App\Console\Commands;

use App\Jobs\usersRankJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class UsersRankCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userrank:cron';

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
        info("Users Rank Job running at " . now());
        $usersRankJob = new usersRankJob();
        Queue::push($usersRankJob);

    }
}
