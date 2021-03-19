

# composer_didrive_parsing_autoas_1c
АвтоАС - Парсинг xml дата файла (экспорт с 1с) и запись его в БД 

запуск обработки дата файлов
/**
 * если if( empty($_REQUEST['skip_rename']) )
 * то не переименовываем дата файл что обработали
 * если if( empty($_REQUEST['no_send_msg']) )
 * то не шлём сообщение в телегу
 * если if (!empty($_REQUEST['show_parse_item'])) {
 * то показываем 50 необработанных итемов
 */
