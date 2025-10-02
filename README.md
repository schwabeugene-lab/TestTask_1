# Deal Command Processor

Пакет на PHP 8.1+ для обработки текстовых команд в CRM, работающий независимо от фреймворков и готовый к установке через Composer.

## Установка

```
composer require acme/deal-command-processor
```

## Использование

```php
use DealCommands\Command\CommandProcessor;
use DealCommands\Command\CommandRegistry;
use DealCommands\Command\Commands\AcceptPaymentCommand;
use DealCommands\Command\Commands\ContactInfoCommand;
use DealCommands\Command\Commands\SetClosureReasonCommand;
use DealCommands\Command\Commands\ShowClosureReasonCommand;
use DealCommands\Logging\CommandLoggerInterface;
use DealCommands\Logging\Entry\CommandLogEntry;

$registry = new CommandRegistry([
    new AcceptPaymentCommand(),
    new ContactInfoCommand(),
    new SetClosureReasonCommand(),
    new ShowClosureReasonCommand(),
]);

$logger = new class() implements CommandLoggerInterface {
    public function log(CommandLogEntry $entry): void
    {
        // сохранить в базу, файл или в системный лог
    }
};

$processor = new CommandProcessor($registry, $logger);
$result = $processor->process('/принято 500 офис', $dealContext);
```

`$dealContext` должен реализовать интерфейс `DealContextInterface` и обеспечивать доступ к данным сделки.

## Команды из коробки

| Команда | Действие |
| --- | --- |
| `/принято <сумма> <источник>` | Устанавливает свойства сделки #14 и #15 |
| `/контакт` | Публикует служебное сообщение с контактом клиента |
| `/причина_закрытия <текст>` | Сохраняет текст причины закрытия в свойство #222 |
| `/причина` | Публикует служебное сообщение с текущей причиной закрытия |

## Расширение

Чтобы добавить новую команду, реализуйте `CommandInterface` и зарегистрируйте её в `CommandRegistry`.

## Тесты

```
composer install
vendor/bin/phpunit
```
