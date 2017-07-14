<?php
namespace Bricks\Business\Atol54\Gateway\Auth;

use Bricks\Business\Atol54\Gateway\ClientInterface;
use Bricks\Business\Atol54\Auth\Auth;
use Bricks\Business\Atol54\Auth\Token;
use Bricks\Business\Atol54\Gateway\Auth\Exception\AuthFailedException;
use Bricks\Business\Atol54\Gateway\Exception\RequestFailedException;
use Bricks\Business\Atol54\Auth\Exception\AbstractAuthException;

/**
 * Шлюз для авторизации.
 *
 * @author Artur Sh. Mamedbekov
 */
class AuthGateway implements AuthGatewayInterface{
  /**
   * @var ClientInterface HTTP-клиент.
   */
  private $client;

  /**
   * @param ClientInterface $client HTTP-клиент.
   */
  public function __construct(ClientInterface $client){
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public function getToken(Auth $auth){
    $response = $this->client->getToken($auth->getLogin(), $auth->getPass());

    try{
      return Token::fromJson($response->getBody()->getContents());
    }
    catch(AbstractAuthException $excep){
      throw new AuthFailedException('Authentication failed', 1, $excep);
    }
  }
}
