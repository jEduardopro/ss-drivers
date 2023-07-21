<?php

namespace App\Services;

final class AssignShipmentDestinations
{

    protected array $addresses;
    protected array $drivers;
    public array $assignedDrivers;

    const VOWELS = ['a', 'e', 'i', 'o', 'u'];
    const CONSONANTS = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n','p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'];

    public function __construct(array $addresses, array $drivers)
    {
        $this->addresses = $addresses;
        $this->drivers = $drivers;
    }

    /**
     * time O(n*(m+a+b) )
     * space O(3n)
     */
    public function handle()
    {
        collect($this->addresses)->each(function ($address) {
            $maxAddressSS = 0;
            $assignedDriver = null;
            $total = 0;
            $addressLength = strlen($address);

            collect($this->drivers)->each(function ($driver) use (&$maxAddressSS, &$assignedDriver, &$total, $addressLength) {

                $ss = $this->assignDriver($driver, $addressLength);

                if ($ss > $maxAddressSS) {
                    $maxAddressSS = $ss;
                    $assignedDriver = $driver;
                }
                $total += $ss;
            });

            $this->assignedDrivers[] = [
                'address' => $address,
                'driver' => $assignedDriver,
                'ss' => $maxAddressSS,
                'total' => $total,
            ];

        });
    }

    private function assignDriver(string $driver, int $addressLength)
    {
        $ss = $this->ssForDriver($driver, $addressLength);

        if ($this->shareCommonFactor($addressLength, strlen($driver))) {
            $ss *= 1.5;
        }

        return $ss;
    }

    private function ssForDriver(string $driver, int $addressLength)
    {
        if ($addressLength % 2 === 0) {
            return $this->countMatches($driver, self::VOWELS) * 1.5;
        }

        return $this->countMatches($driver, self::CONSONANTS) * 1;
    }

    private function countMatches(string $driver, array $matches)
    {
        $count = 0;
        $driverToLower = strtolower($driver);

        for ($i = 0; $i < strlen($driver); $i++) {
            if (in_array($driverToLower[$i], $matches)) {
                $count++;
            }
        }

        return $count;
    }

    private function shareCommonFactor(int $addressLength, int $driverNameLength)
    {
        $commonFactor = false;

        for ($i=2; $i <= $addressLength; $i++) {
            if ($addressLength % $i === 0 && $driverNameLength % $i === 0) {
                $commonFactor = true;
                break;
            }
        }

        return $commonFactor;
    }

}
