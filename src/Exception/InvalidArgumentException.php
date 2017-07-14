<?php
namespace Bricks\Business\Atol54\Gateway\Exception;

use InvalidArgumentException as StdInvalidArgumentException;

/**
 * @author Artur Sh. Mamedbekov
 */
class InvalidArgumentException extends StdInvalidArgumentException{
  /**
   * @param string $name Имя параметра.
   * @param string $shouldBe Ожидаемый тип.
   * @param mixed $real Реальное значение.
   */
  public static function fromParam($name, $shouldBe, $real){
    return new self(sprintf(
      'The "%s" should be a "%s", "%s" given.',
      $name,
      $shouldBe,
      is_object($real)? get_class($real) : gettype($real)
    ));
  }
}
