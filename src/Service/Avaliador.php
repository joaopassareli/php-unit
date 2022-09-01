<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;

class Avaliador
{
    private $maiorValor = 0;
    private $menorValor = INF;
    private $maioresLances;

    public function avalia (Leilao $leilao):void
    {        
        foreach($leilao->getLances() as $lance){
            if ($lance->getValor() > $this->maiorValor)
            {
                $this->maiorValor = $lance->getValor();
            }

            if($lance->getValor() < $this->menorValor) {
                $this->menorValor = $lance->getValor();
            }
        }

        $leilao - $leilao->getLances();
        \usort($lances, function(Lance $lance1, Lance $lance2){
            return $lance1->getValor() - $lance2->getValor();
        });

        // com o metodo array_slice, é selecionado o array (lances) e dele, será iniciado do primeiro valor(0) e retornado 
        // os três primeiros valores (3). Ou seja, será retornado os três maiores lances em ordem decrescente.
        $this->maioresLances = array_slice($lances, 0, 3);
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMenorValor(): float
    {
        return $this->menorValor;
    }

    /**
     * @return Lance[]
     */
    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }
}

