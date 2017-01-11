<?php
/**
 * Date:   11/06/15
 * Time:   15:03
 *
 */

namespace Famillio\Model\Person\Biography\Fact\LifeEvent;


use AGmakonts\STL\DateTime\DateTime;
use AGmakonts\STL\String\Text;
use AGmakonts\STL\Structure\KeyValuePair;
use Famillio\Model\Person\Biography\Fact\AbstractFact;
use Famillio\Model\Person\Biography\Fact\FactInterface;
use Famillio\Model\Person\ValueObject\Name\Name;

/**
 * Class Wedding
 *
 * @package Famillio\Model\Person\ValueObject\Biography\Fact\LifeEvent
 */
class Wedding extends AbstractFact implements Famillio\Model\Person\Biography\Fact\MaritalStatusChangeFactInterface
{
 
    /**
     * @var \AGmakonts\STL\String\Text
     */
    private $name;
    
     public function __construct(
                                Name $name
                                )
    {
        $this->name         = $name;
    }
    
    /**
     * @return \AGmakonts\STL\String\Text
     */
    public function type() : Text
    {
        return Text::get('Wedding');
    }

    public function getName() : Name
    {
        return $this->name;
    }
}