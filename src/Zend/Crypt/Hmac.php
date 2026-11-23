<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Crypt
 * @subpackage Hmac
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * PHP implementation of the RFC 2104 Hash based Message Authentication Code
 * algorithm.
 *
 * @todo  Patch for refactoring failed tests (key block sizes >80 using internal algo)
 * @category   Zend
 * @package    Zend_Crypt
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Crypt_Hmac extends Zend_Crypt
{

    /**
     * The key to use for the hash
     *
     * @var string
     */
    protected static $_key = null;

    /**
     * pack() format to be used for current hashing method
     *
     * @var string
     */
    protected static $_packFormat = null;

    /**
     * Hashing algorithm; can be the md5/sha1 functions or any algorithm name
     * listed in the output of PHP 5.1.2+ hash_algos().
     *
     * @var string
     */
    protected static $_hashAlgorithm = 'md5';

    /**
     * Constants representing the output mode of the hash algorithm
     */
    const STRING = 'string';
    const BINARY = 'binary';

    /**
     * Performs a HMAC computation given relevant details such as Key, Hashing
     * algorithm, the data to compute MAC of, and an output format of String,
     * Binary notation or BTWOC.
     *
     * @param string $key
     * @param string $hash
     * @param string $data
     * @param string $output
     * @throws Zend_Crypt_Hmac_Exception
     * @return string|false
     */
    public static function compute($key, $hash, $data, $output = self::STRING)
    {
        // set the key
        if (!isset($key) || empty($key)) {
            throw new Zend_Crypt_Hmac_Exception('provided key is null or empty');
        }
        self::$_key = $key;

        // set the hash
        self::_setHashAlgorithm($hash);

        // perform hashing and return
        return self::_hash($data, $output);
    }

    /**
     * Setter for the hash method.
     *
     * @param string $hash
     * @throws Zend_Crypt_Hmac_Exception
     * @return void
     */
    protected static function _setHashAlgorithm($hash)
    {
        if (!isset($hash) || empty($hash)) {
            throw new Zend_Crypt_Hmac_Exception('provided hash string is null or empty');
        }

        $hash          = strtolower($hash);
        $hashSupported = false;

        if (function_exists('hash_algos') && in_array($hash, hash_algos())) {
            $hashSupported = true;
        }


        if ($hashSupported === false) {
            throw new Zend_Crypt_Hmac_Exception('hash algorithm provided is not supported on this PHP installation; please enable the hash extension');
        }
        self::$_hashAlgorithm = $hash;
    }

    /**
     * Perform HMAC and return the keyed data
     *
     * @param string $data
     * @param string $output
     * @param bool $internal Option to not use hash() functions for testing
     * @return string
     */
    protected static function _hash($data, $output = self::STRING, $internal = false)
    {
        if ($output == self::BINARY) {
            return hash_hmac(self::$_hashAlgorithm, $data, self::$_key, true);
        }
        return hash_hmac(self::$_hashAlgorithm, $data, self::$_key);
    }
}
