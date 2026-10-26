<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Carpenter;

class CheckCarpenterInactivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carpenters:check-inactivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for active carpenters with no sales over the configured threshold and notify staff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for inactive carpenters...');
        
        $notified = Carpenter::checkInactivityAndNotify();

        if (empty($notified)) {
            $this->info('No new inactive carpenter notifications needed.');
        } else {
            $this->info('Sent notifications for ' . count($notified) . ' inactive carpenter(s):');
            foreach ($notified as $item) {
                $this->line("- {$item['carpenter']}: {$item['days_inactive']} days inactive");
            }
        }

        return 0;
    }
}
