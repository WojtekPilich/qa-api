<?php


namespace App\Storage;

class QuestionsRequestStorage
{
    private $data;

    public function __construct($scope)
    {
        $this->data = $scope;
    }

    public function getData()
    {
        return $this->data;
    }
}