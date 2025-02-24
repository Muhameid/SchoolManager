<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SeederJob implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable,SerializesModels;
    public $tenant_id; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant_id)
        {
            $this->tenant_id = $tenant_id;
        }

        /**
         * Execute the job.
         *
         * @return void
         */
        public function handle()
        {
            Artisan::call('tenants:seed', ['--tenants' => $this->tenant_id,'--force' => true]);
        }
}
