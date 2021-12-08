<?php

use Exceptions\LoginException;

$lang[LoginException::SignOut] = 'User has been sign out';
$lang[LoginException::FormatError] = 'Account or Password format error';
$lang[LoginException::NoAccount] = 'Account does not exist';
$lang[LoginException::DisabledAccount] = 'Account is unavailable';
$lang[LoginException::PasswordError] = 'Password is wrong';

return $lang;