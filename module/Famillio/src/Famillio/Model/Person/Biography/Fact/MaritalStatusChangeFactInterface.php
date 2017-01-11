<?php
/**
 * Created by PhpStorm.
 * User: mariuszbocz
 * Date: 19/06/15
 * Time: 16:12
 */

namespace Famillio\Model\Person\Biography\Fact;

use Famillio\Model\Person\ValueObject\Name;

/**
 * Interface MaritalStatusChangeFactInterface
 *
 * @package Famillio\Model\Person\Biography\Fact
 */
interface MaritalStatusChangeFactInterface
{
    /**
     * @return mixed
     */
    public function getName() : Name;
}