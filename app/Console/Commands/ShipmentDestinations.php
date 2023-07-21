<?php

namespace App\Console\Commands;

use App\Services\AssignShipmentDestinations;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ShipmentDestinations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign-destinations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'assigns shipment destinations to drivers in a way that maximizes the total SS over the set of drivers.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $addressesFile = $this->ask('What is the path of your addresses file?');

        $extension = pathinfo($addressesFile, PATHINFO_EXTENSION);

        if ($extension !== "txt") {
            $this->error('The file must be a txt file');
            return Command::FAILURE;
        }

        if (!File::exists(base_path().$addressesFile)) {
            $this->error('We could not get the file, or the file does not exist');
            return Command::FAILURE;
        }
        $addresses = explode(",", File::get(base_path().$addressesFile));

        $driversFile = $this->ask('What is the path of your drivers file?');
        $extension = pathinfo($driversFile, PATHINFO_EXTENSION);

        if ($extension !== "txt") {
            $this->error('The file must be a json file');
            return Command::FAILURE;
        }

        if (!File::exists(base_path().$driversFile)) {
            $this->error('We could not get the file, or the file does not exist');
            return Command::FAILURE;
        }
        $drivers = explode(",", File::get(base_path().$driversFile));

        $assignShipmentDestinations = new AssignShipmentDestinations($addresses, $drivers);
        $assignShipmentDestinations->handle();

        $this->table(['Address', 'Driver', 'SS', 'Total'], $assignShipmentDestinations->assignedDrivers);
    }
}
