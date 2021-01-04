<?php

namespace app\models\patterns\interfaces;

interface AbstractFactory{

    public function createProductA(): AbstractProductA;

    public function createProductB(): AbstractProductB;
    
}