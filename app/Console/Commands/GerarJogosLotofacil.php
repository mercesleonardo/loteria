<?php

namespace App\Console\Commands;

use App\Services\ApiLoteria\JogoAleatorioService;
use Illuminate\Console\Command;

class GerarJogosLotofacil extends Command
{
    protected $signature = 'lotofacil:gerar-jogos {quantidade=5}';

    protected $description = 'Gera jogos aleatórios da Lotofácil que não existem no banco de dados';

    public function handle()
    {
        $qtd     = (int) $this->argument('quantidade');
        $gerador = app(JogoAleatorioService::class);
        $jogos   = $gerador->gerarJogosUnicos($qtd);

        foreach ($jogos as $jogo) {
            $this->info("Jogo gerado: {$jogo}");
        }

        return Command::SUCCESS;
    }
}
