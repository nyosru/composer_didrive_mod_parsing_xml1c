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
        require_once '0start.php';
        $skip_start = false;
    }






    try {

//f\pa($now);
// \f\pa($now, 2);
// $amnu = \Nyos\nyos::get_menu($now['folder']);

        \Nyos\nyos::getMenu();
// $amnu = \Nyos\nyos::$menu;
//        if (empty(\Nyos\nyos::$menu))
//            throw new \Exception('пустое меню');
//
//        \f\pa( \Nyos\nyos::$menu, 2);
//            $dir = $_SERVER['DOCUMENT_ROOT'] . DS . 'sites' . DS . \Nyos\Nyos::$folder_now . DS . 'download' . DS .'1c.dump' . DS ;
//            
//            $list = scandir($dir);
//            \f\pa($list);
//            
//            foreach( $list as $v ){
//                
//                if( $v == 'AllCatalog.xml' ){
//        \f\pa(\Nyos\Nyos::$folder_now);

//        try {

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
                // \f\pa($res_in, 2, '', '$res_in');
            }
            
//        } catch (\Exception $ex) {
//
//            \f\pa($ex);
//        }




//                            PageData::parseFile(
//                                $_SERVER['DOCUMENT_ROOT'] . DS . '9.site' . DS . $now['folder'] . DS . 'download' . DS . 'datain' . DS . $v1['datain_name_file'], $now['folder'], $v1['cfg.level'], ( isset($v1['type_file_data']) ? $v1['type_file_data'] : null)
//                        );
//                    
//                }
//                
//            }
//            
//                    
//                        require_once './../class.php';
//
//                        Nyos\mod\PageData::parseFile(
//                                $_SERVER['DOCUMENT_ROOT'] . DS . '9.site' . DS . $now['folder'] . DS . 'download' . DS . 'datain' . DS . $v1['datain_name_file'], $now['folder'], $v1['cfg.level'], ( isset($v1['type_file_data']) ? $v1['type_file_data'] : null)
//                        );
//
//                        echo '<br/>обработка файла данных прошла успешно';
//                    } else {
//                        echo '<br/>файл данных не обнаружен';
//                    }
//                }
//            }
//        }

        if (isset($get['action']) && $get['action'] == 'scan_new_file') {
            
        } else {
            $msg = 'Обработано каталогов:' . sizeof($res['data']['cats']) . ' '
                    . ' товаров: ' . sizeof($res['data']['items']);

            \nyos\Msg::sendTelegramm($msg, null, 2);

            die($msg);
        }
    } catch (\Exception $exc) {

// echo $exc->getTraceAsString();

        \nyos\Msg::sendTelegramm('произошла ошибка ' . $exc->getMessage(), null, 2);

        echo '<pre>';
        print_r($_REQUEST);
        echo PHP_EOL;
        print_r($exc);
        echo '</pre>';
    }

    die(__FILE__ . ' #' . __LINE__);


// \f\pa($_REQUEST);

    if (isset($_REQUEST['s']) && isset($_REQUEST['id']) && \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['id']) !== false) {
        
    } else {

        \f\end2('произошла неописуемая ситуация №' . __LINE__, false);

// \f\pa($_REQUEST);
        throw new \Exception('не', __LINE__);
    }

// \f\pa($_REQUEST);

    $date_start = date('Y-m-01', strtotime($_REQUEST['date']));
    $date_finish = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));

    $delete = ['sale_point' => (int) $_REQUEST['in']['sale_point']];

    $in_db = $_REQUEST['in'];
// $in_db = ['sale_point' => (int) $_REQUEST['in']['sale_point'] ];



    $dates = [];
    $in_data = [];

    for ($i = 0; $i <= 35; $i++) {

        $now = date('Y-m-d', strtotime($date_start . ' +' . $i . ' day'));

        if ($now <= $date_finish) {

            $nowdn = date('w', strtotime($now));
// echo '<br/>' . $nowdn;

            if ($now == $_REQUEST['date']) {

                $delete['date'][] = $now;
                $in_data[] = ['date' => $now];
            } else if (!empty($_REQUEST['copyto']) && in_array($nowdn, $_REQUEST['copyto'])) {

                $delete['date'][] = $now;
                $in_data[] = ['date' => $now];
// echo '+'.$now;
            }
//            else{
//                echo '-'.$now;
//            }
        }
    }

// \f\pa($delete);

    \Nyos\mod\items::deleteItemForDops($db, \Nyos\mod\JobDesc::$mod_norms_day, $delete);

// foreach( )

    $in_db = $_REQUEST['in'];

// \f\pa( \Nyos\mod\JobDesc::$mod_norms_day );

    $res = \Nyos\mod\items::adds($db, \Nyos\mod\JobDesc::$mod_norms_day, $in_data, $in_db);

// return \f\end3('ok', true , $res );

    \f\end2('<div class="warn" style="padding:5px;" >'
            . '<b>параметры установлены</b>'
            . '<br/>с ' . implode(', ', $delete['date'])
            . '</div>', true);
} catch (\Exception $exc) {

// \f\end2( [ $_REQUEST, $exc ]  );

    echo '<pre>';
    print_r($_REQUEST);
    echo PHP_EOL;
    print_r($exc);
    echo '</pre>';
// echo $exc->getTraceAsString();
}