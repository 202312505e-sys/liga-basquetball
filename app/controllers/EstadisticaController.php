<?php
class EstadisticaController
{
    private Estadistica $modelo;

    public function __construct()
    {
        $this->modelo = new Estadistica();
    }

    public function posiciones(): void
    {
        vista('estadisticas/posiciones', [
            'titulo'    => 'Tabla de posiciones',
            'posiciones' => $this->modelo->tablaPosiciones(),
        ]);
    }

    public function anotadores(): void
    {
        vista('estadisticas/anotadores', [
            'titulo'     => 'Máximos anotadores',
            'anotadores' => $this->modelo->maximosAnotadores(10),
        ]);
    }
}