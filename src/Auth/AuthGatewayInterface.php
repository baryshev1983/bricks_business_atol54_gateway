<?php
namespace Bricks\Business\Atol54\Gateway\Auth;

use Bricks\Business\Atol54\Auth\Auth;
use Bricks\Business\Atol54\Auth\Token;
use Bricks\Business\Atol54\Gateway\Exception\RequestFailedException;
use Bricks\Business\Atol54\Auth\Exception\BadResponseException;
use Bricks\Business\Atol54\Gateway\Auth\Exception\AuthFailedException;

/**
 * Интерфейс шлюза авторизации.
 *
 * @author Artur Sh. Mamedbekov
 */
interface AuthGatewayInterface{
  /**
   * @param Auth $auth Ключевая пара.
   *
   * @throws RequestFailedException
   * @throws BadResponseException
   * @throws AuthFailedException
   *
   * @return Token Токен.
   */
  public function getToken(Auth $auth);
}
