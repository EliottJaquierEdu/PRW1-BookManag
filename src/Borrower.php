<?php

namespace App;

class Borrower
{
    private $currentBorrowingList;
    public function __construct(private $name, private $email)
    {
        $currentBorrowingList = [];
    }

    public function borrow($asset)
    {
        if(in_array($this->currentBorrowingList,$asset)) throw new BorrowerException("");
        array_push($this->currentBorrowingList,$asset);
    }

    public function sendBack($asset)
    {
    }
}