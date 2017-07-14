<?php
namespace Bricks\Business\Atol54\Gateway\UnitTest\Auth;

use PHPUnit\Framework\TestCase;
use Bricks\Business\Atol54\Gateway\ClientInterface;
use Bricks\Business\Atol54\Gateway\Auth\AuthGateway;
use Bricks\Business\Atol54\Auth\Auth;
use GuzzleHttp\Psr7\Response;

/**
 * @author Artur Sh. Mamedbekov
 */
class AuthGatewayTest extends TestCase{
  // Factories
  /**
   * @param string $json
   *
   * @return Response
   */
  public function createResponseWithJson($json){
    return new Response(200, [], $json);
  }

  /**
   * @return ClientInterface
   */
  public function createClientMock(){
    return $this->createMock(ClientInterface::class);
  }

  /**
   * @param ClientInterface $client [optional]
   *
   * @return AuthGateway
   */
  public function createAuthGateway(ClientInterface $client = null){
    if(is_null($client)){
      $client = $this->createClientMock();
    }
    return new AuthGateway($client);
  }

  // Tests
  public function testGetToken(){
    $clientMock = $this->createClientMock();
    $authGateway = $this->createAuthGateway($clientMock);
    $auth = new Auth('login', 'pass');

    $clientMock->expects($this->once())
      ->method('getToken')
      ->with($this->equalTo('login'), $this->equalTo('pass'))
      ->will($this->returnValue($this->createResponseWithJson('{
        "code": 1,
        "text": null,
        "token": "031c9f6f25ff44e3acffb8b620fd521a"
      }')));
    $token = $authGateway->getToken($auth);

    $this->assertEquals(1, $token->getCode());
    $this->assertEquals('031c9f6f25ff44e3acffb8b620fd521a', $token->getToken());
  }
}
