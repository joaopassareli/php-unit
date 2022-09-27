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
     * @var Avaliador
     */
    private $leiloeiro;
    
    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testeAvaliadorDeveEncontrarMaiorValorDeLances (Leilao $leilao)
    {
        // Act - When
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Assert - Then
        self::assertEquals(3000, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testeAvaliadorDeveEncontrarMenorValorDeLances (Leilao $leilao)
    {
        // Act - When
        $this->leiloeiro->avalia($leilao);
        $menorValor = $this->leiloeiro->getMenorValor();

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
        // Act
        $this->leiloeiro->avalia($leilao);
        $maioresLances = $this->leiloeiro->getMaioresLances();

        // Assert
        static::assertCount(3, $maioresLances);
        static::assertEquals(3000, $maioresLances[0]->getValor());
        static::assertEquals(2500, $maioresLances[1]->getValor());
        static::assertEquals(2000, $maioresLances[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado ()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar um leilão que não possui lances.');
        
        $leilao = new Leilao('Gol G4');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado ()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado!');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }


    /* -------- DADOS -------- */
    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Ford Ka 2020 0KM');

        $joao = new Usuario('Joao');
        $bruna = new Usuario('Bruna');
        $marisa = new Usuario('Marisa');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($marisa, 2500));
        $leilao->recebeLance(new Lance($bruna, 3000));

        return [
            'ordem-crescente' => [$leilao]];
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

        return [
            'ordem-decrescente' => [$leilao]
        ];
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

        return [
            'ordem-aleatoria' => [$leilao]
        ];
    }
}