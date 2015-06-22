<?php
/**
 * Created by PhpStorm.
 * User: adamgrabek
 * Date: 19/06/15
 * Time: 00:58
 */

namespace Famillio\Domain\Person\Collection\Biography\DataExtractor;


use AGmakonts\STL\ValueObjectInterface;
use Famillio\Domain\Person\Biography\Fact\FactInterface;

/**
 * Class CallbackExtractor
 *
 * @package Famillio\Domain\Person\Collection\Biography\DataExtractor
 */
class CallbackExtractor implements DataExtractorInterface
{

    private $function;

    /**
     * CallbackExtractor constructor.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {

    }


    /**
     * @param \Famillio\Domain\Person\Biography\Fact\FactInterface $factInterface
     *
     * @return void
     */
    public function registerFact(FactInterface $factInterface)
    {
        // TODO: Implement registerFact() method.
    }

    /**
     * @return bool
     */
    public function isSatisfied() : bool
    {
        // TODO: Implement isSatisfied() method.
    }

    /**
     * @return \AGmakonts\STL\ValueObjectInterface
     */
    public function data() : ValueObjectInterface
    {
        // TODO: Implement data() method.
    }

}