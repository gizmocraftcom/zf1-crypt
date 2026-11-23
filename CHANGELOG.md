# Changelog

## 2.0.0

Fork of `diablomedia/zendframework1-crypt`, published as `gizmocraft/zendframework1-crypt`.

### Breaking changes

- Requires PHP `>=8.5`. Tested on PHP 8.5 and 8.6.
- Package renamed from `diablomedia/zendframework1-crypt` to `gizmocraft/zendframework1-crypt`. It requires `gizmocraft/zendframework1-exception` `^2.0.0`.
- Removed the `mhash` fallback, because the `mhash*()` functions are deprecated since PHP 8.1 and the `hash` extension is always available:
  - removed the public constant `Zend_Crypt::TYPE_MHASH`
  - removed the protected static property `Zend_Crypt::$_supportedAlgosMhash`
  - removed the protected static method `Zend_Crypt::_digestMhash()`
  - removed the protected static property `Zend_Crypt_Hmac::$_supportedMhashAlgorithms`
  - removed the protected static method `Zend_Crypt_Hmac::_getMhashDefinition()`
  - `Zend_Crypt_Hmac::_hash()` now always uses `hash_hmac()` and returns `string`
  - the "algorithm not supported" exception message of `Zend_Crypt_Hmac` no longer mentions mhash
- Removed the `mcrypt_create_iv()` fallback from `Zend_Crypt_Math::randBytes()`. The mcrypt extension is gone since PHP 7.2. `random_bytes()` is always used.
- Removed the `ext-mcrypt` entry from `suggest` in `composer.json`.
