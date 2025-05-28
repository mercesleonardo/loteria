<?php

namespace App\Services\ApiLoteria;

use App\Models\LotofacilConcurso;

class JogoAleatorioService
{
    public function gerarJogosUnicos(int $quantidade = 1): array
    {
        $existentes = LotofacilConcurso::pluck('dezenas')->toArray();
        $existentes = array_map(function ($dezenas) {
            if (is_array($dezenas)) {
                $numeros = $dezenas;
            } else {
                $numeros = explode(',', $dezenas);
            }
            sort($numeros);
            return implode(',', $numeros);
        }, $existentes);

        $jogosGerados = [];

        while (count($jogosGerados) < $quantidade) {
            $jogo = $this->gerarJogoAleatorio();

            $chave = implode(',', $jogo);
            if (!in_array($chave, $existentes) && !in_array($chave, $jogosGerados)) {
                $jogosGerados[] = $chave;
            }
        }

        return $jogosGerados;

    }

    private function gerarJogoAleatorio(): array
    {
        $numeros = range(1, 25);
        shuffle($numeros);
        $jogo = array_slice($numeros, 0, 15);
        sort($jogo);

         // Formata com dois dígitos (01, 02, ..., 25)
        return array_map(function ($numero) {
            return str_pad($numero, 2, '0', STR_PAD_LEFT);
        }, $jogo);
    }
}
