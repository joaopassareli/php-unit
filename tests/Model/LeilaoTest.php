<?php

namespace Alura\Leilao\Tests\Model;

use DomainException;
use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(int $qtdLances, Leilao $leilao, array $valores)
    {
        static::assertCount($qtdLances, $leilao->getLances());
        foreach ($valores as $i => $valorEsperado) {
            static::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public function geraLances()
    {
        $joao = new Usuario('João');
        $bruna = new Usuario('Bruna');

        $leilaoCom2Lances = new Leilao('Fiat 147 0KM');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($bruna, 2000));

        $leilaoCom1Lance = new Leilao('Fusca 1972 0KM');
        $leilaoCom1Lance->recebeLance(new Lance($bruna, 5000));

        return [
           '2 - lances' => [2, $leilaoCom2Lances, [1000, 2000]],
            '1 - lance' => [1, $leilaoCom1Lance, [5000]]
        ];
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos ()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor dois lances consecultivos.');

        $leilao = new Leilao('Variante');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($joao, 2500));
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario ()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor mais de cinco lances por leilão.');

        $leilao = new Leilao('Brasília Amarela');

        $joao = new Usuario('João');
        $bruna = new Usuario('Bruna');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($bruna, 2500));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($bruna, 4500));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($bruna, 5500));
        $leilao->recebeLance(new Lance($joao, 6000));
        $leilao->recebeLance(new Lance($bruna, 6500));
        $leilao->recebeLance(new Lance($joao, 7000));
        $leilao->recebeLance(new Lance($bruna, 7500));

        $leilao->recebeLance(new Lance($joao, 8000));

        static::assertCount(10, $leilao->getLances());
        static::assertEquals(7500, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }
}