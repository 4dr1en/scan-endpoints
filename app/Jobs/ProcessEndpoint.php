<?php

namespace App\Jobs;

use App\Mail\EndpointDown;
use Illuminate\Bus\Queueable;
use App\Models\TargetsMonitored;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

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

        $username = $this->target->user->display_name ?? $this->target->user->first_name . ' ' . $this->target->user->last_name;
        $enpointIdentifier =
            $this->target->name ?: ($this->target->protocol . '://' . $this->target->path . ($this->target->port ? (':' . $this->target->port) : ''));

        if (!str_starts_with($response['response_code'], '2') && !str_starts_with($response['response_code'], '3')) {
            Log::info('Endpoint ' . $enpointIdentifier . ' (id: ' . $this->target->id . ') response time: ' .  $response['response_time'] . ' ms, response code: ' . $response['response_code']);

            Mail::to($this->target->user->email)->send(new EndpointDown([
                'username' => $username,
                'enpointIdentifier' => $enpointIdentifier
            ]));
        }

        // create processed target
        $this->target->processedTargets()->create([
            'response_code' => (int) $response['response_code'],
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
