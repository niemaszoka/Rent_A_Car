<?php

namespace CarRental\Infrastructure\Payment;
class UrlcCheck
{
    const STATUS_OK = 'OK';
    const STATUS_FAIL = 'FAIL';
    const OPERATION_STATUS_COMPLETED = 'completed';
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    private function validateSignature()
    {
        $string = implode(
            '',
            [
                'f5M7pZJKzVfbMT9AnFFdzaVq1dqP1HUg',
                $this->paramValue('id'),
                $this->paramValue('operation_number'),
                $this->paramValue('operation_type'),
                $this->paramValue('operation_status'),
                $this->paramValue('operation_amount'),
                $this->paramValue('operation_currency'),
                $this->paramValue('operation_original_amount'),
                $this->paramValue('operation_original_currency'),
                $this->paramValue('operation_datetime'),
                $this->paramValue('control'),
                $this->paramValue('description'),
                $this->paramValue('email'),
                $this->paramValue('p_info'),
                $this->paramValue('p_email'),
                $this->paramValue('channel'),
            ]
        );
        if ($this->paramValue('operation_status') !== self::OPERATION_STATUS_COMPLETED) {
            return self::STATUS_FAIL;
        }
        if (hash('sha256', $string) !== $this->paramValue('signature') ) {
            return self::STATUS_FAIL;
        }
        return self::STATUS_OK;
    }

    public function isSuccessful()
    {
        return $this->validateSignature() === self::STATUS_OK ? true : false;
    }
    private function paramValue($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : '';
    }
}