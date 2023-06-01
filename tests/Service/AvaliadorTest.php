<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    protected $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        self::assertEquals(5000, $maiorValor);
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio');
        $leilao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMenorValor();

        self::assertEquals(3000, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOs3MarioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->getMaioresLances();

        self::assertCount(3, $maioresLances);
        self::assertEquals(5000, $maioresLances[0]->getValor());
        self::assertEquals(4500, $maioresLances[1]->getValor());
        self::assertEquals(4000, $maioresLances[2]->getValor());
    }

    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $weslley = new Usuario('Weslley');
        $bianca = new Usuario('Bianca');
        $lenna = new Usuario('Lenna');
        $wellyson = new Usuario('Wellyson');

        $leilao->recebeLance(new Lance($weslley, 3000));
        $leilao->recebeLance(new Lance($bianca, 4000));
        $leilao->recebeLance(new Lance($lenna, 4500));
        $leilao->recebeLance(new Lance($wellyson, 5000));

        return [
            'ordem-crescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $weslley = new Usuario('Weslley');
        $bianca = new Usuario('Bianca');
        $lenna = new Usuario('Lenna');
        $wellyson = new Usuario('Wellyson');

        $leilao->recebeLance(new Lance($wellyson, 5000));
        $leilao->recebeLance(new Lance($lenna, 4500));
        $leilao->recebeLance(new Lance($bianca, 4000));
        $leilao->recebeLance(new Lance($weslley, 3000));

        return [
            'ordem-decrescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $weslley = new Usuario('Weslley');
        $bianca = new Usuario('Bianca');
        $lenna = new Usuario('Lenna');
        $wellyson = new Usuario('Wellyson');

        $leilao->recebeLance(new Lance($wellyson, 5000));
        $leilao->recebeLance(new Lance($bianca, 4000));
        $leilao->recebeLance(new Lance($weslley, 3000));
        $leilao->recebeLance(new Lance($lenna, 4500));

        return [
            'ordem-aleatoria' => [$leilao]
        ];
    }

    public static function tearDownAfterClass(): void
    {
        var_dump('Fim dos testes');
    }
}
