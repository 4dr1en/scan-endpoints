<?php

namespace App\Jobs;

use App\Mail\EndpointDown;
use App\Models\TargetsMonitored;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessEndpoint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $endpointIdentifier;

    /**
     * Create a new job instance.
     */
    public function __construct(private TargetsMonitored $target)
    {
        $this->endpointIdentifier =
            $this->target->name ?:
            ($this->target->protocol . '://' . $this->target->path . ($this->target->port ? (':' . $this->target->port) : ''));
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
            'response_code' => (int) $response['response_code'],
            'response_time' => $response['response_time'],
        ]);

        // notify user if endpoint is down
        if (!str_starts_with($response['response_code'], '2') && !str_starts_with($response['response_code'], '3')) {
            $this->notifyUsers($response);
        }
    }

    /**
     * test if the target is up
     */
    private function testEndpoint(): array|false
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
                'response_time' => ($stop - $start) / 1e+6,
            ];
        }

        Log::warning('Endpoint ' . $this->target->name . ' (id: ' . $this->target->id . ') check failed.');

        return [
            'response_code' => 0,
            'response_time' => 0,
        ];
    }

    private function notifyUsers($response): void
    {
        Log::info('Endpoint ' . $this->endpointIdentifier . ' (id: ' . $this->target->id . ') response time: ' . $response['response_time'] . ' ms, response code: ' . $response['response_code']);
        // Get all users of the workspace of the target and send them an email
        $this->target->workspace->users->each(function ($user) {
            $username = $user->display_name ?? $user->first_name . ' ' . $user->last_name;
            Mail::to($user->email)->send(new EndpointDown([
                'username' => $username,
                'enpointIdentifier' => $this->endpointIdentifier,
            ]));
        });
    }
}