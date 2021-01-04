<?php

namespace app\models\abstarct\interfaces;


use app\models\patterns\interfaces\AbstractFactory;
use app\models\patterns\interfaces\AbstractProductB;

class ConcreteFactory1 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {

    }

    public function createProductB(): AbstractProductB
    {

    }
}