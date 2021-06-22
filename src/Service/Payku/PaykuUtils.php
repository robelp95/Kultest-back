<?php


namespace App\Service\Payku;


final class PaykuUtils
{
    private string $paykuPrivateToken;
    public function __construct(string $paykuPrivateToken)
    {
        $this->paykuPrivateToken = $paykuPrivateToken;
    }

    public function signRequest($requestPath, array $data){

        $request_path = urlencode($requestPath);
        ksort($data);

        $i = count($data);
        $array_concat = null;
        foreach ($data as $key => $val) {
            $array_concat .= $key . '=' . urlencode($val);
            $last_iteration = !(--$i);
            if (!$last_iteration) {
                $array_concat .= '&';
            }
        }

        $concat = $request_path.'&'.$array_concat;
        return hash_hmac('sha256', $concat, $this->paykuPrivateToken);

    }
}