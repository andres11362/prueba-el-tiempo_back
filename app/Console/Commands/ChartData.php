<?php

namespace App\Console\Commands;

use App\Events\ShowLastMedition;
use App\Jobs\SendDataChart;
use App\models\Medition;
use Illuminate\Console\Command;

class ChartData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chart:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia el evento a pusher en tiempo real';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $last = Medition::orderBy('id', 'desc')->take(1)->first();

        try {
            $data = event(new ShowLastMedition($last));
        } catch (\Throwable $th) {
            dd($th);
        }

        return $data;

        return 'Se va para el chart';
    }
}
