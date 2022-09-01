<?php

namespace Alura\Leilao\tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Service\Avaliador;


class AvaliadorTest extends TestCase
{
    public function testeAvaliadorDeveEncontrarMaiorValorDeLances ()
    {
        // 
        $leilao = new Leilao('Ford Ka 2020 0KM');

        $joao = new Usuario('Joao');
        $bruna = new Usuario('Bruna');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($bruna, 3000));

        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);
        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert
        self::assertEquals(3000, $maiorValor);
    }

    public function testeAvaliadorDeveEncontrarMenorValorDeLances ()
    {
        // Arrange - Given
        $leilao = new Leilao('Ford Ka 2020 0KM');

        $joao = new Usuario('Joao');
        $bruna = new Usuario('Bruna');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($bruna, 3000));

        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);
        $menorValor = $leiloeiro->getMenorValor();

        // Assert - Then
        self::assertEquals(2000, $menorValor);
    }
}