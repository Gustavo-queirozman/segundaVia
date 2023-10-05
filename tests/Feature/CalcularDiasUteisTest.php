<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Class\CalcularDiasUteis;
use DateTime;

class CalcularDiasUteisTest extends TestCase
{
    public function testCalcularDiasUteisComFeriadoEFinalDeSemana(){
        $dataInicial = new DateTime('2023-10-1');
        $dataFinal = new DateTime('12-10-2023');
        $resultado = new CalcularDiasUteis($dataInicial, $dataFinal);
        $this->assertEquals(11, $resultado->diasUteis);
    }
    public function testCalcularDiasUteisSemFeriado(){
        $dataInicial = new DateTime('2023-10-1');
        $dataFinal = new DateTime('10-10-2023');
        $resultado = new CalcularDiasUteis($dataInicial, $dataFinal);
        $this->assertEquals(10, $resultado->diasUteis);
    }
    public function testCalcularDiasUteisComFinalDeSemana(){
        $dataInicial = new DateTime('2023-10-1');
        $dataFinal = new DateTime('10-10-2023');
        $resultado = new CalcularDiasUteis($dataInicial, $dataFinal);
        $this->assertEquals(10, $resultado->diasUteis);
    }
    public function testCalcularDiasUteisComDatasInvalidas(){
        $dataInicial = new DateTime('2023-10-11111');
        $dataFinal = new DateTime('10-10-202311'); //1 feriado
        $resultado = new CalcularDiasUteis($dataInicial, $dataFinal);
        $this->assertEquals(10, $resultado->diasUteis);
    }

}
