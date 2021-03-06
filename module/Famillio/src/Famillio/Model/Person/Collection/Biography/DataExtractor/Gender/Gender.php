<?php
/**
 * Created by PhpStorm.
 * User: adamgrabek
 * Date: 20/06/15
 * Time: 21:08
 */

namespace Famillio\Model\Person\Collection\Biography\DataExtractor\Gender;


use AGmakonts\STL\ValueObjectInterface;
use Famillio\Model\Person\Biography\Fact\FactInterface;
use Famillio\Model\Person\Biography\Fact\GenderChangeFactInterface;
use Famillio\Model\Person\Collection\Biography\DataExtractor\DataExtractorInterface;
use Famillio\Model\Person\Collection\Biography\DataExtractor\Exception\NotSatisfiedExtractorException;

/**
 * Class Gender
 *
 * @package Famillio\Model\Person\Collection\Biography\DataExtractor\Gender
 */
class Gender implements DataExtractorInterface
{
    /**
     * @var \Famillio\Model\Person\ValueObject\Gender
     */
    private $gender;

    /**
     * Add Fact for extraction. Internal logic of the method will decide witch Facts
     * to use and witch ones to discard.
     *
     * @param \Famillio\Model\Person\Biography\Fact\FactInterface $factInterface
     *
     * @return void
     */
    public function registerFact(FactInterface $factInterface)
    {
        if($factInterface instanceof GenderChangeFactInterface) {
            $this->gender = $factInterface->gender();
        }
    }

    /**
     * Return boolean value that describes if Extractor had already registered all
     * Facts that are needed to extract data.
     *
     * @return bool
     */
    public function isSatisfied() : bool
    {
        return (NULL !== $this->gender);
    }

    /**
     * @return \AGmakonts\STL\ValueObjectInterface
     */
    public function data() : ValueObjectInterface
    {
        if(FALSE === $this->isSatisfied()) {
            throw new NotSatisfiedExtractorException();
        }

        return $this->gender;
    }

}