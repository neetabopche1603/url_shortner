<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use App\Services\UrlService;
use Illuminate\Console\Command;

class ProcessExpiredUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urls:process-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired URLs and send notifications';

    /**
     * The URL service instance.
     *
     * @var UrlService
     */
    protected $urlService;

    /**
     * The notification service instance.
     *
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * Create a new command instance.
     *
     * @param UrlService $urlService
     * @param NotificationService $notificationService
     * @return void
     */
    public function __construct(UrlService $urlService, NotificationService $notificationService)
    {
        parent::__construct();
        $this->urlService = $urlService;
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing expired URLs...');

        // Process expired URLs
        $expiredCount = $this->urlService->processExpiredUrls();
        $this->info("{$expiredCount} URLs marked as expired.");

        // Send notifications for expired URLs
        $notifiedCount = $this->notificationService->processExpiredUrlNotifications();
        $this->info("{$notifiedCount} expiration notifications sent.");

        return Command::SUCCESS;
    }
}
