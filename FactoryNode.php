<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 1/31/16
 * Time: 12:26 PM
 */
  namespace Modeling;

class FactoryNode
{
    private $speed; // производительность узла в деталях/час

    private $cost; // стоимость часа эксплуатации в тыс. руб
    private $deviation; // отклонение для вероятности от 0 до 1
    private $comment; // описываем единицу оборудования

    private $TotalCost; //общая сумма затрат на выпуск
    private $TotalNumber; //Количество сделанных деталей
    private $Number; //количество деталей поступивших на изготовление

    public function __construct() {
        $this->setComment('empty');
        $this->setSpeed(100);
        $this->setCost(1);
        $this->setDeviation(0.1);
    }

    public function setComment(string $value)
    {
        $this->comment = $value;
    }

    public function setCost(float $value)
    {
        $this->cost = $value;
    }

    public function setTotalCost(float $value = 0)
    {
        $this->TotalCost = $value;
    }

    public function setSpeed(int $value)
    {
        $this->speed = $value;
    }

    public function setNumber(int $value = 0)
    {
        $this->Number = $value;
    }

    public function setTotalNumber(int $value = 0)
    {
        $this->TotalNumber = $value;
    }

    public function setDeviation(float $value)
    {
        $this->deviation = $value;
    }

    /*
     *
     */
    public function InitNode(string $comment, float $cost, int $speed, float $devation)
    {
        $this->setComment($comment);
        $this->setSpeed($speed);
        $this->setCost($cost);
        $this->setDeviation($devation);

        $this->setTotalCost();
        $this->setNumber();
        $this->setTotalNumber();
    }

    public function getStartState()
    {
         $str = 'Узел :'.$this->comment.'r\n';
         $str .= 'Себестоимость :'.$this->cost.' тыс. рублей r\n';
         $str .= 'Скорость :'.$this->speed.' деталей/час r\n';
         $str .= 'Отклонение :'.$this->deviation.' в % r\n';

         return $str;
    }

    public function getCurrentState()
    {
        $str = 'Узел : '.$this->comment.'r\n';
        $str .= 'Себестоимость на данный момент: '.$this->getTotalCost().' тыс. рублей r\n';
        $str .= 'Деталей в очереди : '.$this->Number.' r\n';
        $str .= 'Сделано : '.$this->totalNumber.' r\n';

        return $str;
    }

    public function getTotalCost()
    {
        $this->totalCost = $this->totalNumber * $this->cost;
        return $this->totalCost;
    }

    public function getStatus()
    {
        if($this->Number > 0)
            return true;

        return false;
    }

    public function getSpeed()
    {
        return $this->speed;
    }

    public function setQueue(int $param)
    {
        if($param > $this->speed)
        {
            $param -= $this->speed;
            $this->Number += $this->speed;
        }
        else
        {
            $this->Number += $param;
            $param = 0;
        }

        return $param;
    }

    public function setManufacturing()
    {
        if( $this->Number == 0) return;
        if( $this->Number >= $this->speed)
        {
            $this->TotalNumber += $this->speed;
            $this->Number -= $this->speed;

        }

        if( $this->Number < $this->speed)
        {
            $this->TotalNumber += $this->Number;
            $this->Number = 0;
        }

    }
}