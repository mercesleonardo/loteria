<?php

namespace App\Services\ApiLoteria;

use Illuminate\Support\Facades\Http;

class ApiLoteriaService
{
    protected string $baseUrl = 'https://loteriascaixa-api.herokuapp.com/api';

    /**
     * Retorna o concurso mais recente da Lotofácil
     *
     * @return array|null
     */
    public function getLatestLotofacil(): ?array
    {
        $response = Http::acceptJson()->get("{$this->baseUrl}/lotofacil/latest");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    /**
     * Retorna todos os concursos da Lotofácil
     */
    public function getAllLotofacilConcursos(): ?array
    {
        $response = Http::acceptJson()->get("{$this->baseUrl}/lotofacil");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
