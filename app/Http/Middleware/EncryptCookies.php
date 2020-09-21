<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Symfony\Component\HttpFoundation\Request;

class EncryptCookies extends Middleware
{
	/**
	 * The names of the cookies that should not be encrypted.
	 *
	 * @var array
	 */
	protected $except = [
		//
	];

	/**
	 * Decrypt the cookies on the request.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	protected function decrypt(Request $request)
	{
		foreach ($request->cookies as $key => $cookie) {
			if ($this->isDisabled($key)) {
				continue;
			}

			try {
				$value = $this->decryptCookie($key, $cookie);

				if (!is_string($value)) {
					$value = json_encode($value);
				}

				$hasValidPrefix = strpos($value, CookieValuePrefix::create($key, $this->encrypter->getKey())) === 0;

				$request->cookies->set(
					$key, $hasValidPrefix ? CookieValuePrefix::remove($value) : null
				);


			} catch (DecryptException $e) {
				$request->cookies->set($key, null);
			}
		}

		return $request;
	}
}
