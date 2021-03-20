<?php

// ставим новые нормы дня на всякие дни

if (strpos($_SERVER['HTTP_HOST'], 'dev.') !== false) {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

try {

//    if (empty($_REQUEST['date']))
//        throw new \Exception('нет даты');
//
//    if (empty($_REQUEST['user']))
//        throw new \Exception('нет пользователя');
//
//    if (empty($_REQUEST['dolgn']))
//        throw new \Exception('нет должности');

    if (isset($skip_start) && $skip_start === true) {
        
    } else {
        //require_once '0start.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive/base/start-for-microservice.php';
        $skip_start = false;
    }






    try {

        \Nyos\nyos::getMenu();

        $res = Nyos\mod\parsing_xml1c::scanNewDataFile($db, \Nyos\Nyos::$folder_now);
        // \f\pa($res, 2);

        if (!empty($res['data']['cats'])) {

            $sql = 'TRUNCATE `mod_020_cats` ;';
            $s2 = $db->prepare($sql);
            $s2->execute();

            $res_in = \Nyos\mod\items::adds($db, '020.cats', $res['data']['cats']);
            // \f\pa($res_in, 2, '', '$res_in');
        }

        if (!empty($res['data']['items'])) {

            $sql = 'TRUNCATE `mod_021_items` ;';
            $s2 = $db->prepare($sql);
            $s2->execute();

            $res_in = \Nyos\mod\items::adds($db, '021.items', $res['data']['items']);
        }

        // \f\pa($res['data']['analogs'], 2, '', '$data analogs вставляем аналоги');
        // echo __FILE__;

        if (!empty($res['data']['analogs'])) {

            $sql = 'TRUNCATE `mod_021_items_analogs` ;';
            $s2 = $db->prepare($sql);
            $s2->execute();

            // \Nyos\mod\items::$show_sql = true;
            // \Nyos\mod\items::$type_module = 3;
            $res_in = \Nyos\mod\items::adds($db, '021.items_analogs', $res['data']['analogs']);
            // \f\pa($res_in, 2, '', '$resin');
        }

        $msg = 'Обработано каталогов:' . sizeof($res['data']['cats']) . ' '
                . ' товаров: ' . sizeof($res['data']['items']);

        if( empty($_REQUEST['no_send_msg']) )
        \nyos\Msg::sendTelegramm($msg, null, 2);

        die($msg);
    }
    //
    catch (\Exception $exc) {

        \nyos\Msg::sendTelegramm('произошла ошибка ' . $exc->getMessage(), null, 2);
        \f\pa('ошибка ' . $exc->getMessage());
    }
}
//
catch (\Exception $exc) {

    \nyos\Msg::sendTelegramm('произошла ошибка ' . $exc->getMessage(), null, 2);
    \f\pa('ошибка ' . $exc->getMessage());
}

die('die end ' . __FILE__ . ' #' . __LINE__);
