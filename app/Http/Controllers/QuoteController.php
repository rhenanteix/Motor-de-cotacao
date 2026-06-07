<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Services\QuoteCalculatorService;

class QuoteController extends Controller
{
    public function store(
        QuoteRequest $request,
        QuoteCalculatorService $service
    ) {
       $result = $service->calculate(
            $request->validated()
        );

            Quote::create([
            'request_payload' => $request->validated(),
            'response_payload' => $result,
            'total_final' => $result['total_final'],
        ]);

        return response()->json($result);
    }

    public function index() 
    {
        return Quote::latest()->get();
    }
}