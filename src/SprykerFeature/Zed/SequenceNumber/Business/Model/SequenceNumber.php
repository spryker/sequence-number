<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business\Model;

use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;
use SprykerFeature\Zed\SequenceNumber\Persistence\Propel\SpySequenceNumber;
use SprykerFeature\Zed\SequenceNumber\Persistence\Propel\SpySequenceNumberQuery;

class SequenceNumber implements SequenceNumberInterface
{

    /** @var RandomNumberGenerator */
    protected $randomNumberGenerator;

    /** @var string */
    protected $name;

    /** @var int */
    protected $minimumNumber;

    /** @var int */
    protected $numberLength;

    /**
     * @param RandomNumberGeneratorInterface $randomNumberGenerator
     * @param string $name
     * @param int $minimumNumber
     * @param int $numberLength
     */
    public function __construct(RandomNumberGeneratorInterface $randomNumberGenerator, $name, $minimumNumber, $numberLength)
    {
        $this->randomNumberGenerator = $randomNumberGenerator;
        $this->name = $name;
        $this->minimumNumber = $minimumNumber;
        $this->numberLength = $numberLength;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $idCurrent = 0;

        while ($idCurrent < 1) {
            $idCurrent = $this->createNumber();
        }

        if ($this->numberLength > 0) {
            return sprintf('%1$0' . $this->numberLength . 'd', $idCurrent);
        }
        return sprintf('%s', $idCurrent);
    }

    /**
     * @return int
     */
    protected function createNumber()
    {
        $idCurrent = 0;
        $transaction = Propel::getConnection();

        try {
            $transaction->beginTransaction();

            $sequence = $this->getSequence($transaction);
            $idCurrent = $sequence->getCurrentId() + $this->randomNumberGenerator->generate();

            $sequence->setCurrentId($idCurrent);
            $sequence->save($transaction);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            $idCurrent = 0;
        }

        return $idCurrent;
    }

    /**
     * @param ConnectionInterface $transaction
     *
     * @return SpySequenceNumber
     */
    protected function getSequence($transaction)
    {
        $sequence = SpySequenceNumberQuery::create()
            ->findOneByName($this->name, $transaction);

        if ($sequence === null) {
            $sequence = new SpySequenceNumber();
            $sequence->setName($this->name);
            $sequence->setCurrentId($this->minimumNumber);
        }

        return $sequence;
    }

}
