<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuoteTest extends TestCase
{
    public function test_can_calculate_quote_correctly()
    {
        $payload = [
            'destino' => 'AMERICAS',
            'data_inicio' => '2026-06-10',
            'data_fim' => '2026-06-15',
            'viajantes' => [
                [
                    'nome' => 'João',
                    'data_nascimento' => '1990-05-15',
                    'adicionais' => ['ESPORTES_AVENTURA', 'BAGAGEM']
                ],
                [
                    'nome' => 'Maria',
                    'data_nascimento' => '2015-08-20',
                    'adicionais' => ['BAGAGEM']
                ]
            ]
        ];

        $response = $this->postJson('/api/quotes', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'dias_cobrados',
            'viajantes' => [
                '*' => [
                    'nome',
                    'idade',
                    'subtotal',
                    'adicionais_aplicados'
                ]
            ],
            'avisos',
            'desconto_grupo_percentual',
            'total_final'
        ]);
    }
}
