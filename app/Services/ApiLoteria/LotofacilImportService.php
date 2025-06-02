<?php

namespace App\Services\ApiLoteria;

use App\Models\LotofacilConcurso;
use Carbon\Carbon;

class LotofacilImportService
{
    protected ApiLoteriaService $apiLoteria;

    public function __construct(ApiLoteriaService $apiLoteria)
    {
        $this->apiLoteria = $apiLoteria;
    }

    public function importarNovosConcursos()
    {
        $concursos = $this->apiLoteria->getAllLotofacilConcursos();

        if (empty($concursos)) {
            return 0;
        }

        $numerosApi = collect($concursos)->pluck('concurso')->all();

        $existentes = LotofacilConcurso::whereIn('concurso', $numerosApi)
            ->pluck('concurso')->all();

        $novosConcursos = collect($concursos)
            ->whereNotIn('concurso', $existentes)
            ->sortBy('concurso')
            ->all();

        foreach ($novosConcursos as $concurso) {
            if (!isset($concurso['concurso'], $concurso['data'], $concurso['dezenas'])) {
                continue; // pula dados incompletos
            }

            $dataConvertida = Carbon::createFromFormat('d/m/Y', $concurso['data'])->toDateString();

            LotofacilConcurso::create(
                [
                    'concurso' => $concurso['concurso'],
                    'data'     => $dataConvertida,
                    'dezenas'  => $concurso['dezenas'],
                ]
            );

        }

        return count($novosConcursos);
    }
}
