<?php

use Games\Exceptions\UserException;

$lang[UserException::UserNotExist] = 'User [user] does not exist.';
$lang[UserException::NotHoldPlayer] = 'User does not hold this player.(Player ID: [player])';

$lang[UserException::UsernameAlreadyExist] = 'Username [username] is already exist.';
$lang[UserException::UsernameTooLong] = 'Username must under 16 characters.';
$lang[UserException::UsernameDirty] = 'Username dirty.';
$lang[UserException::UsernameNotEnglishOrNumber] = 'Username can only be English or number.';
$lang[UserException::CanNotResetName] = 'Can not set name.';
$lang[UserException::UserNameNotSetYet] = 'User:[user] nickname have not set yet';
$lang[UserException::AlreadyHadFreePeta] = 'User UserID:[user] Already had free peta.';

$lang[UserException::ItemNotExists] = 'ItemID:[itemID] does not in static table.';
$lang[UserException::UserNotItemOwner] = 'User does not have this userItemID:[userItemID]';
$lang[UserException::UseItemError] = 'ItemID:[itemID] use error!!';
$lang[UserException::UseRewardIDError] = 'RewardID:[rewardID] use error!!';
$lang[UserException::UserFreePlayerListEmpty] = 'User:[userID] free player list is empty.';
$lang[UserException::UserFreePlayerOverLimit] = 'User:[userID] free player count over limit.';
$lang[UserException::UserCoinNotEnough] = 'User:[userID] Coin Not Enough.';
$lang[UserException::UserPowerError] = 'User:[userID] power modify error.';
return $lang;