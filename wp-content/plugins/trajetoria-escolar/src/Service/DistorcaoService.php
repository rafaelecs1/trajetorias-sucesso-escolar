<?php
/**
 * Unicef\TrajetoriaEscolar\Service\DistorcaoService | DistorcaoService.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Service;

use Unicef\TrajetoriaEscolar\Contract\IService;
use Unicef\TrajetoriaEscolar\Model\Distorcao;

/**
 * Disponibiliza serviços para os modelos de distorção
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Service
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IService
 */
class DistorcaoService implements IService
{
    /**
     * Distorção a utilizar os serviços
     */
    private $distorcao;

    /**
     * Construtor da classe que inicializa a distorção a utilizar os serviços
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorcao $distorcao
     * @return void
     */
    public function __construct(Distorcao $distorcao)
    {
        $this->distorcao = $distorcao;
    }

    /**
     * Valida as informações de uma distorção
     *
     * @return void
     */
    public function validate()
    {
        if (!is_int($this->distorcao->getEscola()->getId()) || $this->distorcao->getEscola()->getId() <= 0) {
            throw new \UnexpectedValueException('Informe a escola.');
        }
        if (!is_int($this->distorcao->getAnoReferencia()) || $this->distorcao->getAnoReferencia() < 1970 || $this->distorcao->getAnoReferencia() > (int)date('Y')) {
            throw new \UnexpectedValueException('Informe o ano de referência para a informação de distorção.');
        }
        if (!in_array($this->distorcao->getTipoEnsino(), array('Fundamental', 'Médio'))) {
            throw new \UnexpectedValueException('Informe o tipo de ensino, fundamental ou médio.');
        }
        if ($this->distorcao->getTipoEnsino() === 'Fundamental' && !in_array($this->distorcao->getTipoAno(), array('Iniciais', 'Finais'))) {
            echo '<pre>';
            var_dump($this->distorcao);
            die();
            throw new \UnexpectedValueException('Informe o tipo de ano para o ensino fundamental.');
        }
        if ($this->distorcao->getTipoEnsino() === 'Médio' && trim($this->distorcao->getTipoAno()) !== 'Todos') {
            throw new \UnexpectedValueException('O ensino médio não tem classificação por tipo de ano.');
        }
        if (is_a($this->distorcao, 'Unicef\TrajetoriaEscolar\Model\DistorcaoAno')) {
            if (!is_int($this->distorcao->getAno()) || $this->distorcao->getAno() < 0) {
                throw new \UnexpectedValueException('Informe o ano escolar.');
            }
            if (!is_int($this->distorcao->getSemDistorcao()) || $this->distorcao->getSemDistorcao() < 0) {
                throw new \UnexpectedValueException('Informe a quantidade sem distorção.');
            }
            if (!is_int($this->distorcao->getDistorcao1()) || $this->distorcao->getDistorcao1() < 0) {
                throw new \UnexpectedValueException('Informe a quantidade para a distorção 1 do ano.');
            }
            if (!is_int($this->distorcao->getDistorcao2()) || $this->distorcao->getDistorcao2() < 0) {
                throw new \UnexpectedValueException('Informe a quantidade para a distorção 2.');
            }
            if (!is_int($this->distorcao->getDistorcao3()) || $this->distorcao->getDistorcao3() < 0) {
                throw new \UnexpectedValueException('Informe a quantidade para a distorção 3.');
            }
        }
        if (method_exists($this->distorcao, 'getTipoDistorcao') && (!is_int($this->distorcao->getTipoDistorcao()) || $this->distorcao->getTipoDistorcao() < 0)) {
            throw new \UnexpectedValueException('Informe o tipo de distorção.');
        }
        if (is_a($this->distorcao, 'Unicef\TrajetoriaEscolar\Model\DistorcaoRaca')) {
            if (!is_int($this->distorcao->getNaoDeclarada()) || $this->distorcao->getNaoDeclarada() < 0) {
                throw new \UnexpectedValueException('Informe o valor para a distorção de cor/raça não declarada.');
            }
            if (!is_int($this->distorcao->getBranca()) || $this->distorcao->getBranca() < 0) {
                throw new \UnexpectedValueException('Informe o valor para a distorção de cor/raça branca.');
            }
            if (!is_int($this->distorcao->getPreta()) || $this->distorcao->getPreta() < 0) {
                throw new \UnexpectedValueException('Informe o valor para a distorção de cor/raça preta.');
            }
            if (!is_int($this->distorcao->getParda()) || $this->distorcao->getParda() < 0) {
                throw new \UnexpectedValueException('Informe o valor para a distorção de cor/raça parda.');
            }
            if (!is_int($this->distorcao->getAmarela()) || $this->distorcao->getAmarela() < 0) {
                throw new \UnexpectedValueException('Informe o valor para a distorção de cor/raça amarela.');
            }
            if (!is_int($this->distorcao->getIndigena()) || $this->distorcao->getIndigena() < 0) {
                throw new \UnexpectedValueException('Informe o valor para a distorção de cor/raça indígena.');
            }
        }
        if (is_a($this->distorcao, 'Unicef\TrajetoriaEscolar\Model\DistorcaoGenero')) {
            if (!is_int($this->distorcao->getMasculino()) || $this->distorcao->getMasculino() < 0) {
                throw new \UnexpectedValueException('Informe o valor da distorção para o gênero masculino.');
            }
            if (!is_int($this->distorcao->getFeminino()) || $this->distorcao->getFeminino() < 0) {
                throw new \UnexpectedValueException('Informe o valor da distorção para o gênero feminino.');
            }
        }
    }

    /**
     * Retorna o ano escolar com base na posição da coluna no arquivo CSV
     *
     * @param integer $column
     * @return int
     */
    public static function getAnoByColumn($column = 0)
    {
        $result = false;
        $column = (int)$column;
        $anos = array(
            9 => 1,
            13 => 2,
            17 => 3,
            21 => 4,
            25 => 5,
            29 => 6,
            33 => 7,
            37 => 8,
            41 => 9,
            45 => 1,
            49 => 2,
            53 => 3,
            57 => 4);
        if (array_key_exists($column, $anos)) {
            $result = $anos[$column];
        }
        $anos = null;
        return $result;
    }

    /**
     * Retorna o tipo de ano escolar com base nas informações de colunas no arquivo CSV
     *
     * @param integer $startColumn
     * @param integer $positionColumn
     * @param integer $totalColumns
     * @param mixed $options
     * @return string
     */
    public static function getTipoAnoByColumn(
        $startColumn = 0,
        $positionColumn = 0,
        $totalColumns = 0,
        $options = array()
    ) {
    
        $result = false;
        $ruleMap = array();
        $space = $totalColumns - $startColumn;
        $interval = $space / count($options);
        $i = 1;
        foreach ($options as $option) {
            $ruleMap[$startColumn + ($interval * $i)] = $option;
            $i++;
        }
        foreach ($ruleMap as $k => $v) {
            if ($positionColumn < $k) {
                $result = $v;
                break;
            }
        }
        $ruleMap = null;
        return $result;
    }

    /**
     * Retorna o tipo de distorção com base nas informações de colunas no arquivo CSV
     *
     * @param integer $startColumn
     * @param integer $positionColumn
     * @param integer $totalColumns
     * @param mixed $options
     * @param integer $amountPerGroup
     * @return int
     */
    public static function getTipoDistorcaoByColumn($startColumn = 0, $positionColumn =
        0, $totalColumns = 0, $options = array(), $amountPerGroup = 1)
    {
        $result = false;
        $ruleMap = array();
        for ($i = $startColumn; $i < $totalColumns; $i = $i + $amountPerGroup) {
            $next = array_shift($options);
            for ($j = $i; $j < ($i + $amountPerGroup); $j++) {
                $ruleMap[$j] = $next;
            }
            array_push($options, $next);
        }
        if (array_key_exists($positionColumn, $ruleMap)) {
            $result = $ruleMap[$positionColumn];
        }
        $ruleMap = null;
        return $result;
    }
}
