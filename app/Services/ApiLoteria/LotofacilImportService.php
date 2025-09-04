<?php

namespace App\Services\ApiLoteria;

use App\Models\LotofacilConcurso;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class LotofacilImportService
{
    protected ApiLoteriaService $apiLoteria;

    public function __construct(ApiLoteriaService $apiLoteria)
    {
        $this->apiLoteria = $apiLoteria;
    }

    public function importarNovosConcursos(): int
    {
        $concursos = $this->apiLoteria->getAllLotofacilConcursos();

        if (empty($concursos)) {
            return 0;
        }

        // lista de concursos que vieram da API
        $numerosApi = collect($concursos)->pluck('concurso')->all();

        // concursos já existentes no banco
        $existentes = LotofacilConcurso::whereIn('concurso', $numerosApi)
            ->pluck('concurso')->all();

        // filtra só os novos
        $novosConcursos = collect($concursos)
            ->whereNotIn('concurso', $existentes)
            ->sortBy('concurso')
            ->all();

        $importados = 0;

        DB::beginTransaction();

        try {
            foreach ($novosConcursos as $concurso) {
                if (!isset($concurso['concurso'], $concurso['data'], $concurso['dezenas'])) {
                    continue; // pula dados incompletos
                }

                // converte a data (com fallback seguro)
                try {
                    $dataConvertida = Carbon::createFromFormat('d/m/Y', $concurso['data'])->toDateString();
                } catch (Throwable $e) {
                    continue; // ignora se a data for inválida
                }

                // padroniza dezenas
                $dezenas = is_array($concurso['dezenas'])
                    ? collect($concurso['dezenas'])
                        ->map(fn ($n) => str_pad($n, 2, '0', STR_PAD_LEFT))
                        ->sort()
                        ->values()
                        ->all()
                    : collect(explode(',', $concurso['dezenas']))
                        ->map(fn ($n) => str_pad($n, 2, '0', STR_PAD_LEFT))
                        ->sort()
                        ->values()
                        ->all();

                LotofacilConcurso::create([
                    'concurso' => $concurso['concurso'],
                    'data'     => $dataConvertida,
                    'dezenas'  => $dezenas, // array → vira JSON automaticamente
                ]);

                $importados++;
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e; // deixa o erro subir para logar/monitorar
        }

        return $importados;
    }
}
