<?php

namespace App\Console\Commands;

use App\Services\ApiLoteria\LotofacilImportService;
use Illuminate\Console\Command;

class Playground extends Command
{
    protected $signature = 'lotofacil:importar';

    protected $description = 'Importa concursos da Lotofácil da API e salva no banco de dados';

    protected LotofacilImportService $importar;

    public function __construct(LotofacilImportService $importar)
    {
        parent::__construct();
        $this->importar = $importar;
    }

    public function handle()
    {
        $novos = $this->importar->importarNovosConcursos();

        if ($novos === 0) {
            $this->info('Nenhum novo concurso para importar.');
        } else {
            $this->info("Importação concluída com sucesso. Novos concursos salvos: $novos");
        }

        return Command::SUCCESS;
    }
}
