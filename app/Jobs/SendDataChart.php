<?php

namespace App\Jobs;

use App\Events\ShowLastMedition;
use App\models\Medition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDataChart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data = Medition::orderBy('id', 'desc')->take(1)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $event_data = event(new ShowLastMedition($this->data));
        } catch (\Throwable $th) {
            dd($th);
        }

        return $event_data;
    }
}
