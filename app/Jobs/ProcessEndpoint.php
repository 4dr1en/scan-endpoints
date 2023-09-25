<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\TargetsMonitored;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessEndpoint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private TargetsMonitored $target)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // get response
        $response = $this->testEndpoint();

        // create processed target
        $this->target->processedTargets()->create([
            'response_code' => $response['response_code'],
            'response_time' => $response['response_time']
        ]);
    }

    /**
     * test if the target is up
     */
    private function testEndpoint(): array | false
    {
        $protocol = '';
        if (
            !$this->target->protocol
        ) {
            $protocol = 'http://';
        } elseif ($this->target->protocol === 'https' || $this->target->protocol === 'http') {
            $protocol = $this->target->protocol . '://';
        }

        $port = '';
        if ($this->target->port) {
            $port = ':' . $this->target->port;
        }

        $url = $protocol . $this->target->path . $port;

        $start = hrtime(true);
        $headers = @get_headers($url);
        $stop = hrtime(true);


        if ($headers != false) {
            Log::info('Endpoint ' . $this->target->name . ' (id: ' . $this->target->id . ') response time: ' . ($stop - $start) / 1e+6 . ' ms, response code: ' . $headers[0]);
            return [
                'response_code' => substr($headers[0], 9, 3),
                'response_time' => ($stop - $start) / 1e+6
            ];
        }

        Log::warning('Endpoint ' . $this->target->name . ' (id: ' . $this->target->id . ') check failed.');

        return [
            'response_code' => 0,
            'response_time' => 0
        ];
    }
}
