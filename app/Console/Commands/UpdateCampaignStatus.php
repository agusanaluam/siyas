<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Master\Campaign;

class UpdateCampaignStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update campaign status based on current date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating campaign statuses...');

        $campaigns = Campaign::all();
        $updated = 0;

        foreach ($campaigns as $campaign) {
            $newStatus = Campaign::determineStatus($campaign->start_date, $campaign->end_date);
            
            if ($campaign->status !== $newStatus) {
                $campaign->update(['status' => $newStatus]);
                $updated++;
            }
        }

        $this->info("Updated {$updated} campaign(s).");
        
        return Command::SUCCESS;
    }
}
