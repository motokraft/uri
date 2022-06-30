# Удобная работа с ссылками

![Package version](https://img.shields.io/github/v/release/motokraft/uri)
![Total Downloads](https://img.shields.io/packagist/dt/motokraft/uri)
![PHP Version](https://img.shields.io/packagist/php-v/motokraft/uri)
![Repository Size](https://img.shields.io/github/repo-size/motokraft/uri)
![License](https://img.shields.io/packagist/l/motokraft/uri)

## Установка

Библиотека устанавливается с помощью пакетного менеджера [**Composer**](https://getcomposer.org/)

Добавьте библиотеку в файл `composer.json` вашего проекта:

```json
{
    "require": {
        "motokraft/uri": "^1.0"
    }
}
```

или выполните команду в терминале

```
$ php composer require motokraft/uri
```

Включите автозагрузчик Composer в код проекта:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Примеры инициализации

```php
use \Motokraft\Uri\Uri;
$uri = new Uri('index.php');
```

## Лицензия

Эта библиотека находится под лицензией MIT License.