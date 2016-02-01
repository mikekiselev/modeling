<?php

/**
 * Created by PhpStorm.
 * User: oem
 * Date: 1/31/16
 * Time: 12:36 PM
 */
 namespace Modeling;


 include "FactoryNode.php";

class Modeling
{
    private $Nodes; // узлы с оборудованием
    private $Number; // количество деталей
    private $hours; //количество часов на производство изделия
    private $TotalNumber; //Количество сделанных деталей
    private $TotalCost; //общая сумма затрат на выпуск

    public function __construct()
    {

    }

    /**
     *
     */
    public function Init()
    {
        $this->Number = 550;

        $this->hours = 0;
        for($i=0;$i<5;$i++)
        {
            $param = rand(0, 1);
            $this->Nodes[] = ( new FactoryNode())->InitNode('Node '.$i, 30*$param,  10*$param, 0.05*$param);
        }

        /*
         * Параметры перед стартом задаем
         */
        $this->TotalNumber = 0;
        $this->hours = 0;
        $this->TotalCost = 0;
    }

    public function Start()
    {
        echo 'Start...r\n';
        echo 'Параметры оборудования: r\n';
        foreach($this->Nodes as $item)
        {
            echo $item->getStartState();
            echo 'r\n';
        }


        do
        {
            $this->setQueue();


            $this->hours++;

            $this->setTotalCost();
            $this->GetCurrentState();
        }
        while($this->getStatus());


    }

    /*
     * движение заготовок по узлам
     */
    private function setQueue()
    {
        foreach($this->Nodes as $id => $item)
        {
            /*
             * если что-то есть на входе производства
             */
            if($id == 0 && $this->Number >0)
            {
                $this->Number = $item->setQueue($this->Number);
            }


        }
    }

    private function getStatus()
    {
        if($this->Number >0)
            return true;

        foreach($this->Nodes as $item)
        {
            if($item->getStatus())
                return true;
        }
        return false;
    }

    private function setTotalCost()
    {

        $this->TotalCost = 0;

        foreach($this->Nodes as $item)
        {
            $this->TotalCost += $item->getTotalCost();
        }

    }

    private function GetCurrentState()
    {
        foreach($this->Nodes as $item)
        {
            echo $item->getCurrentState();
        }
    }

}