<?php

namespace App\Services\ApiLoteria;

use App\Models\LotofacilConcurso;

class VerificaJogoService
{
    public function verificaJogoExistente(array $jogo): bool
    {
        // normaliza dezenas recebidas
        $jogoNormalizado = collect($jogo)
            ->map(fn ($n) => str_pad($n, 2, '0', STR_PAD_LEFT))
            ->sort()
            ->values()
            ->all();

        // busca todos os jogos existentes (arrays)
        $jogosExistentes = LotofacilConcurso::pluck('dezenas')->toArray();

        // compara arrays (transforma em strings JSON para comparar)
        return in_array(
            json_encode($jogoNormalizado),
            array_map('json_encode', $jogosExistentes),
            true
        );
    }
}
