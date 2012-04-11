# CSScomb плагин для  Sublime Text 2


## Установка:

1. Скачать PHP с официального сайта [php.net](http://windows.php.net/download/)  
Добавить путь до папки куда вы установили PHP в переменную окружения PATH

2. Скачиваем последнюю версию [плагина CSScomb для Sublime Text 2](https://github.com/i-akhmadullin/Sublime-CSSComb)

3. В Sublime открываем: `Preferences` -> `Browse Packages...` ( Откроется папка с примерно таким адресом `C:\Users\user\AppData\Roaming\Sublime Text 2\Packages` ) Создаем в этой папке папку `CSScomb`, копируем в нее файлы плагина
3.1 Другой способ. Скачать [sublime-package](https://github.com/i-akhmadullin/Sublime-CSSComb/downloads), положить его в `AppData\Roaming\Sublime Text 2\Installed Packages` и перезапустить редактор.

5. В случае проблем проверяем правильно установлен PHP и настроен ли путь до него


##Использование: 

Запуск сортировки стилей для текущего файла  
`ctrl + alt + shift + c`  
`ctrl + shift + p` -> `Sort via CSScomb`  
Выбрать в меню `Tools` -> `Sort via CSScomb`, либо `Sort via CSScomb` в контекстном меню