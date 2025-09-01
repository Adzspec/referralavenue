<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncOffersAdtraction;

class SyncAdtraction extends Command
{
    // company first? your job ctor is ($channelId, $companyId), so we accept both explicitly.
    protected $signature = 'sync:adtraction
        {company : Company ID}
        {channel : Channel ID}
        {--now : Run synchronously (no queue worker needed)}
        {--queue=integrations : Queue name}
        {--connection= : Queue connection (e.g. redis, database)}';

    protected $description = 'Dispatch Adtraction master sync for a given company and channel';

    public function handle(): int
    {
        $companyId = (string) $this->argument('company');
        $channelId = (string) $this->argument('channel');
        $queue     = (string) $this->option('queue');
        $connection = $this->option('connection');

        if ($this->option('now')) {
            // Run inline, useful for testing
            SyncOffersAdtraction::dispatchSync($channelId, $companyId);
            $this->info("✅ Ran sync NOW for company={$companyId}, channel={$channelId}");
            return self::SUCCESS;
        }

        $pending = SyncOffersAdtraction::dispatch($channelId, $companyId)->onQueue($queue);
        if ($connection) {
            $pending->onConnection($connection);
        }

        $this->info("🚀 Dispatched job for company={$companyId}, channel={$channelId} on queue='{$queue}'"
            . ($connection ? " (connection='{$connection}')" : ''));
        $this->line('Make sure a queue worker is running to process it.');
        return self::SUCCESS;
    }
}
