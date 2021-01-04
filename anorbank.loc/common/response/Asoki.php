<?php

namespace common\response;

/**
 * Class Asoki
 * @package usanjar\belt\response
 *
 * @property-read string|null $errorMessage
 * @property-read int $code
 */
class Asoki extends BaseResponse
{
    public function __construct(array $params)
    {
        if (isset($params['data']) && is_array($params['data'])) {
            $data = $params['data'];
            unset($params['data']);
            $this->attributes = array_merge($data, $params);
        } else {
            $this->attributes = $params;
        }
    }
}
