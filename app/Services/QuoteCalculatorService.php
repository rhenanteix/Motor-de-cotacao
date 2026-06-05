<?php

namespace App\Services;

use Carbon\Carbon;
class QuoteCalculatorService
{

    private const DESTINATION_RATES = [
        'NACIONAL' => 10,
        'AMERICAS' => 16,
        'EUROPA' => 22,
    ];

    private const BAGGAGE_PRICE_PER_DAY = 3;

  public function calculate(array $data): array
{
    $chargedDays = $this->calculateChargedDays(
        $data['data_inicio'],
        $data['data_fim']
    );

    $travellers = [];
    $warnings = [];
    $totalGroup = 0;

    foreach ($data['viajantes'] as $traveller) {

        $age = $this->calculateAge(
            $traveller['data_nascimento'],
            $data['data_inicio']
        );

        $multiplier = $this->getAgeMultiplier(
            $age
        );

        $basePrice = $this->calculateBasePrice(
            $data['destino'],
            $chargedDays
        );

       $subtotal = $basePrice * $multiplier;

$addonsResult = $this->applyAddons(
    $traveller,
    $subtotal,
    $age,
    $chargedDays,
    $warnings
);

$subtotal = $addonsResult['subtotal'];

$totalGroup += $subtotal;

     $travellers[] = [
    'nome' => $traveller['nome'],
    'idade' => $age,
    'subtotal' => round($subtotal, 2),
    'adicionais_aplicados' => $addonsResult['applied_addons']
];
    }

    return [
        'dias_cobrados' => $chargedDays,
        'viajantes' => $travellers,
    ];
}

private function calculateChargedDays(
    string $startDate,
    string $endDate
): int {

    $days = Carbon::parse($startDate)
        ->diffInDays(
            Carbon::parse($endDate)
        ) + 1;

    return max(5, $days);
}

private function calculateAge(
    string $birthDate,
    string $tripStartDate
): int {

    return Carbon::parse($birthDate)
        ->diffInYears(
            Carbon::parse($tripStartDate)
        );
}

private function getAgeMultiplier(int $age): float
{
    if($age <= 17) return 0.5;

    if($age <= 64) return 1.0;

    return 2.0;
}

private function getDestinationRate(string $destination): float
{
    return self::DESTINATION_RATES[$destination];
}

private function calculateBasePrice(
    string $destination,
    int $chargedDays
): float {

    return $this->getDestinationRate(
        $destination
    ) * $chargedDays;
}

private function calculateExtraBaggagePrice(
    int $chargedDays,
    int $extraBaggageCount
): float {
    return $chargedDays * self::BAGGAGE_PRICE_PER_DAY * $extraBaggageCount;
}

private function calculatePerPersonPrice(
    array $travelers,
    int $chargedDays,
    float $destinationRate
): float {

    $totalPerPerson = 0.0;

    foreach ($travelers as $traveler) {
        $age = $this->calculateAge($traveler['data_nascimento']);
        $ageMultiplier = $this->getAgeMultiplier($age);
        $basePrice = $this->calculateBasePrice($chargedDays, $destinationRate);
        $extraBaggage = $this->calculateExtraBaggagePrice($chargedDays, $traveler['adicionais']);
        
        $totalPerPerson += ($basePrice * $ageMultiplier) + $extraBaggage;
    }

    return $totalPerPerson;
    }

   private function applyAddons(
    array $traveller,
    float $subtotal,
    int $age,
    int $chargedDays,
    array &$warnings
): array {

    $appliedAddons = [];

    $addons = $traveller['adicionais'] ?? [];

    foreach ($addons as $addon) {

        if ($addon === 'ESPORTES_AVENTURA') {

            if ($age >= 18 && $age <= 64) {

                $subtotal += ($subtotal * 0.25);

                $appliedAddons[] = 'ESPORTES_AVENTURA';

            } else {

                $warnings[] =
                    "ESPORTES_AVENTURA não aplicado para {$traveller['nome']}: fora da faixa etária permitida (18-64).";
            }
        }

        if ($addon === 'BAGAGEM') {

            $subtotal += (
                self::BAGGAGE_PRICE_PER_DAY
                * $chargedDays
            );

            $appliedAddons[] = 'BAGAGEM';
        }
    }

    return [
        'subtotal' => $subtotal,
        'applied_addons' => $appliedAddons
    ];
}
}