<?php

/**
 * Файл настроек.
 * 
 * Сделайте необходимые изменения в настройках для запуска бота.
 * Для того чтобы бот заработал, достаточно изменить 'bot_uin',
 * 'bot_password' и 'master_uin' на нужные UIN,пароль и UIN
 * администратора соответственно.
 * 
 */

$cfg = array(            			  
			  //UIN
			  'bot_uin'       => 333333334,
			  
			  //пароль от уина
			  'bot_password'  => 'p1a2s3s',		  

			  // номер администратора бота (или несколько номеров через запятую, 
			  // например array(321742) 
			  'master_uin'    => array( ),
			  
			  //директория с плагинами( абсолютный путь )
			  'plugin_dir'    => dirname(__FILE__).'/plugins',
			  
			  //интервал прослушки сокета, рекомендуется 1
			  'delaylisten'   =>1,
			  
			  //максимальное количество попыток подключения
			  'connect_attempts' => 5,
			  
			  //сообщение пользователю, если введена не команда
			  'err_cmd' => 'Команда не найдена. Для получения помощи наберите help',
			  
			  // кодировка отправляемых сообщений
			  'msg_charset' => 'windows-1251',
			  
			  // чувствительность команд к регистру
			  'msg_case_sensitive' => false,
			  
			  'log' => array (
			  
			     // маска имени файла в логах
			     // переменные: см. http://php.net/date
			     'filename_format' => 'd_M_Y',
			     
			     // формат даты в логах
			     // cинтаксис аналогичен переменным в php функции date()
			     // переменные: см. http://php.net/date
			     'dateformat' => 'H:i:s',
			     
			     /**
			      * формат сообщения лога
			      * 
			      * переменные:
			      *     %d - дата и время (см. опцию log => dateformat)
			      *     %u - UIN, отправивший сообщение 
			      *     %m - сообщение лога
			      *     %n - группа записи (например, название плагина)
			      *     %c - код сообщения
			      * 
			      */
			     'msgformat' => '[%d] %с %n %u %m',
			     
			     'exclude_types' => array(LOG_MSG_SENT)
			  
			  ),
			  
			  /**
			   * Формат вывода каждой команды в помощи
			   *   %c - команда
			   *   %s - текст помощи
			   */
			  'help.format' => "%s\n",
			  'help.format_full' => "%s",
			  
			  /**
			   * Сохранять таймеры в файл при завершении работы бота
			   */
			  'save_actual_timers' => true,
			  
			  /**
			   * автоматически подключаемый при выходе в онлайн файл.
			   */
			  'autoinclude_file' => dirname(__FILE__)."/autorun.php",
			  
			  /**
			   * Язык по умолчанию
			   */
			  'language' => 'ru',
			  
			  /**
			   * Пароль для запуска бота через web-интерфейс
			   */
			  'web_password' => 'steelbot',
			  
			  /**
			   * тип используемой базы данных
			   */
			  'db.engine' => 'txtdbapi',
			  
			  /**
			   * Активировать lock-файл для предотвращения повтороного запуска
			   * бота на одном и том же UIN
			   */
			  'lockfile_enabled' => false
            );
          