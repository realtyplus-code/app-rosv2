<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Contracts\PropertyHistoryService;

class SaveHistoricalContracts extends Command
{
    protected $signature = 'app:save-historical-contracts';
    protected $description = 'Command description';
    protected $propertyHistoryService;

    public function __construct(PropertyHistoryService $propertyHistoryService)
    {
        parent::__construct();
        $this->propertyHistoryService = $propertyHistoryService;
    }

    public function handle()
    {
        $this->propertyHistoryService->saveHistoricalContracts();
        $this->info('Contratos históricos actualizados.');
    }
}
