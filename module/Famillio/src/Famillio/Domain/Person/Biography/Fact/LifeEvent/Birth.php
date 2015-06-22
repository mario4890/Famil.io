<?php
/**
 * Date:   11/06/15
 * Time:   15:03
 *
 */

namespace Famillio\Domain\Person\Biography\Fact\LifeEvent;


use AGmakonts\STL\DateTime\DateTime;
use AGmakonts\STL\String\Text;
use AGmakonts\STL\Structure\KeyValuePair;
use Famillio\Domain\Person\Biography\Fact\AbstractFact;
use Famillio\Domain\Person\Biography\Fact\FactInterface;
use Famillio\Domain\Person\Biography\Fact\FamilyNameChangeFactInterface;
use Famillio\Domain\Person\Biography\Fact\FussyFactInterface;
use Famillio\Domain\Person\Biography\Fact\GenderChangeFactInterface;
use Famillio\Domain\Person\Biography\Fact\GivenNameChangeFactInterface;
use Famillio\Domain\Person\Biography\Fact\LifespanBoundaryFactInterface;
use Famillio\Domain\Person\Biography\Fact\Validator\ValidatorInterface;
use Famillio\Domain\Person\ValueObject\Biography\Fact\Description;
use Famillio\Domain\Person\ValueObject\Biography\Fact\Identifier;
use Famillio\Domain\Person\ValueObject\Biography\Fact\LifespanBoundaryType;
use Famillio\Domain\Person\ValueObject\Biography\Fact\Status;
use Famillio\Domain\Person\ValueObject\Biography\Fact\Story;
use Famillio\Domain\Person\ValueObject\Gender;
use Famillio\Domain\Person\ValueObject\Name\FamilyName;
use Famillio\Domain\Person\ValueObject\Name\GivenName;

/**
 * Class Birth
 *
 * @package Famillio\Domain\Person\ValueObject\Biography\Fact\LifeEvent
 */
class Birth extends AbstractFact implements LifespanBoundaryFactInterface,
    GivenNameChangeFactInterface,
    FamilyNameChangeFactInterface,
    GenderChangeFactInterface,
    FussyFactInterface
{
    /**
     * @var \AGmakonts\STL\String\Text
     */
    private $place;

    /**
     * @var \Famillio\Domain\Person\Collection\Biography\DataExtractor\Name\GivenName
     */
    private $givenName;

    /**
     * @var \Famillio\Domain\Person\Collection\Biography\DataExtractor\Name\FamilyName
     */
    private $familyName;

    /**
     * @var \Famillio\Domain\Person\ValueObject\Gender
     */
    private $gender;

    /**
     * Birth constructor.
     *
     * @param \Famillio\Domain\Person\ValueObject\Biography\Fact\Identifier  $identifier
     * @param \Famillio\Domain\Person\ValueObject\Biography\Fact\Description $description
     * @param \AGmakonts\STL\String\Text                                     $place
     * @param \Famillio\Domain\Person\ValueObject\Name\GivenName             $givenName
     * @param \Famillio\Domain\Person\ValueObject\Name\FamilyName            $familyName
     * @param \Famillio\Domain\Person\ValueObject\Gender                     $gender
     */
    public function __construct(Identifier $identifier,
                                Description $description,
                                Text $place,
                                GivenName $givenName,
                                FamilyName $familyName,
                                Gender $gender)
    {
        $this->place      = $place;
        $this->givenName  = $givenName;
        $this->familyName = $familyName;
        $this->gender     = $gender;

        $this->setIdentity($identifier);
        $this->setDescription($description);
        $this->setStatus(Status::get(Status::CURRENT));
    }


    /**
     * @return \AGmakonts\STL\String\Text
     */
    public function type() : Text
    {
        return Text::get('Birth');
    }

    /**
     * @return FamilyName
     */
    public function familyName() : FamilyName
    {
        return $this->familyName;
    }

    /**
     * @return GivenName
     */
    public function givenName() : GivenName
    {
        return $this->givenName;
    }

    /**
     * @return Gender
     */
    public function gender() : Gender
    {
        return $this->gender;
    }

    public function place() : Text
    {
        return $this->place;
    }


    /**
     * @return LifespanBoundaryType
     */
    public function lifespanBoundaryType() : LifespanBoundaryType
    {
        return LifespanBoundaryType::get(LifespanBoundaryType::BEGINNING);
    }

    /**
     * @param \Famillio\Domain\Person\ValueObject\Biography\Fact\Story $customStory
     *
     * @return \Famillio\Domain\Person\ValueObject\Biography\Fact\Story
     */
    public function story(Story $customStory = NULL) : Story
    {
        $past    = Text::get('was born at {DATE} in {LOCATION} ({PLACE})');
        $present = Text::get('is borning in {LOCATION} ({PLACE})');
        $future  = Text::get('will be born ar {DATE} in {LOCATION} ({PLACE})');

        $data = [
            KeyValuePair::get(Text::get('{DATE}'), $this->date()),
            KeyValuePair::get(Text::get('{LOCATION}'), $this->location()),
            KeyValuePair::get(Text::get('{PLACE}'), $this->place()),
        ];

        return Story::get($past, $present, $future, $data);
    }

    /**
     * Due to immutable nature of the Facts, change of the date requires
     * building new instance.
     *
     * @param \AGmakonts\STL\DateTime\DateTime $dateTime
     *
     * @return \Famillio\Domain\Person\Biography\Fact\FactInterface
     */
    public function changedDate(DateTime $dateTime) : FactInterface
    {
        $identifier = Identifier::generate($dateTime);

        return new static(
            $identifier,
            $this->description(),
            $this->place(),
            $this->givenName(),
            $this->familyName(),
            $this->gender()
        );
    }

    /**
     * Returns instance of Validator that is setup in a way that can determine
     * if Facts that will be existing in the same collection are not conflicting.
     *
     * @return \Famillio\Domain\Person\Biography\Fact\Validator\ValidatorInterface
     */
    public function validator() : ValidatorInterface
    {
        // TODO: Implement validator() method.
    }


}