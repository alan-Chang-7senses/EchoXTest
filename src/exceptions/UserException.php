<?php

use Games\Exceptions\UserException;

$lang[UserException::UserNotExist] = 'User [user] does not exist.';
$lang[UserException::NotHoldPlayer] = 'User does not hold this player.(Player ID: [player])';
$lang[UserException::ItemNotEnough] = 'This item not enough remaining.(Item ID: [item])';

$lang[UserException::UsernameAlreadyExist] = 'Username [username] is already exist.';
$lang[UserException::UsernameTooLong] = 'Username must under 16 characters.';
$lang[UserException::UsernameDirty] = 'Username [username] has banded word.';
$lang[UserException::UsernameNotEnglishOrNumber] = 'Username can only be English or number.';
$lang[UserException::CanNotResetName] = 'Can not set name.';
$lang[UserException::UserNameNotSetYet] = 'User:[user] nickname have not set yet';
$lang[UserException::AlreadyHadFreePeta] = 'User UserID:[user] Already had free peta.';

$lang[UserException::ItemNotExists] = 'ItemID:[itemID] does not in static table.';
$lang[UserException::UserNotItemOwner] = 'User does not have this userItemID:[userItemID]';
$lang[UserException::UseItemError] = 'ItemID:[itemID] use error!!';
return $lang;