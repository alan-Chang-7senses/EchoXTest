<?php

namespace Consts;

/**
 * Description of EnvVar
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class EnvVar {
    
    const SysEnv = 'SYSENV';
    const Maintain = 'MAINTAIN';
    
    const DBLabelMain = 'DBLABEL_MAIN';
    const DBLabelStatic = 'DBLABEL_STATIC';
    const DBLabelLog = 'DBLABEL_LOG';
    const DBLabelEliteTest = 'DBLABEL_ELITE_TEST';
    
    const DBHost = 'DB_HOST';
    const DBPort = 'DB_PORT';
    const DBUsername = 'DB_USERNAME';
    const DBPassword = 'DB_PASSWORD';
    const DBName = 'DB_NAME';
    
    const MemcahcedHost = 'MEMCACHED_HOST';
    const MemcachedPort = 'MEMCACHED_PORT';
}
