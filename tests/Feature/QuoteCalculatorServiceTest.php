<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\QuoteCalculatorService;

class QuoteCalculatorServiceTest extends TestCase
{
    private QuoteCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new QuoteCalculatorService();
    }

    /** @test */
    public function it_applies_minimum_charge_of_five_days()
    {
        $result = $this->service->calculate([
            'destino' => 'NACIONAL',
            'data_inicio' => '2026-07-10',
            'data_fim' => '2026-07-10',
            'viajantes' => [
                [
                    'nome' => 'Ana',
                    'data_nascimento' => '1990-01-01',
                    'adicionais' => []
                ]
            ]
        ]);

        $this->assertEquals(
            5,
            $result['dias_cobrados']
        );
    }

    /** @test */
    public function it_calculates_age_based_on_trip_start_date()
    {
        $result = $this->service->calculate([
            'destino' => 'NACIONAL',
            'data_inicio' => '2026-07-10',
            'data_fim' => '2026-07-15',
            'viajantes' => [
                [
                    'nome' => 'Pedro',
                    'data_nascimento' => '2008-07-15',
                    'adicionais' => []
                ]
            ]
        ]);

        $this->assertEquals(
            17,
            $result['viajantes'][0]['idade']
        );
    }

    /** @test */
    public function it_adds_warning_when_adventure_sports_is_not_allowed()
    {
        $result = $this->service->calculate([
            'destino' => 'EUROPA',
            'data_inicio' => '2026-07-10',
            'data_fim' => '2026-07-20',
            'viajantes' => [
                [
                    'nome' => 'João',
                    'data_nascimento' => '1948-11-02',
                    'adicionais' => [
                        'ESPORTES_AVENTURA'
                    ]
                ]
            ]
        ]);

        $this->assertCount(
            1,
            $result['avisos']
        );

        $this->assertStringContainsString(
            'ESPORTES_AVENTURA',
            $result['avisos'][0]
        );
    }

    /** @test */
    public function it_applies_group_discount_for_five_or_more_travellers()
    {
        $travellers = [];

        for ($i = 1; $i <= 5; $i++) {
            $travellers[] = [
                'nome' => "Pessoa {$i}",
                'data_nascimento' => '1990-01-01',
                'adicionais' => []
            ];
        }

        $result = $this->service->calculate([
            'destino' => 'NACIONAL',
            'data_inicio' => '2026-07-10',
            'data_fim' => '2026-07-20',
            'viajantes' => $travellers
        ]);

        $this->assertEquals(
            10,
            $result['desconto_grupo_percentual']
        );
    }

    /** @test */
    public function it_calculates_complete_quote_correctly()
    {
        $result = $this->service->calculate([
            'destino' => 'EUROPA',
            'data_inicio' => '2026-07-10',
            'data_fim' => '2026-07-20',
            'viajantes' => [
                [
                    'nome' => 'Ana',
                    'data_nascimento' => '1990-03-15',
                    'adicionais' => [
                        'BAGAGEM',
                        'ESPORTES_AVENTURA'
                    ]
                ],
                [
                    'nome' => 'João',
                    'data_nascimento' => '1948-11-02',
                    'adicionais' => [
                        'BAGAGEM',
                        'ESPORTES_AVENTURA'
                    ]
                ]
            ]
        ]);

        $this->assertEquals(
            11,
            $result['dias_cobrados']
        );

        $this->assertEquals(
            852.50,
            $result['total_final']
        );

        $this->assertCount(
            1,
            $result['avisos']
        );

        $this->assertEquals(
            'Ana',
            $result['viajantes'][0]['nome']
        );

        $this->assertEquals(
            'João',
            $result['viajantes'][1]['nome']
        );
    }
}