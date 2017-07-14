# Bricks.Business.Atol54Gateway

Пакет включает классы, реализующие клиентскую логику для формирования чека согласно 54-ФЗ с использованием [протокола Атол Онлайн][].

## Установка и Автозагрузка

Этот пакет сопровождается файлом [composer.json][], что позволяет использовать 
[Composer][] для его инсталляции и автозагрузки.

Так же можно установить его загрузив [исходные коды][] пакета в виде Zip архива 
или клонировав этот репозиторий. Все компоненты этого пакета загружают 
зависимости автоматически.

Перед использованием рекомендуется выполнить тестирование с помощью утилиты 
[PHPUnit][] вызвав ее в корневом каталоге пакета.

## Зависимости

Этот пакет зависит от:

* Интерпретатора PHP версии 5.5 или выше
* Пакета [Bricks.Business.Atol54][]

## Поддержка

Если у вас возникли сложности или вопросы по использованию пакета, создайте 
[обсуждение][] в данном репозитории или напишите на электронную почту 
<Artur-Mamedbekov@yandex.ru>.

## Документация

Пользовательскую документацию можно получить по [ссылке](./docs/index.md).

Документацию API можно получить из исходных кодов пакета или с помощью утилиты 
[Doxygen][].

[протокола Атол Онлайн]: http://online.atol.ru/
[composer.json]: ./composer.json
[Composer]: http://getcomposer.org/
[исходные коды]: https://github.com/Bashka/bricks_business_atol54_gateway/releases
[PHPUnit]: http://phpunit.de/
[Bricks.Business.Atol54]: https://github.com/Bashka/bricks_business_atol54
[обсуждение]: https://github.com/Bashka/bricks_business_atol54_gateway/issues
[Doxygen]: http://www.stack.nl/~dimitri/doxygen/index.html
