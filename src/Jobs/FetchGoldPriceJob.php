<?php

namespace RayzenAI\GoldSilverPrice\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use RayzenAI\GoldSilverPrice\Services\GoldPriceService;

class FetchGoldPriceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(GoldPriceService $goldPriceService): void
    {
        $goldPriceService->fetchAndStore();
    }
}
