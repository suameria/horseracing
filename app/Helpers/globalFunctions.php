<?php

function getNumber($string)
{
    return preg_replace('/[^0-9]/', '', $string);
}

function getPlaceNumber($string)
{
    return substr($string, 2, 2);
}

function getRaceNumber($string)
{
    return substr($string, 8, 2);
}

function getRaceKeyWithoutRaceNo($string)
{
    return substr($string, 0, 8);
}

function getLessThanLeftValue($string)
{
    $explode = explode('<', $string);
    return $explode[0];
}

function removeBrackets($string)
{
    return str_replace('(', '', str_replace(')', '', $string));
}

function removeYen($string)
{
    return str_replace('円', '', $string);
}

function numberUnFormat($number, $force_number = true, $dec_point = '.', $thousands_sep = ',')
{
    if ($force_number) {
        $number = preg_replace('/^[^\d]+/', '', $number);
    } else if (preg_match('/^[^\d]+/', $number)) {
        return false;
    }
    $type   = (strpos($number, $dec_point) === false) ? 'int' : 'float';
    $number = str_replace(array($dec_point, $thousands_sep), array('.', ''), $number);
    settype($number, $type);
    return $number;
}

function arrayFilterMerge($data)
{
    return array_merge(array_filter($data));
}

function convertStrTimeToSecond($stringTime)
{
    // 1:33.7
    $explode = explode('.', $stringTime);
    return (int)($explode[0] * 60) + (string)($explode[1]).(string)('.'.$explode[2]);
}