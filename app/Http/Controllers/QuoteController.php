<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Services\QuoteCalculatorService;
use App\Services\QuotePersistenceService;
use App\Models\Quote;

class QuoteController extends Controller
{
    public function store(
        QuoteRequest $request,
        QuoteCalculatorService $service,
        QuotePersistenceService $persistence
    ) {
       $result = $service->calculate(
            $request->validated()
        );

        $persistence->save(
            $request->validated(),
            $result
        );

        return response()->json($result);
    }

 public function index()
{
    return response()->json(
        Quote::query()
            ->latest()
            ->get()
            ->map(function ($quote) {

                return [
                    'id' => $quote->id,

                    'created_at' =>
                        $quote->created_at,

                    'total_final' =>
                        $quote->total_final,

                    'viajantes' =>
                        count(
                            $quote->request_payload['viajantes']
                        ),

                    'destino' =>
                        $quote->request_payload['destino'],
                ];
            })
    );
}

        public function show(Quote $quote)
        {
            return response()->json([
                'id' => $quote->id,
                'request_payload' => $quote->request_payload,
                'response_payload' => $quote->response_payload,
                'total_final' => $quote->total_final,
                'created_at' => $quote->created_at,
            ]);
        }
}