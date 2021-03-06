<?php
/**
 * Created by PhpStorm.
 * User: adamgrabek
 * Date: 21/06/15
 * Time: 23:32
 */

namespace Famillio\Model\Person\Biography\Fact\Exception;


use Famillio\Model\Person\Biography\Fact\FactInterface;

/**
 * Class CreationTimeChangeAttemptException
 *
 * @package Famillio\Model\Person\Biography\Fact\Exception
 */
class CreationTimeChangeAttemptException extends AbstractFactException
{
    const MESSAGE_FORMAT = 'Creation time of the Fact identified by %s cannot be updated';

    /**
     * @param \Famillio\Model\Person\Biography\Fact\FactInterface $fact
     */
    public function __construct(FactInterface $fact)
    {
        parent::__construct($fact);

        $this->message = sprintf(self::MESSAGE_FORMAT, $this->fact()->identity()->value());
    }

}