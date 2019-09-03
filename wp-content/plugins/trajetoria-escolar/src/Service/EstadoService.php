<?php
/**
 * Unicef\TrajetoriaEscolar\Service\EstadoService | EstadoService.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Service;

use \Unicef\TrajetoriaEscolar\Contract\IService;
use \Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Disponibiliza serviços para estados
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Service
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IService
 */
class EstadoService implements IService
{
    /**
     * Estado a utilizar os serviços
     */
    private $estado;

    /**
     * Construtor da classe que inicializa o estado a utilizar os serviços
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @return void
     */
    public function __construct(Estado $estado)
    {
        $this->estado = $estado;
    }

    /**
     * Valida as informações de um estado
     *
     * @return void
     */
    public function validate()
    {
        if (!is_int($this->estado->getId()) || $this->estado->getId() <= 0) {
            throw new \UnexpectedValueException('Informe o código do estado!');
        }
        if (trim($this->estado->getNome()) === '') {
            throw new \UnexpectedValueException('Informe o nome do estado!');
        }
        if (trim($this->estado->getLimites()) === '') {
            throw new \UnexpectedValueException('Informe os limites do estado!');
        }
        if (trim($this->estado->getRegiao()) === '') {
            throw new \UnexpectedValueException('Informe a região para o estado!');
        }
    }

    /**
     * Configura o código IBGE para um estado
     *
     * @return void
     */
    public function setCode()
    {
        $codigos = array(
            //Norte
            11 => 'Rondônia',
            12 => 'Acre',
            13 => 'Amazonas',
            14 => 'Roraima',
            15 => 'Pará',
            16 => 'Amapá',
            17 => 'Tocantins',
            //Nordeste
            21 => 'Maranhão',
            22 => 'Piauí',
            23 => 'Ceará',
            24 => 'Rio Grande do Norte',
            25 => 'Paraíba',
            26 => 'Pernambuco',
            27 => 'Alagoas',
            28 => 'Sergipe',
            29 => 'Bahia',
            //Sudeste
            31 => 'Minas Gerais',
            32 => 'Espírito Santo',
            33 => 'Rio de Janeiro',
            35 => 'São Paulo',
            //Sul
            41 => 'Paraná',
            42 => 'Santa Catarina',
            43 => 'Rio Grande do Sul',
            //Centro-Oeste
            50 => 'Mato Grosso do Sul',
            51 => 'Mato Grosso',
            52 => 'Goiás',
            53 => 'Distrito Federal',
            );
        $codigo = array_search($this->estado->getNome(), $codigos);
        $this->estado->setId((int)$codigo);
    }

    /**
     * Configura os limites de um estado através de coordenadas geográficas
     *
     * @return void
     */
    public function setBounds()
    {
        $limites = array(
            11 => array(
                'n' => -7.97,
                's' => -13.69,
                'l' => -59.77,
                'o' => -66.81,
                ),
            12 => array(
                'n' => -7.11,
                's' => -11.15,
                'l' => -66.62,
                'o' => -73.99,
                ),
            13 => array(
                'n' => 2.25,
                's' => -9.82,
                'l' => -56.10,
                'o' => -73.80,
                ),
            14 => array(
                'n' => 5.27,
                's' => -1.58,
                'l' => -58.89,
                'o' => -64.83,
                ),
            15 => array(
                'n' => 2.59,
                's' => -9.84,
                'l' => -46.06,
                'o' => -58.90,
                ),
            16 => array(
                'n' => 4.44,
                's' => -1.24,
                'l' => -49.88,
                'o' => -54.88,
                ),
            17 => array(
                'n' => -5.17,
                's' => -13.47,
                'l' => -45.70,
                'o' => -50.74,
                ),
            21 => array(
                'n' => -1.04,
                's' => -10.26,
                'l' => -41.80,
                'o' => -48.76,
                ),
            22 => array(
                'n' => -2.74,
                's' => -10.93,
                'l' => -40.37,
                'o' => -45.99,
                ),
            23 => array(
                'n' => -2.78,
                's' => -7.86,
                'l' => -37.25,
                'o' => -41.42,
                ),
            24 => array(
                'n' => -4.83,
                's' => -6.98,
                'l' => -34.97,
                'o' => -38.58,
                ),
            25 => array(
                'n' => -6.03,
                's' => -8.30,
                'l' => -34.79,
                'o' => -38.77,
                ),
            26 => array(
                'n' => -3.83,
                's' => -9.48,
                'l' => -32.39,
                'o' => -41.36,
                ),
            27 => array(
                'n' => -8.81,
                's' => -10.50,
                'l' => -35.15,
                'o' => -38.24,
                ),
            28 => array(
                'n' => -9.52,
                's' => -11.57,
                'l' => -36.39,
                'o' => -38.24,
                ),
            29 => array(
                'n' => -8.53,
                's' => -18.35,
                'l' => -37.34,
                'o' => -46.62,
                ),
            31 => array(
                'n' => -14.23,
                's' => -22.92,
                'l' => -39.86,
                'o' => -51.05,
                ),
            32 => array(
                'n' => -17.33,
                's' => -21.82,
                'l' => -37.92,
                'o' => -43.62,
                ),
            33 => array(
                'n' => -20.76,
                's' => -23.37,
                'l' => -40.96,
                'o' => -44.89,
                ),
            35 => array(
                'n' => -19.78,
                's' => -25.31,
                'l' => -44.16,
                'o' => -53.11,
                ),
            41 => array(
                'n' => -22.52,
                's' => -26.72,
                'l' => -48.02,
                'o' => -54.62,
                ),
            42 => array(
                'n' => -25.96,
                's' => -29.35,
                'l' => -48.33,
                'o' => -53.84,
                ),
            43 => array(
                'n' => -27.08,
                's' => -33.75,
                'l' => -49.69,
                'o' => -57.64,
                ),
            50 => array(
                'n' => -17.17,
                's' => -24.07,
                'l' => -50.92,
                'o' => -58.17,
                ),
            51 => array(
                'n' => -7.35,
                's' => -18.04,
                'l' => -50.22,
                'o' => -61.63,
                ),
            52 => array(
                'n' => -12.40,
                's' => -19.50,
                'l' => -45.91,
                'o' => -53.25,
                ),
            53 => array(
                'n' => -15.50,
                's' => -16.05,
                'l' => -47.31,
                'o' => -48.29,
                ),
            );

        $estadoId = $this->estado->getId();
        if (array_key_exists($estadoId, $limites)) {
            $serializePrecision = ini_get('serialize_precision');
            ini_set('serialize_precision', -1);
            
            $limites = json_encode($limites[$estadoId]);
            $this->estado->setLimites($limites);
            
            ini_set('serialize_precision', $serializePrecision);
        }
    }
}
