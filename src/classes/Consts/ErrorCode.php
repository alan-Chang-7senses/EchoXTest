<?php

namespace Consts;

/**
 * Description of ErrorCode
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class ErrorCode {
    
    const Success = 0;
    const ConfigError = 1;
    const SystemError = 3;
    const SQLError = 4;
    const ParamError = 26;
    const VerifyError = 27;
    const ProcessFailure = 28;
    const Maintain = 503;
    const Unknown = 999;
    const PDODuplicate = 23000;
}
