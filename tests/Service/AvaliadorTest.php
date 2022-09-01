<?php

namespace Alura\Leilao\tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Service\Avaliador;


class AvaliadorTest extends TestCase
{
    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testeAvaliadorDeveEncontrarMaiorValorDeLances (Leilao $leilao)
    {         
        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);
        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert
        self::assertEquals(3000, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testeAvaliadorDeveEncontrarMenorValorDeLances (Leilao $leilao)
    {
        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);
        $menorValor = $leiloeiro->getMenorValor();

        // Assert - Then
        self::assertEquals(2000, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveBuscarTresMaioresValores (Leilao $leilao)
    {
        $leiloeiro = new Avaliador();

        // Act
        $leiloeiro->avalia($leilao);
        $maioresLances = $leiloeiro->getMaioresLances();

        // Assert
        static::assertCount(3, $maioresLances);
        static::assertEquals(3000, $maioresLances[0]->getValor());
        static::assertEquals(2500, $maioresLances[1]->getValor());
        static::assertEquals(2000, $maioresLances[2]->getValor());
    }

    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Ford Ka 2020 0KM');

        $joao = new Usuario('Joao');
        $bruna = new Usuario('Bruna');
        $marisa = new Usuario('Marisa');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($marisa, 2500));
        $leilao->recebeLance(new Lance($bruna, 3000));

        return [[$leilao]];
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Ford Ka 2020 0KM');

        $joao = new Usuario('Joao');
        $bruna = new Usuario('Bruna');
        $marisa = new Usuario('Marisa');

        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($marisa, 2500));
        $leilao->recebeLance(new Lance($bruna, 2000));

        return [[$leilao]];
    }

    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Ford Ka 2020 0KM');

        $joao = new Usuario('Joao');
        $bruna = new Usuario('Bruna');
        $marisa = new Usuario('Marisa');

        
        $leilao->recebeLance(new Lance($marisa, 2500));
        $leilao->recebeLance(new Lance($bruna, 2000));
        $leilao->recebeLance(new Lance($joao, 3000));

        return [[$leilao]];
    }
}