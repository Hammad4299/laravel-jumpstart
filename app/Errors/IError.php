<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 8/16/2018
 * Time: 12:38 AM
 */

namespace App\Errors;


interface IError extends \JsonSerializable
{
    function __toString();

    /**
     * @return mixed
     */
    function getErrorData();
}