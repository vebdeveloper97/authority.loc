<?php


namespace app\models\abstarct\interfaces;
use app\models\patterns\interfaces\AbstractProductA;

class ConcreteProductA1 implements AbstractProductA
{
    public function usefulFunction(): string
    {
        return "The result of the product A1.";
    }

    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "The result of the B1 collaborating with the ({$result})";
    }
}