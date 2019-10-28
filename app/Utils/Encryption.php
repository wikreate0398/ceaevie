<?php

namespace App\Utils;

class Encryption
{  
    private $key;

    public function __construct($key = '61EhS8xGu3F8nUzxPQE6CoMs1si0XY5KTi+b8WUi6EM=') 
    { 
        $this->key = $key;
    }

    function xorEncrypt( $InputString, $KeyString )
	{
	    $KeyStringLength = mb_strlen( $KeyString );
	    $InputStringLength = mb_strlen( $InputString );
	    for ( $i = 0; $i < $InputStringLength; $i++ )
	    {
	    // Если входная строка длиннее строки-ключа
	      $rPos = $i % $KeyStringLength;
	    // Побитовый XOR ASCII-кодов символов
	      $r = ord( $InputString[$i] ) ^ ord( $KeyString[$rPos] );
	    // Записываем результат - символ, соответствующий полученному ASCII-коду
	      $InputString[$i] = chr($r);
	    }
	     return $InputString;
	}
	/**
	* Вспомогательная функция для шифрования в строку, удобную для использования в ссылках
	* @param string $InputString
	* @return string
	*/
	function encrypt( $InputString )
	{
	    $str = $this->xorEncrypt( $InputString, $this->key);
	    $str = $this->base64EncodeUrl( $str );
	    return $str;
	}
	/**
	* Вспомогательная функция для дешифрования из строки, удобной для использования в ссылках (парный к @link self::encrypt())
	* @param string $InputString
	* @return string
	*/
	function decrypt( $InputString )
	{
		$str = $this->base64DecodeUrl( $InputString );
		$str = $this->xorEncrypt( $str, $this->key);
		return $str;
	}
	/**
	* Кодирование в base64 с заменой url-несовместимых символов
	* @param string $Str
	* @return string
	*/
	function base64EncodeUrl( $Str )
	{
		return strtr( base64_encode( $Str ), '+/=', '-_,' );
	}
	/**
	* Декодирование из base64 с заменой url-несовместимых символов (парный к @link self::base64EncodeUrl())
	* @param string $Str
	* @return string
	*/
	function base64DecodeUrl( $Str )
	{
		return base64_decode( strtr( $Str, '-_,', '+/=' ) );
	}
}