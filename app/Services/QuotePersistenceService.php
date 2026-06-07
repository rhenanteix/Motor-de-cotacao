<?php

namespace App\Services;

use App\Models\Quote;

class QuotePersistenceService
{
    public function save(
        array $requestData,
        array $responseData
    ): Quote {

        return Quote::create([
            'request_payload' => $requestData,
            'response_payload' => $responseData,
            'total_final' => $responseData['total_final'],
        ]);
    }
}