<?php

$lang = array(

    'cm_help'      => "Синтаксис: .help [.command]
                            
Если команда введена без параметров, то выводит список команд администрирования. Если введен параметр .command, то бот вернет текст помощи для команды .command",
    
    'cm_cmdaccess' => "Синтаксис: .cmdaccess command [new_level]\n
Установить/просмотреть уровень доступа к команде command. Если указан new_level, то он будет установлен в качестве уровня доступа к команде, иначе бот вернет текущий уровень доступа к команде command.",
    
    'cm_eval'      => "Синтаксис: .eval php_code\n
интерпретирует php-код php_code (с помощью функции eval()). Возвращает результат вывода кода.",
    
    'cm_exit'      => "Синтаксис: .exit\n
Завершает работу бота.",
    
    'cm_opt'       => "Синтаксис: 
.opt create option value - создать в боте конфигурационную переменную с именем option и значением value
.opt delete option - удалить переменную с именем option
.opt set option new_value - установить значение существующей переменной в new_value
.opt list option - посмотреть значение существующей переменной option.",
    
    'cm_plugin'   => "Синтаксис:
.plugin - список установленных плагинов
.plugin load plg_name - загрузить плагин plg_name
.plugin enable plg_name - включить все команды плагина plg_name
.plugin disable plg_name - выключить все команды плагина plg_name
.plugin info plg_name - посмотреть информацию о плагине plg_name",
    
    'cm_reconnect' => "Синтаксис: .reconnect\n
Переподключает бота к ICQ",
    
    'cm_useraccess'=> "Синтаксис: .useraccess uin [new_level]\n
Посмотреть или изменить уровень доступа для номера uin. Если задан параметр new_level, то пользователю устанавливается уровень new_level, в противном случае бот возвращает текущий уровень доступа пользователя.",
    
    'cm_timer'     => "Управление таймерами бота. Синтаксис:
.timer list - выводит список установленных таймеров
.timer add time func - устанавливает вызов функции func через время time (указывается в секундах)
.timer del ~interval - удаляет все таймеры, которые должны сработать в ближайшие interval секунд
.timer del ^func - удаляет все вызовы функции func из таймеров",
    
    
    'parsecommand_1' => 'Команда %1 не найдена. Введите %2help для получения помощи по командам',
    'cmdhelp_1' => "Команды администратора:\n%1\n\nЧтобы увидеть подробную справку по каждой команде, наберите .help имя_команды",
    'cmdhelp_2' => 'Команада %1%2 не найдена',
    'cmdexit_1' => 'Завершение работы...',
    
    'cmdopt_1' => '%1 создана со значением %2',
    'cmdopt_2' => 'Опция %1 уже существует',
    'cmdopt_3' => '%1 удалена',
    'cmdopt_4' => 'Опции %1 не существует',
    'cmdopt_5' => 'Номер владельца можно менять только в файле конфигурации',
    'cmdopt_6' => '%1 установлена в значение %2. Старое значение: %3',
    'cmdopt_7' => "Установленные опции бота:\n\n%1",
    'cmdopt_8' => 'Значение %1: %2',
    'cmdopt_9' => 'Опции %1 не существует',
    'cmdopt_10' => "Установленные опции бота:\n\n%1",
    
    'cmduseraccess_1' => 'Синтаксис: %1useraccess uin',
    'cmduseraccess_2' => 'Доступ пользвателя %1: %2',
    'cmduseraccess_3' => '%1 является мастер-уином и его права нельзя изменять',
    'cmduseraccess_4' => 'Пользователю %1 установлен уровень доступа %2',
    'cmduseraccess_5' => 'Ошибка при изменении прав пользователя',
    'cmduseraccess_6' => 'Введен неверный уин.',
    
    'cmdcmdaccess_1' => 'Синтаксис: %1cmdaccess command',
    'cmdcmdaccess_2' => 'Уровень доступа к команде %1: %2',
    'cmdcmdaccess_3' => 'Команда %1 не найдена',
    'cmdcmdaccess_4' => 'Команде %1 установлен уровень доступа %2',
    'cmdcmdaccess_5' => 'Введен неверный уровень доступа.',
    
    'cmdplugins_1' => "Установленные плагины:\n%1",
    'cmdplugins_2' => 'Плагин %1 загружен',
    'cmdplugins_3' => 'При загрузке плагина произошла ошибка',
    'cmdplugins_4' => "Информация о плагине %1:\n Автор: %2\n Версия: %3\n\n Поставляемые команды:\n %4\n\n".
                      "Плагины, от которых зависит %1:\n %5",
    'cmdplugins_5' => 'Плагин %1 не найден',
    
    'cmdtimer_1' => "Список установленных таймеров. Формат:\nВремя срабатывания => вызываемая функция (осталось секунд)\n",
    'cmdtimer_2' => 'Таймер для %1 установлен на срабатывание через %2 секунд',
    'cmdtimer_3' => 'Неверное время таймера',
    'cmdtimer_4' => 'Удалено таймеров: %1',
    'cmdtimer_5' => "Всего установлено таймеров: %1\n".
                       "Ближайший таймер: %2 (сработает через %3 секунд)",
    'cmdtimer_6' => 'Таймеров не установлено',

    

);