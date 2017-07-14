<?php
namespace Bricks\Business\Atol54\Gateway;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Client as DefaultGuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Bricks\Business\Atol54\Gateway\Exception\RequestFailedException;

/**
 * HTTP-клиент, использующий библиотеку Guzzle.
 *
 * @see https://github.com/guzzle/guzzle
 *
 * @author Artur Sh. Mamedbekov
 */
class GuzzleClient implements ClientInterface{
  /**
   * @var GuzzleClientInterface Guzzle-клиент.
   */
  private $guzzle;

  /**
   * @return ClientInterface Дефолтный HTTP-клиент.
   */
  public static function createDefault(){
    return new self(new DefaultGuzzleClient);
  }

  /**
   * @param GuzzleClientInterface $guzzle Guzzle-клиент.
   */
  public function __construct(GuzzleClientInterface $guzzle){
    $this->guzzle = $guzzle;
  }

  /**
   * @param RequestInterface $request Запрос.
   * @param array $options Опции запроса.
   *
   * @throws RequestFailedException
   *
   * @return ResponseInterface Ответ.
   */
  protected function send(RequestInterface $request, array $options = []){
    try{
      return $this->guzzle->send($request, $options);
    }
    catch(GuzzleRequestException $excep){
      if($excep->hasResponse()){
        return $excep->getResponse();
      }
      else{
        throw new RequestFailedException('Request failed', 1, $excep);
      }
    }
    catch(GuzzleException $excep){
      throw new RequestFailedException('Request failed', 1, $excep);
    }
  }

  /**
   * @param string $groupCode Идентификатор группы ККТ.
   * @param string $token Авторизационный токен.
   * @param string $json Тело операции.
   * @param string $method Наименование операции.
   *
   * @return RequestInterface Результирующий запрос.
   */
  protected function prepareRequestForOperation($groupCode, $token, $json, $method){
    return new Request(
      'POST',
      sprintf('https://online.atol.ru/possystem/v3/%s/%s?', $groupCode, $method) . http_build_query([
        'tokenid' => $token,
      ]),
      [
        'Content-Type' => 'application/json',
        'Content-Length' => strlen($json),
      ],
      $json
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getToken($login, $pass){
    $request = new Request(
      'GET',
      'https://online.atol.ru/possystem/v3/getToken?' . http_build_query([
        'login' => $login,
        'pass' => $pass
      ])
    );

    return $this->send($request);
  }

  /**
   * {@inheritdoc}
   */
  public function sell($groupCode, $token, $json){
    $request = $this->prepareRequestForOperation($groupCode, $token, $json, 'sell');

    return $this->send($request);
  }

  /**
   * {@inheritdoc}
   */
  public function sellRefund($groupCode, $token, $json){
    $request = $this->prepareRequestForOperation($groupCode, $token, $json, 'sell_refund');

    return $this->send($request);
  }

  /**
   * {@inheritdoc}
   */
  public function buy($groupCode, $token, $json){
    $request = $this->prepareRequestForOperation($groupCode, $token, $json, 'buy');

    return $this->send($request);
  }

  /**
   * {@inheritdoc}
   */
  public function buyRefund($groupCode, $token, $json){
    $request = $this->prepareRequestForOperation($groupCode, $token, $json, 'buy_refund');

    return $this->send($request);
  }

  /**
   * {@inheritdoc}
   */
  public function getReport($groupCode, $token, $uuid){
    $request = new Request(
      'GET',
      sprintf('https://online.atol.ru/possystem/v3/%s/report/%s?', $groupCode, $uuid) . http_build_query([
        'tokenid' => $token,
      ])
    );

    return $this->send($request);
  }
}
