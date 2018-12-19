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
class GuzzleClient implements ClientInterface
{
  const SERVER_URL = 'https://online.atol.ru/possystem/v4';

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
      sprintf('%s/%s/%s?', self::SERVER_URL, $groupCode, $method),
      [
        'Content-Type' => 'application/json; charset=utf-8',
        'Token' => $token,
        'Content-Length' => strlen($json),
      ],
      $json
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getToken($login, $pass)
  {
    $request = new Request(
      'POST',
      self::SERVER_URL . '/getToken?',
      [
          'Content-Type' => 'application/json; charset=utf-8',
      ],
      \GuzzleHttp\json_encode([
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
  public function getReport($groupCode, $token, $uuid)
  {
      $request = new Request(
        'GET',
        sprintf(
            self::SERVER_URL . '/%s/report/%s?',
            $groupCode,
            $uuid
        ),
        [
            'Content-Type' => 'application/json; charset=utf-8',
            'Token' => $token,
        ]
    );

    return $this->send($request);
   }
}
