<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SequenceNumber;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SequenceNumberConstants
{
    /**
     * @var string
     */
    public const ENVIRONMENT_PREFIX = 'environmentPrefix';

    /**
     * Specification:
     * - A list of limits per sequence name
     * - If limit is not set, the sequence is unlimited
     *
     * @example
     * [
     *  'SEQUENCE1' => 100,
     *  'SEQUENCE2' => 200,
     * ]
     *
     * @var string
     */
    public const LIMIT_LIST = 'SEQUENCE_NUMBER:LIMIT_LIST';
}
