<?php
/**
 * Created by PhpStorm.
 * User: Hope
 * Date: 06.08.2018
 * Time: 21:59
 */

namespace LifecellApi\Models;


class SubscriberInfo extends Model
{
    protected $token;
    protected $subId;

    public function getToken()
    {
        return $this->token;
    }

    public function getSubId()
    {
        return $this->subId;
    }
}