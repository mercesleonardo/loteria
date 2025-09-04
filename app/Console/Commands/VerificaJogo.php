<?php

namespace App\Console\Commands;

use App\Services\ApiLoteria\VerificaJogoService;
use Illuminate\Console\Command;

class VerificaJogo extends Command
{
    protected $signature = 'lotofacil:verifica-jogo';

    protected $description = 'Verifica se o jogo já existe no banco de dados';

    protected VerificaJogoService $verificaJogoService;

    public function __construct(VerificaJogoService $verificaJogoService)
    {
        parent::__construct();
        $this->verificaJogoService = $verificaJogoService;
    }

    public function handle()
    {
        $jogo = $this->ask('Informe os 15 números do jogo (ex: 1 2 3 4 5 ...)');

        // padroniza o input → "01,02,03,..."
        $dezenas = collect(explode(' ', $jogo))
            ->map(fn ($n) => str_pad($n, 2, '0', STR_PAD_LEFT))
            ->sort()
            ->values()
            ->all();

        if ($this->verificaJogoService->verificaJogoExistente($dezenas)) {
            $this->info('✅ O jogo já existe no banco de dados.');
        } else {
            $this->warn('❌ O jogo não existe no banco de dados.');
        }
    }
}
