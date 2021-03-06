<?php
/**
 * Date:   11/06/15
 * Time:   14:52
 *
 */

namespace Famillio\Model\Person\Biography\Fact;

use AGmakonts\DddBricks\Entity\EntityInterface;
use AGmakonts\STL\DateTime\DateTime;
use AGmakonts\STL\Number\Integer;
use Famillio\Model\Person\Biography\Fact\Exception\CreationTimeChangeAttemptException;
use Famillio\Model\Person\Biography\Fact\Exception\DateAlreadySetException;
use Famillio\Model\Person\Biography\Fact\Exception\DateNotSetYetException;
use Famillio\Model\Person\Biography\Fact\Exception\FactIdentifierAlreadySetException;
use Famillio\Model\Person\Biography\Fact\Exception\InvalidStatusChangeAttemptException;
use Famillio\Model\Person\ValueObject\Biography\Fact\Description;
use Famillio\Model\Person\Biography\Fact\Exception\DateInFutureException;
use Famillio\Model\Person\ValueObject\Biography\Fact\Identifier;
use Famillio\Model\Person\ValueObject\Biography\Fact\Relation\FactRelationInterface;
use Famillio\Model\Person\ValueObject\Biography\Fact\Status;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Class AbstractFact
 *
 * Fact is a representation of single event in person's biography.
 * Each fact has immutable date and identifier. Apart from that, each
 * Fact can be extended by interfaces that add new data to it.
 *
 * @package Famillio\Model\Person\ValueObject\Biography\Fact
 */
abstract class AbstractFact implements FactInterface, EventManagerAwareInterface
{
    /**
     * Date of the Fact occurrence. Can, and usually be different from
     * the Fact entity creation time in technical way
     *
     * @var \AGmakonts\STL\DateTime\DateTime
     */
    private $date;

    /**
     * Personalized description of the Fact
     *
     * @var \Famillio\Model\Person\ValueObject\Biography\Fact\Description
     */
    private $description;

    /**
     * Unique identifier of the entity
     *
     * @var \Famillio\Model\Person\ValueObject\Biography\Fact\Identifier
     */
    private $identity;

    /**
     * List of all Facts that are related to the entity.
     *
     * @var \SplObjectStorage
     */
    private $relatedFacts;

    /**
     * Status or state of the Fact's life
     *
     * @var \Famillio\Model\Person\ValueObject\Biography\Fact\Status
     */
    private $status;

    /**
     * Technical timestamp of the creation of the entity
     *
     * @var \AGmakonts\STL\DateTime\DateTime
     */
    private $creationTime;

    /**
     * Timestamp of the last modification of the entity
     *
     * @var \AGmakonts\STL\DateTime\DateTime
     */
    private $updateDate;

    /**
     * Local instance of the event manager
     *
     * @var \Zend\EventManager\EventManagerInterface
     */
    private $eventManager;

    /**
     * Returns current status of the Fact
     *
     * @return \Famillio\Model\Person\ValueObject\Biography\Fact\Status
     */
    public function status() : Status
    {
        return $this->status;
    }

    /**
     * @param \Famillio\Model\Person\ValueObject\Biography\Fact\Status $status
     */
    protected function setStatus(Status $status)
    {
        $this->status = $status;
    }


    /**
     * @param \Famillio\Model\Person\ValueObject\Biography\Fact\Status $status
     *
     * @throws \Famillio\Model\Person\Biography\Fact\Exception\InvalidStatusChangeAttemptException
     */
    public function changeStatus(Status $status)
    {
        if ($status === $this->status()) {
            throw new InvalidStatusChangeAttemptException($this, $status, 'Status is the same as current one.');
        }

        if ($status->getOrdinal() < $this->status()->getOrdinal()) {
            throw new InvalidStatusChangeAttemptException($this, $status, 'Cannot change to higher status');
        }

        $this->setStatus($status);

    }

    /**
     * Return date of the last Fact update
     *
     * @return \AGmakonts\STL\DateTime\DateTime
     */
    public function lastUpdateTime() : DateTime
    {
        return $this->updateDate;
    }

    /**
     *
     */
    protected function registerUpdate()
    {
        $this->updateDate = DateTime::get();
    }

    /**
     *
     */
    protected function setCreationTime()
    {
        if (NULL !== $this->creationTime) {
            throw new CreationTimeChangeAttemptException($this);
        }

        $this->creationTime = DateTime::get();
        $this->registerUpdate();
    }

    /**
     * Return date of the Fact instance creation
     *
     * @return \AGmakonts\STL\DateTime\DateTime
     */
    public function creationTime() : DateTime
    {
        return $this->creationTime;
    }


    /**
     * @param \AGmakonts\STL\DateTime\DateTime $date
     */
    final private function setDate(DateTime $date)
    {
        if (TRUE === $date->isFurtherThan(DateTime::get()) &&
            FALSE === $date->isToday()
        ) {
            throw new DateInFutureException($date);
        }

        if (NULL === $this->date) {
            throw new DateAlreadySetException($this, $date);
        }

        $this->date = $date;
    }

    /**
     * @param \Famillio\Model\Person\ValueObject\Biography\Fact\Description $description
     */
    protected function setDescription(Description $description)
    {
        $this->description = $description;
        $this->registerUpdate();
    }

    /**
     * @param \Famillio\Model\Person\ValueObject\Biography\Fact\Identifier $identifier
     */
    final protected function setIdentity(Identifier $identifier)
    {
        if (NULL !== $this->identity) {
            throw new FactIdentifierAlreadySetException($this);
        }

        $this->setDate($identifier->date());
        $this->identity = $identifier;
    }

    /**
     * Returns date of the Fact occurrence.
     *
     * @return DateTime
     */
    public function date() : DateTime
    {
        if (NULL === $this->date) {
            throw new DateNotSetYetException($this);
        }

        return $this->date;
    }

    /**
     * Returns meaningful description of a Fact.
     *
     * @return \Famillio\Model\Person\ValueObject\Biography\Fact\Description
     */
    public function description() : Description
    {
        return $this->description;
    }


    /**
     * Changes description of a Fact to new one.
     *
     * @param \Famillio\Model\Person\ValueObject\Biography\Fact\Description $description
     */
    public function changeDescription(Description $description)
    {
        $this->setDescription($description);
    }


    /**
     * @param \AGmakonts\DddBricks\Entity\EntityInterface $entity
     *
     * @return bool
     */
    public function assertIsTheSameAs(EntityInterface $entity)
    {
        return (get_class($entity) === get_class() && $entity->identity() === $this->identity());
    }

    /**
     * @return \Famillio\Model\Person\ValueObject\Biography\Fact\Identifier
     */
    public function identity() : Identifier
    {
        return $this->identity;
    }

    /**
     * Returns interval between Fact's occurrence and specific date.
     * By default interval is calculated to current date. Optional parameter
     * allows to specify different date.
     *
     * @param \AGmakonts\STL\DateTime\DateTime $toDate
     *
     * @return mixed
     */
    public function timeSinceFact(DateTime $toDate = NULL)
    {
        // TODO: Implement timeSinceFact() method.
    }


    /**
     * Return DateTime object with the date of next anniversary of the Fact.
     *
     * @return \AGmakonts\STL\DateTime\DateTime
     */
    public function nextAnniversary() : DateTime
    {
        $nativeDate = new \DateTime($this->date()->getTimestamp()->value());
        $now        = new \DateTime();

        $interval = new \DateInterval('P1Y');

        while ($nativeDate->getTimestamp() < $now->getTimestamp()) {
            $nativeDate = $nativeDate->add($interval);
        }

        return DateTime::get(Integer::get($nativeDate->getTimestamp()));
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     *
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (NULL === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    /**
     * Relates Fact to another via FactRelation.
     *
     * @param \Famillio\Model\Person\ValueObject\Biography\Fact\Relation\FactRelationInterface $factRelationInterface
     *
     * @return void
     */
    public function relateToFact(FactRelationInterface $factRelationInterface)
    {
        if (NULL === $this->relatedFacts) {
            $this->relatedFacts = new \SplObjectStorage();
        }

        if(TRUE === $this->relatedFacts->contains($factRelationInterface)) {
            // throw
        }

        $this->relatedFacts->attach($factRelationInterface);
    }

    /**
     * Returns list of related Facts
     *
     * @return \SplObjectStorage
     */
    public function related() : \SplObjectStorage
    {
        if (NULL === $this->relatedFacts) {
            return new \SplObjectStorage();
        }
    }

    /**
     * Removed relation between Fact and another related Fact
     *
     * @param \Famillio\Model\Person\ValueObject\Biography\Fact\Relation\FactRelationInterface $factRelationInterface
     *
     * @return void
     */
    public function destroyRelationToFact(FactRelationInterface $factRelationInterface)
    {
        if (NULL === $this->relatedFacts) {
            $this->relatedFacts = new \SplObjectStorage();
        }

        if(FALSE === $this->relatedFacts->contains($factRelationInterface)) {
            //throw
        }

        $this->relatedFacts->detach($factRelationInterface);
    }
}