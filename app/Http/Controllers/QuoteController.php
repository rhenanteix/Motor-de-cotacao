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
        return response()->json(
            $service->calculate(
                $request->validated()
            )
        );
    }
}