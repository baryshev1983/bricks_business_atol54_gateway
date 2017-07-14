# HTTP-клиент

Все перечисленные здесь шлюзы используют одну из реализаций интерфейса _ClientInterface_, описывающего низкоуровневые операции взаимодействия с сервисом по протоколу [Атол Онлайн][].

# Авторизация

Класс _AuthGateway_ является дефолтной реализацией интерфейса _AuthGatewayInterface_, описывающего шлюз для выполнения авторизации по протоколу [Атол Онлайн][].

Пример:

```php
use Bricks\Business\Atol54\Auth\Auth;
use Bricks\Business\Atol54\Auth\Token;
use Bricks\Business\Atol54\Gateway\GuzzleClient;
use Bricks\Business\Atol54\Gateway\Auth\AuthGateway;

$auth = new Auth('my_login', 'my_password');

$gateway = new AuthGateway(new GuzzleClient);
$token = $gateway->getToken($auth);

echo $token->getToken();
```

# Операции

Класс _OperationGateway_ является дефолтной реализацией интерфейса _OperationGatewayInterface_, описывающего шлюз для регистрации документов по протоколу [Атол Онлайн][].

Пример операции "приход":

```php
use Bricks\Business\Atol54\Operation\Field\Item;
use Bricks\Business\Atol54\Operation\Field\ItemList;
use Bricks\Business\Atol54\Operation\Field\Payment;
use Bricks\Business\Atol54\Operation\Field\PaymentList;
use Bricks\Business\Atol54\Operation\Field\Attributes;
use Bricks\Business\Atol54\Operation\Field\Receipt;
use Bricks\Business\Atol54\Operation\Field\Service;
use Bricks\Business\Atol54\Operation\SellOperation;
use Bricks\Business\Atol54\Gateway\GuzzleClient;
use Bricks\Business\Atol54\Gateway\Operation\OperationGateway;

$itemList = new ItemList;
$quantity = 1;
$price = 10;
$itemList->add(new Item('Болты "Особые"', $quantity, $price, Item::TAX_NO));

$paymentList = new PaymentList;
$quantity = 1;
$price = 10;
$paymentList->add(new Payment($quantity, $price));

$clientEmail = 'client@mail.com';
$attributes = new Attributes(Attributes::SNO_PATENT, $clientEmail);

$receipt = new Receipt($attributes, $itemList, $paymentList);

$inn = '0123456789';
$magazineDomain = 'magazine.com';
$service = new Service($inn, $magazineDomain);

$orderId = '123';
$operation = new SellOperation($orderId, $receipt, $service);

$gateway = new OperationGateway(new GuzzleClient);
$report = $gateway->sell($operation);

echo $report->getUuid();
echo $report->getStatus();
```
# Отчетность

Метод _OperationGateway::getReport_ позволяет получить информацию о текущем состоянии регистрируемой операции.

Пример:

```php
use Bricks\Business\Atol54\Gateway\GuzzleClient;
use Bricks\Business\Atol54\Gateway\Operation\OperationGateway;

$gateway = new OperationGateway(new GuzzleClient);
$report = $gateway->getReport($uuid);

echo $report->getPayload()->getFnNumber();
```

[Атол онлай]: http://online.atol.ru/
