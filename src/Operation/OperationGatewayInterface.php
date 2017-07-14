<?php
namespace Bricks\Business\Atol54\Gateway\Operation;

use Bricks\Business\Atol54\Operation\SellOperation;
use Bricks\Business\Atol54\Operation\SellRefundOperation;
use Bricks\Business\Atol54\Operation\BuyOperation;
use Bricks\Business\Atol54\Operation\BuyRefundOperation;
use Bricks\Business\Atol54\Operation\Report\ShortReport;
use Bricks\Business\Atol54\Operation\Report\FullReport;
use Bricks\Business\Atol54\Gateway\Operation\Exception\OperationException;
use Bricks\Business\Atol54\Operation\Exception\BadResponseException;

/**
 * Интерфейс шлюза для работы с документами.
 *
 * @author Artur Sh. Mamedbekov
 */
interface OperationGatewayInterface{
  /**
   * @param SellOperation $operation Приход.
   *
   * @throws RequestFailedException
   * @throws BadResponseException
   * @throws OperationException
   *
   * @return ShortReport Отчет.
   */
  public function sell(SellOperation $operation);

  /**
   * @param SellRefundOperation $operation Возврат прихода.
   *
   * @throws OperationException
   * @throws BadResponseException
   * @throws OperationException
   *
   * @return ShortReport Отчет.
   */
  public function sellRefund(SellRefundOperation $operation);

  /**
   * @param BuyOperation $operation Расход.
   *
   * @throws OperationException
   * @throws BadResponseException
   * @throws OperationException
   *
   * @return ShortReport Отчет.
   */
  public function buy(BuyOperation $operation);

  /**
   * @param BuyRefundOperation $operation Возврат расхода.
   *
   * @throws OperationException
   * @throws BadResponseException
   * @throws OperationException
   *
   * @return ShortReport Отчет.
   */
  public function buyRefund(BuyRefundOperation $operation);

  /**
   * @param string $uuid Идентификатор документа.
   *
   * @throws OperationException
   * @throws BadResponseException
   * @throws OperationException
   *
   * @return FullReport Отчет.
   */
  public function getReport($uuid);
}
