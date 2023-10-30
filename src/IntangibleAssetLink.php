<?php

namespace App;

class IntangibleAssetLink
{
    public function __construct(private IntangibleAsset $asset, private Borrower $borrower)
    {
    }

    public function consult()
    {
        if (!$this->isLinkValid()) {
            throw new NotFoundException();
        }
        //TODO : consult the asset / make some stuff with $this->asset
    }

    private function isLinkValid(): bool
    {
        return !$this->asset->isExpired() && $this->asset->getCurrentBorrower() == $this->borrower;
    }

    public function sendEmail(): void
    {
        $this->borrower->receiveLinkByEmail($this);
    }
}