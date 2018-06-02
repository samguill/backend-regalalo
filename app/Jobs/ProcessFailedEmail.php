<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\Job;
use Dusterio\PlainSqs\Jobs\DispatcherJob;

class ProcessFailedEmail extends DispatcherJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data = null)
    {
        parent::__construct($data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Job $job, $data)
    {
        var_dump($data);
    }
}
