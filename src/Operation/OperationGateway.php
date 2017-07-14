<?php
namespace Bricks\Business\Atol54\Gateway\Operation;

use Bricks\Business\Atol54\Gateway\ClientInterface;
use Bricks\Business\Atol54\Auth\Token;
use Bricks\Business\Atol54\Operation\AbstractOperation;
use Bricks\Business\Atol54\Operation\SellOperation;
use Bricks\Business\Atol54\Operation\SellRefundOperation;
use Bricks\Business\Atol54\Operation\BuyOperation;
use Bricks\Business\Atol54\Operation\BuyRefundOperation;
use Bricks\Business\Atol54\Operation\Report\ShortReport;
use Bricks\Business\Atol54\Operation\Report\FullReport;
use Bricks\Business\Atol54\Gateway\Exception\InvalidArgumentException;
use Bricks\Business\Atol54\Gateway\Exception\RequestFailedException;
use Bricks\Business\Atol54\Operation\Exception\BadResponseException;
use Bricks\Business\Atol54\Gateway\Operation\Exception\OperationException;
use Bricks\Business\Atol54\Operation\Exception\AbstractOperationException;

/**
 * Шлюз для работы с документами.
 *
 * @author Artur Sh. Mamedbekov
 */
class OperationGateway implements OperationGatewayInterface{
  /**
   * @var ClientInterface HTTP-клиент.
   */
  private $client;

  /**
   * @var string Код группы ККТ.
   */
  private $groupCode;

  /**
   * @var Token Авторизационный токен.
   */
  private $token;

  /**
   * @param ClientInterface $client HTTP-клиент.
   * @param string $groupCode Код группы ККТ.
   * @param Token $token Авторизационный токен.
   */
  public function __construct(ClientInterface $client, $groupCode, Token $token){
    $this->client = $client;
    if(!is_string($groupCode)){
      throw InvalidArgumentException::fromParam('groupCode', 'string', $groupCode);
    }
    $this->groupCode = $groupCode;
    $this->token = $token;
  }

  /**
   * @param string $method Метод запроса.
   * @param AbstractOperationException $operation Регистрируемая операция.
   *
   * @throws RequestFailedException
   * @throws BadResponseException
   * @throws OperationException
   *
   * @return ShortReport Отчет.
   */
  private function registerOperation($method, AbstractOperation $operation){
    $response = $this->client->$method(
      $this->groupCode,
      (string) $this->token,
      $operation->toJson()
    );

    try{
      $responseBody = $response->getBody()->getContents();
      return ShortReport::fromJson($responseBody);
    }
    catch(AbstractOperationException $excep){
      throw new OperationException('Invalid operation', 1, $excep);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function sell(SellOperation $operation){
    return $this->registerOperation('sell', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function sellRefund(SellRefundOperation $operation){
    return $this->registerOperation('sellRefund', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function buy(BuyOperation $operation){
    return $this->registerOperation('buy', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function buyRefund(BuyRefundOperation $operation){
    return $this->registerOperation('buyRefund', $operation);
  }

  /**
   * {@inheritdoc}
   */
  public function getReport($uuid){
    $response = $this->client->getReport(
      $this->groupCode,
      (string) $this->token,
      $uuid
    );

    try{
      return FullReport::fromJson($response->getBody()->getContents());
    }
    catch(AbstractOperationException $excep){
      throw new OperationException('Invalid operation', 1, $excep);
    }
  }
}
