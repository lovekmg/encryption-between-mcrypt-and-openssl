<?php
define('KEY', '01234567890123456789012345678901');
define('KEY_256', substr(KEY, 0, 256 / 8));
define('KEY_128', substr(KEY, 0, 128 / 8));


class AesOpenssl
{
	public static function enc($cipher, $str, $key, $iv)
	{
		return base64_encode(openssl_encrypt($str, $cipher, $key, 1, $iv));
	}

	public static function dec($cipher, $encStr, $key, $iv)
	{
		return openssl_decrypt(base64_decode($encStr), $cipher, $key, 1, $iv);
	}
}

class AesMcrypt 
{
	public static function padding($str)
	{
		$padding = 16 - (strlen($str) % 16);
		$paddingText = str_repeat(chr($padding), $padding);
		return $str.$paddingText;
	}

	public static function unPadding($str)
	{
		$len = strlen($str);
		$padding = ord($str[$len - 1]);
		return substr($str, 0, $len - $padding);
	}

	public static function enc($cipher, $str, $key, $iv)
	{
		return base64_encode(mcrypt_encrypt(
			MCRYPT_RIJNDAEL_128, 
			$key, 
			AesMcrypt::padding($str), 
			$cipher,
			$iv
		));
	}

	public static function dec($cipher, $encStr, $key, $iv)
	{
		$dec = mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128, 
			$key, 
			base64_decode($encStr), 
			$cipher,
			$iv
		);
		return AesMcrypt::unPadding($dec);
		//return $dec;
	}
}



$str = 'hello aes256 encrypt test!!';
$cipher = 'AES-256-CBC';
$mCipher = MCRYPT_MODE_CBC;

/*
$encStr = AesOpenssl::enc($cipher, $str, KEY_256, KEY_128);
$decStr = AesOpenssl::dec($cipher, $encStr, KEY_256, KEY_128);
//*/

/*
$cipher = MCRYPT_MODE_CBC;
$encStr = AesMcrypt::enc($mCipher, $str, KEY_256, KEY_128);
$decStr = AesMcrypt::dec($mCipher, $encStr, KEY_256, KEY_128);
//*/

/*
$encStr = AesOpenssl::enc($cipher, $str, KEY_256, KEY_128);
$decStr = AesMcrypt::dec($mCipher, $encStr, KEY_256, KEY_128);
//*/

///*
$encStr = AesMcrypt::enc($mCipher, $str, KEY_256, KEY_128);
$decStr = AesOpenssl::dec($cipher, $encStr, KEY_256, KEY_128);
//*/

var_dump($encStr, $decStr);
