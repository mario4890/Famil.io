<?php
/**
 * Created by PhpStorm.
 * User: adamgrabek
 * Date: 18/06/15
 * Time: 18:18
 */

namespace Famillio\Model\Person\Collection\Preconditions\Biography\Replacement;

/**
 * Class Replacement
 *
 * @package Famillio\Model\Person\Collection\Preconditions\Biography\Replacement
 */
class Replacement extends Removal
{
    /**
     * @return bool
     */
    public function isMeet() : bool
    {
        $removalPreconditionMeet = $this->areBaseConditionsMeet();

        $newFactNotNull = (NULL !== $this->newFact());
        $datesMatch = ($this->newFact()->date() === $this->oldFact()->date());
        $typesMatch = ($this->newFact()->type() === $this->oldFact()->type());

        return ($removalPreconditionMeet && $newFactNotNull && $datesMatch && $typesMatch);
    }
}