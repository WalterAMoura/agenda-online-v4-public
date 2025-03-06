<?php

namespace App\Utils;

class General
{
    /**
     * Método responsável por validar se o conteúdo passado é nulo, vazio ou contém somente espaços
     * @param mixed $str
     * @return bool
     */
    public static function isNullOrEmpty(mixed $str):bool
    {
        if (!isset($str) || trim($str) === '') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método responsável por validar se é um array
     * @param $arr
     * @return bool
     */
    public static function isArray($arr) : bool
    {
        if(!is_array($arr) && count($arr) > 0){
            return true;
        }
        return false;
    }

    /**
     * Método responsável por validar se a string não é numérica
     * @param string $str
     * @return bool
     */
    public static function isNotNumeric(string $str): bool
    {
        return !is_numeric($str);
    }
}