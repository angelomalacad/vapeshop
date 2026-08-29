<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InventoryReservationService;

class ExpireReservations extends Command
{
    protected $signature = 'inventory:expire-reservations';
    protected $description = 'Expire old inventory reservations';

    protected $reservationService;

    public function __construct(InventoryReservationService $reservationService)
    {
        parent::__construct();
        $this->reservationService = $reservationService;
    }

    public function handle()
    {
        $count = $this->reservationService->expireReservations();
        
        $this->info("Expired {$count} reservations.");
        
        return Command::SUCCESS;
    }
}