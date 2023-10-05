<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Class\CalcularDiasUteis;
use DateTime;

class CalculoDiasUteisTest extends TestCase
{
    public function test_calcular_dias_uteis()
    {
        $dataInicio = new DateTime('2023-10-01'); // Um domingo
        $resultado = new CalcularDiasUteis($dataInicio);
        $this->assertEquals(0, $resultado);
    }
}
