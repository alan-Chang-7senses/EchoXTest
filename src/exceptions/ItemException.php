<?php

use Games\Exceptions\ItemException;

$lang[ItemException::ItemNotEnough] = 'This item not enough remaining.(Item ID: [item])';
$lang[ItemException::ItemNotExists] = 'ItemID:[itemID] does not in static table.';
$lang[ItemException::UserNotItemOwner] = 'User does not have this userItemID:[userItemID]';
$lang[ItemException::UseItemError] = 'ItemID:[itemID] use error!!';
$lang[ItemException::UseRewardIDError] = 'RewardID:[rewardID] use error!!';
$lang[ItemException::UserItemStacklimitReached] = 'Item stack limit reached!!';
$lang[ItemException::MailNotExist] = 'Mail does not exist or has been deleted.';
$lang[ItemException::MailRewardsReceived] = 'Received mail reward';
$lang[ItemException::AddItemError] = 'ItemID:[itemID] add error!!';
$lang[ItemException::DecItemError] = 'ItemID:[itemID] dec error!!';

return $lang;