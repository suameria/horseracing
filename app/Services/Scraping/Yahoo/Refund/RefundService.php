<?php

namespace App\Services\Scraping\Yahoo\Refund;

class RefundService implements RefundServiceInterface
{
    public function __construct()
    {

    }

    public function getRefund($crawler, $raceKey)
    {
        $data = $crawler->filter('.resultYen tr')->each(function($element) use($raceKey){

            return [
                'order_of_finish' => trim($element->filter('td')->eq(0)->text()),
                'price'           => numberUnFormat(removeYen(trim($element->filter('td')->eq(1)->text()))),
                'favorite'        => (int)getNumber(trim($element->filter('td')->eq(2)->text())),
                'type'            => (int)$this->getRefundType($element),
                'race_key'        => $raceKey,
            ];
        });

        if (isset($data[1]['type']) && $data[1]['type'] === 2) {
            $data[2]['type'] = 2;
            $data[3]['type'] = 2;
        }

        if (isset($data[6]['type']) && $data[6]['type'] === 5) {
            $data[7]['type'] = 5;
            $data[8]['type'] = 5;
        }

        return $data;
    }

    private function getRefundType($element)
    {
        $type = null;
        switch (true) {
            case preg_match('/'.config('yahoo.refund.1').'/', $element->html()):
                $type = 1;
                break;
            case preg_match('/'.config('yahoo.refund.2').'/', $element->html()):
                $type = 2;
                break;
            case preg_match('/'.config('yahoo.refund.3').'/', $element->html()):
                $type = 3;
                break;
            case preg_match('/'.config('yahoo.refund.4').'/', $element->html()):
                $type = 4;
                break;
            case preg_match('/'.config('yahoo.refund.5').'/', $element->html()):
                $type = 5;

                break;
            case preg_match('/'.config('yahoo.refund.6').'/', $element->html()):
                $type = 6;
                break;
            case preg_match('/'.config('yahoo.refund.7').'/', $element->html()):
                $type = 7;
                break;
            case preg_match('/'.config('yahoo.refund.8').'/', $element->html()):
                $type = 8;
                break;
        }

        return $type;
    }
}
