<?php
/**
 * Created by PhpStorm.
 * User: Hope
 * Date: 06.08.2018
 * Time: 21:57
 */

namespace LifecellApi;

use LifecellApi\Client\BaseClient;
use LifecellApi\Models\SubscriberInfo;

class Client extends BaseClient
{
    /**
     * @param $msisdn
     * @param $superPassword
     * @return SubscriberInfo
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Auth($msisdn, $superPassword)
    {
        $method = 'signIn';

        $options = [
            'msisdn' => $msisdn,
            'superPassword' => $superPassword,
        ];

        $data = $this->request($method, $options);

        return new SubscriberInfo($data);
    }
}