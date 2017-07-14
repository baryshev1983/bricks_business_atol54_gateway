<?php
namespace Bricks\Business\Atol54\Gateway;

use Psr\Http\Message\ResponseInterface;
use Bricks\Business\Atol54\Gateway\Exception\RequestFailedException;

/**
 * HTTP-клиент.
 *
 * @author Artur Sh. Mamedbekov
 */
interface ClientInterface{
  /**
   * Запрос авторизационного токена.
   *
   * @param string $login Логин.
   * @param string $pass Пароль.
   *
   * @throws RequestFailedException
   *
   * @return ResponseInterface Ответ.
   */
  public function getToken($login, $pass);

  /**
   * Регистрация прихода.
   *
   * @param string $groupCode Идентификатор группы ККТ.
   * @param string $token Авторизационный токен.
   * @param string $json Тело операции.
   *
   * @throws RequestFailedException
   *
   * @return ResponseInterface Ответ.
   */
  public function sell($groupCode, $token, $json);

  /**
   * Регистрация возврата прихода.
   *
   * @param string $groupCode Идентификатор группы ККТ.
   * @param string $token Авторизационный токен.
   * @param string $json Тело операции.
   *
   * @throws RequestFailedException
   *
   * @return ResponseInterface Ответ.
   */
  public function sellRefund($groupCode, $token, $json);

  /**
   * Регистрация расхода.
   *
   * @param string $groupCode Идентификатор группы ККТ.
   * @param string $token Авторизационный токен.
   * @param string $json Тело операции.
   *
   * @throws RequestFailedException
   *
   * @return ResponseInterface Ответ.
   */
  public function buy($groupCode, $token, $json);

  /**
   * Регистрация возврата расхода.
   *
   * @param string $groupCode Идентификатор группы ККТ.
   * @param string $token Авторизационный токен.
   * @param string $json Тело операции.
   *
   * @throws RequestFailedException
   *
   * @return ResponseInterface Ответ.
   */
  public function buyRefund($groupCode, $token, $json);

  /**
   * Получение отчета по документу.
   *
   * @param string $groupCode Идентификатор группы ККТ.
   * @param string $token Авторизационный токен.
   * @param string $uuid Идентификатор документа.
   *
   * @throws RequestFailedException
   *
   * @return ResponseInterface Ответ.
   */
  public function getReport($groupCode, $token, $uuid);
}
