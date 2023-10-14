<?php

namespace App\Services;

use App\Jobs\ProcessEndpoint;
use App\Models\TargetsMonitored;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class LaunchTargetCheckService
{
    public function __construct(
        private TargetsMonitored $targetsMonitored
    ) {
    }

    public function launch()
    {
        Log::info('LaunchTargetCheckService launch');
        // Get all targets
        $endpointsToProcess = $this->retrieveTargetsToProcess();

        // Launch job for each target
        foreach ($endpointsToProcess as $endpoint) {
            ProcessEndpoint::dispatch($endpoint);
        }
    }

    private function retrieveTargetsToProcess(): LazyCollection
    {
        $targets = $this->targetsMonitored::where('status', 'active')
            ->doesnthave('processedTargets.targetsMonitored', 'and', function ($query) {
                $query->whereRaw(
                    'processed_targets.created_at > DATE_SUB(NOW(), INTERVAL targets_monitoreds.interval SECOND)'
                ); // If there is a processed target that is newer than the interval, (we have a 'doesnt have' before that)
            })->lazyById();

        return $targets;
    }
}