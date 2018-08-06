<?php
/**
 * Created by PhpStorm.
 * User: Hope
 * Date: 06.08.2018
 * Time: 22:25
 */

namespace LifecellApi\Models;


class SummaryData extends Model
{
    protected $msisdn;


    // + subscriber index
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}