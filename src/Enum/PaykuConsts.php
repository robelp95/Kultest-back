<?php


namespace App\Enum;



class PaykuConsts
{
    const TEST_PLAN = 'pruebalo 7';
    const MONTHLY_PLAN = 'mensual 7';
    const YEARLY_PLAN = 'anual 7';
    const LIFETIME_PLAN = 'apoyanos 7';
    const PAYKU_CLIENT_ENDPOINT = '/api/suclient';
    const DELETE_CLIENT_ENDPOINT = '/api/suclient/';
    const CREATE_PLAN_ENDPOINT = '/api/suplan';
    const GET_PLANS_ENDPOINT = '/api/suplan/plans';
    const PAYKU_SUSCRIPTION_ENDPOINT = '/api/sususcription';
    const API_BASE= "https://des.payku.cl";
    const PAYKU_PUBLIC_TOKEN="382de021c5b4a48fe990ae9d99c82335";
    const PAYKU_PRIVATE_TOKEN="a395de99474b40bb1ae7aec14e2325be";
}