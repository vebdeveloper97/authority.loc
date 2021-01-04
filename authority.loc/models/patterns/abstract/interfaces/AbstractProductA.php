<?php


namespace app\models\patterns\interfaces;


interface AbstractProductA{

    public function usefulFunctionA(): string;

    public function anotherUsefulFunctionA(AbstractProductA $collaborator): string;

}