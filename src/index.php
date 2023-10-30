<?php

@require_once "../vendor/autoload.php";

use App\Borrower;
use App\intangibleAsset;
use App\PhysicalAsset;

/**
 * NEED :
 * Borrower
 * Borrowing - MAX 5 (Max 2 to 4 weeks physical)
 * Link
 *
 * asset/good/thing (physical or )
 */

$now = new DateTimeImmutable();
$nowCallback = function () {
    global $now;
    return $now;
};

$user1 = new Borrower("Eliott", "eliott.jaquier@gmail.com");
$user2 = new Borrower("Mikael", "cosalpino@gmail.com");

$physicalAsset1 = new PhysicalAsset($nowCallback);
$physicalAsset2 = new PhysicalAsset($nowCallback);
$physicalAsset3 = new PhysicalAsset($nowCallback);
$physicalAsset4 = new PhysicalAsset($nowCallback);
$physicalAsset5 = new PhysicalAsset($nowCallback);

//assert $physicalAsset1->borrowDuration

$intangibleAsset = new IntangibleAsset($nowCallback);
$intangibleAsset1 = new IntangibleAsset($nowCallback);
$intangibleAsset2 = new IntangibleAsset($nowCallback);

//assert $intangibleAsset2->borrowDuration

$user1->borrow($physicalAsset1);

//Assert $user1 borrow items list (count or first item)

$user2->borrow($physicalAsset1);
//Impossible, already borrowed

$user1->borrow($physicalAsset2);
$user1->borrow($physicalAsset3);
$user1->borrow($physicalAsset4);

//Ce lien est directement envoyé par mail à la personne. new Link();
$user1->borrow($intangibleAsset);

//This throw error (MAX LIMIT REACHED)
$user1->borrow($physicalAsset5);
//This is impossible
$user1->sendBack($physicalAsset5);

$user1->sendBack($physicalAsset2);
$user1->borrow($physicalAsset5);
//Assert this can now work

//ERROR impossible borrows
$time->addDays(14);

//Asert YES (IDEA : Liste d'umprunts ?)
$intangibleAsset->isExpired();
//Assert NO
$physicalAsset1->isExpired();

$time->addDays(14);

//Assert YES
$physicalAsset1->isExpired();