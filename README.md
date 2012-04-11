# CSScomb для Sublime Text 2

## Требования к окружению

1. OS X, Linux или Windows на котором установлен [Sublime Text 2](http://www.sublimetext.com/2)

2. PHP-интерпретатор. На OS X он установлен из коробки. А для Linux/Windows его нужно скачать с официального сайта [php.net](http://windows.php.net/download/) и добавить путь до папки куда вы установили PHP в переменную окружения $PATH. Подробнее о том, как это сделать на Windows читайте [тут](https://github.com/miripiruni/CSScomb/blob/master/src/plugins/csscomb.notepad_plus_plus/README.mkd).

## Установка

1. Скачиваем последнюю версию [плагина CSScomb для Sublime Text 2](https://github.com/i-akhmadullin/Sublime-CSSComb)

2. В Sublime открываем: `Preferences` -> `Browse Packages...` ( Откроется папка с примерно таким адресом `C:\Users\user\AppData\Roaming\Sublime Text 2\Packages` ) Создаем в этой папке папку `CSScomb`, копируем в нее файлы плагина
**Другой способ**. Скачать [sublime-package](https://github.com/i-akhmadullin/Sublime-CSSComb/downloads), положить его в `AppData\Roaming\Sublime Text 2\Installed Packages` и перезапустить редактор.


##Использование

Запуск сортировки стилей для текущего файла
`ctrl + alt + shift + c`
`ctrl + shift + p` -> `Sort via CSScomb`
Выбрать в меню `Tools` -> `Sort via CSScomb`, либо `Sort via CSScomb` в контекстном меню


##Issues & bugs

[https://github.com/i-akhmadullin/Sublime-CSSComb/issues](CSScomb for Sublime Text 2 tracker)