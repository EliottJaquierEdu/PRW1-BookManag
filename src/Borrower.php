<?php

namespace App;

class Borrower
{
    private $currentBorrowingList;
    private $receivedLinks;

    public function __construct(private $name, private $email)
    {
        $this->currentBorrowingList = [];
        $this->receivedLinks = [];
    }

    public function getReceivedLinks()
    {
        return $this->receivedLinks;
    }

    public function borrow(Asset $asset)
    {
        $notExpired = $this->getNotExpiredBorrowedItems();
        if (in_array($asset, $notExpired)) {
            throw new BorrowerException("You cannot borrow this item, you already have it.");
        }
        if (count($notExpired) >= 5) {
            throw new BorrowerException("You cannot borrow this item because you already have 5 other items.");
        }
        $asset->onBorrowBy($this);
        array_push($this->currentBorrowingList, $asset);
    }

    public function getNotExpiredBorrowedItems()
    {
        return array_filter($this->currentBorrowingList, function ($item) {
            return !$item->isExpired($this);
        });
    }

    public function sendBack(PhysicalAsset $asset)
    {
        if (!in_array($asset, $this->currentBorrowingList)) {
            throw new BorrowerException("You cannot send back this item, you don't have it.");
        }
        $asset->onSendBackBy($this);
        array_splice($this->currentBorrowingList, array_search($asset, $this->currentBorrowingList), 1);
    }

    public function receiveLinkByEmail(IntangibleAssetLink $link)
    {
        array_push($this->receivedLinks, $link);
    }
}