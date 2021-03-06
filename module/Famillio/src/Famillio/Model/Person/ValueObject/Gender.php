<?php
/**
 * Created by PhpStorm.
 * User: adamgrabek
 * Date: 12/06/15
 * Time: 12:28
 */

namespace Famillio\Model\Person\ValueObject;


use AGmakonts\STL\Structure\AbstractEnum;

/**
 * Class Gender
 *
 * @package Famillio\Model\Person\ValueObject
 */
class Gender extends AbstractEnum
{
    const MALE   = 'male';
    const FEMALE = 'female';
    const OTHER  = 'other';
}