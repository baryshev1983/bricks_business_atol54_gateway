<?php
namespace Bricks\Business\Atol54\Gateway\UnitTest\Operation;

use DateTime;
use PHPUnit\Framework\TestCase;
use Bricks\Business\Atol54\Gateway\ClientInterface;
use Bricks\Business\Atol54\Gateway\Operation\OperationGateway;
use Bricks\Business\Atol54\Auth\Token;
use GuzzleHttp\Psr7\Response;
use Bricks\Business\Atol54\Operation\SellOperation;
use Bricks\Business\Atol54\Operation\SellRefundOperation;
use Bricks\Business\Atol54\Operation\BuyOperation;
use Bricks\Business\Atol54\Operation\BuyRefundOperation;

/**
 * @author Artur Sh. Mamedbekov
 */
class OperationGatewayTest extends TestCase{
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

  public function createOperationMock($class){
    $operation = $this->createMock($class);
    $operation->expects($this->once())
      ->method('toJson')
      ->will($this->returnValue('{}'));

    return $operation;
  }

  /**
   * @param ClientInterface $client [optional]
   * @param string $groupCode [optional]
   * @param string $token [optional]
   *
   * @return OperationGateway
   */
  public function createOperationGateway(ClientInterface $client = null, $groupCode = 'group', $token = 'token'){
    if(is_null($client)){
      $client = $this->createClientMock();
    }
    $token = new Token(1, $token);

    return new OperationGateway($client, $groupCode, $token);
  }

  // Cases
  public function assertOperation($operationClass, $method){
    $clientMock = $this->createClientMock();
    $operationGateway = $this->createOperationGateway($clientMock);
    $operation = $this->createOperationMock($operationClass);

    $clientMock->expects($this->once())
      ->method($method)
      ->with($this->equalTo('group'), $this->equalTo('token'), $this->equalTo('{}'))
      ->will($this->returnValue($this->createResponseWithJson('{
        "uuid": "2ea26f17-0884-4f08-b120-306fc096a58f",
        "timestamp": "12.04.2017 06:15:06",
        "error": null,
        "status": "wait"
      }')));
    $report = $operationGateway->$method($operation);

    $this->assertEquals('2ea26f17-0884-4f08-b120-306fc096a58f', $report->getUuid());
    $this->assertEquals(new DateTime('12.04.2017 06:15:06'), $report->getTimestamp());
    $this->assertEquals('wait', $report->getStatus());
  }

  // Tests
  public function testSell(){
    $this->assertOperation(SellOperation::class, 'sell');
  }

  public function testSellRefund(){
    $this->assertOperation(SellRefundOperation::class, 'sellRefund');
  }

  public function testBuy(){
    $this->assertOperation(BuyOperation::class, 'buy');
  }

  public function testBuyRefund(){
    $this->assertOperation(BuyRefundOperation::class, 'buyRefund');
  }

  public function testGetReport(){
    $clientMock = $this->createClientMock();
    $operationGateway = $this->createOperationGateway($clientMock);

    $clientMock->expects($this->once())
      ->method('getReport')
      ->with($this->equalTo('group'), $this->equalTo('token'), $this->equalTo('uuid'))
      ->will($this->returnValue($this->createResponseWithJson('{
        "uuid": "2ea26f17-0884-4f08-b120-306fc096a58f",
        "error": null,
        "status": "done",
        "payload": {
          "total": 1598,
          "fns_site": "www.nalog.ru",
          "fn_number": "1110000100238211",
          "shift_number": 23,
          "receipt_datetime": "12.04.2017 20:16:00",
          "fiscal_receipt_number": 6,
          "fiscal_document_number": 133,
          "ecr_registration_number": "0000111118041361",
          "fiscal_document_attribute": 3449555941
        },
        "timestamp": "12.04.2017 20:15:08",
        "group_code": "MyCompany_MyShop",
        "daemon_code": "prod-agent-1",
        "device_code": "KSR13.00-1-11",
        "callback_url": "callback"
      }')));
    $report = $operationGateway->getReport('uuid');

    $this->assertEquals('2ea26f17-0884-4f08-b120-306fc096a58f', $report->getUuid());
    $this->assertEquals(new DateTime('12.04.2017 20:15:08'), $report->getTimestamp());
    $this->assertEquals('done', $report->getStatus());
    $this->assertEquals('MyCompany_MyShop', $report->getGroupCode());
    $this->assertEquals('prod-agent-1', $report->getDaemonCode());
    $this->assertEquals('KSR13.00-1-11', $report->getDeviceCode());
    $this->assertEquals('callback', $report->getCallbackUrl());
  }
}
