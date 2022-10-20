<?php

namespace Consts;

/**
 * Description of EnvVar
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class EnvVar {
    
    const AppVersion = 'APP_VERSION';
    
    const SysEnv = 'SYSENV';
    const Maintain = 'MAINTAIN';
    const ProcessTiming = 'PROCESS_TIMING';
    const TimezoneDefault = 'TIMEZONE_DEFAULT';
    
    const DBMain = 'Main';
    const DBStatic = 'Static';
    const DBLog = 'Log';
    const DBEliteTest = 'EliteTest';
    
    const DBHost = 'Host';
    const DBPort = 'Port';
    const DBUsername = 'Username';
    const DBPassword = 'Password';
    const DBName = 'Name';
    
    const DBs = [
        self::DBMain => [
            self::DBHost => 'DB_MAIN_HOST',
            self::DBPort => 'DB_MAIN_PORT',
            self::DBUsername => 'DB_MAIN_USERNAME',
            self::DBPassword => 'DB_MAIN_PASSWORD',
            self::DBName => 'DB_MAIN_NAME',
        ],
        self::DBStatic => [
            self::DBHost => 'DB_STATIC_HOST',
            self::DBPort => 'DB_STATIC_PORT',
            self::DBUsername => 'DB_STATIC_USERNAME',
            self::DBPassword => 'DB_STATIC_PASSWORD',
            self::DBName => 'DB_STATIC_NAME',
        ],
        self::DBLog => [
            self::DBHost => 'DB_LOG_HOST',
            self::DBPort => 'DB_LOG_PORT',
            self::DBUsername => 'DB_LOG_USERNAME',
            self::DBPassword => 'DB_LOG_PASSWORD',
            self::DBName => 'DB_LOG_NAME',
        ],
        self::DBEliteTest => [
            self::DBHost => 'DB_ELITE_HOST',
            self::DBPort => 'DB_ELITE_PORT',
            self::DBUsername => 'DB_ELITE_USERNAME',
            self::DBPassword => 'DB_ELITE_PASSWORD',
            self::DBName => 'DB_ELITE_NAME',
        ],
    ];
    
    const MemcahcedHost = 'MEMCACHED_HOST';
    const MemcachedPort = 'MEMCACHED_PORT';
    
    const SSOUri = 'SSO_URI';
    const SSOClientID = 'SSO_CLIEND_ID';
    const SSOClientSecret = 'SSO_CLIEND_SECRET';
    const APPUri = 'APP_URI';
    
    const NFTUri = 'NFT_URI';
    const NFTClientID = 'NFT_CLIENTID';
    const NFTAPISecret = 'NFT_APISECRET';
}
