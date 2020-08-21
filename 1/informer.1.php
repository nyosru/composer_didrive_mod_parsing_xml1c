<?php

/**
  информер
 * */
// echo '<br/>Сканим файлы';

//
//if (1 == 2) {
//
//    function parsing_xml_1s($db, $folder = null, $mod_cats = '020.cats', $mod_items = '021.items') {
//
////  echo dir_site_sd;
////  echo '<br/>';
////  echo '<br/>';
////  echo $folder;
////  echo '<br/>';
////  $sc = scandir( dirname(__FILE__) . '/download/'.( !empty($folder) ? $folder.'/' : '' ) );
//
//        $sc = DR . dir_site_sd . (!empty($folder) ? $folder . '/' : '' );
//
//        if (!is_dir($sc))
//            return \f\end3('нет папки', false);
//
//        // echo '<br/>' . $sc;
//
//        $sc_scan = scandir($sc);
//
//        foreach ($sc_scan as $k => $file) {
//
//            if (strpos($file, '.old.') !== false)
//                continue;
//
//            if (strpos($file, '.xml') !== false) {
//
//                $reader = new XMLReader();
//
//                if (!$reader->open($sc . $file)) {
//                    die('Failed to open ' . $sc . $file);
//                }
//
//                $d = ['id' => 0, 'parentId' => 0, 'name' => 'head'];
//                $d_item = ['id' => 0, 'categoryId' => 0, 'price' => 0, 'in_stock' => 0];
//
//                $cats = [];
//                $items = [];
//
//                while ($reader->read()) {
//
//                    if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'category') {
//
//                        $d1 = [];
//
//                        $node = (array) new SimpleXMLElement($reader->readOuterXML());
//
//                        if (!empty($node['@attributes']))
//                            foreach ($node['@attributes'] as $k1 => $v1) {
//                                if (!empty($v1)) {
//                                    if ($k1 == 'name') {
//                                        $d1['head'] = $v1;
//                                    } else {
//                                        $d1['a_' . $k1] = $v1;
//                                    }
//                                }
//                            }
//                        $cats[] = $d1;
//                    } elseif ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'item') {
//
//                        $d1 = [];
//
//                        $node = (array) new SimpleXMLElement($reader->readOuterXML());
//
//                        if (!empty($node['name'])) {
//                            $d1['head'] = $node['name'];
//
//                            if (!empty($node['@attributes']))
//                                foreach ($node['@attributes'] as $k1 => $v1) {
//                                    if (!empty($v1))
//                                        $d1['a_' . $k1] = $v1;
//                                }
//                        }
//
//                        $items[] = $d1;
//                    }
//                }
//
//                $reader->close();
//
//                // rename($sc . $file, $sc . $file . '.'.  date('Y-m-d-h-i-s') . '.old.xml');
//
//                \Nyos\mod\items::deleteFromDops($db, $mod_cats);
//                \Nyos\mod\items::addNewSimples($db, $mod_cats, $cats);
//                // \f\pa($cats, 2);
////            $we = \Nyos\mod\items::get($db, $mod_cats);
////            $nn = 1;
////            foreach ($we as $k => $v) {
////
////                if ($nn > 2)
////                    break;
////
////                \f\pa($v);
////
////                $nn++;
////            }
//
//
//                \Nyos\mod\items::deleteFromDops($db, $mod_items);
//                \Nyos\mod\items::addNewSimples($db, $mod_items, $items);
//                // \f\pa($items, 2);
//
//                return \f\end3('файл обработали', true, [
//                    'cats_colvo' => sizeof($cats),
////                'cats' => sort_cats($cats),
////                'cats_all' => $cats,
////                'items' => $items,
//                    'items_colvo' => sizeof($items),
////                '123' => 123
//                ]);
////            break;
//            }
//        }
//
//        return \f\end3('нет файла данных', false);
//    }
//
//    function sort_cats($ar, $up = null) {
//
//        $cats = [];
//
//        foreach ($ar as $k => $v) {
//
//            if (
//                    ( empty($up) && !isset($v['a_parentId']) ) || (!empty($up) && !empty($v['a_parentId']) && $up == $v['a_parentId'] )
//            ) {
//
//                $ee = sort_cats($ar, $v['a_id']);
//
//                if (!empty($ee))
//                    $v['cats'] = $ee;
//
//                $cats[] = $v;
//            }
//        }
//
//        return !empty($cats) ? $cats : false;
//    }
//
//}
//
//// \f\timer_start(12);
//// \f\pa($vv['now_inf_cfg']['folder']);
//// die('123');
//
//if (1 == 2) {
//    $e = \Nyos\mod\parsing_xml1c::parsing_xml_1s($db, $vv['now_inf_cfg']['folder'] ?? '');
//    \f\pa($e);
//}

// echo \f\timer_stop(12);
// if (!empty($_REQUEST['scan_1s'])) {
// if (1 == 1) {
if ( !empty($_REQUEST['scan']) ) {

    // require __DIR__.'/../class.php';

    \f\pa('старт');
//    exit;

    \f\timer_start(223);

    $e = \Nyos\mod\parsing_xml1c::parsingXmlImport($db, $vv['now_inf_cfg']['folder'] ?? '');
    \f\pa($e,'','','$e');

    echo '<br/>#' . __LINE__ . ' старт';

    \f\pa( \f\end3( '12',true,[\f\timer_stop(223, 'ar'), $e['status'], $e ] ) );
    // exit;

    if (!empty($e['status']) && $e['status'] == 'ok') {

//$e['data'][cats_new] => 2
//$e['data'][cats_in_xml] => 212
//$e['data'][cats_in_db] => 0
//$e['data'][items_colvo] => 9611

        $msg = 'Обработали файл данных'
                . PHP_EOL . 'каталогов: ' . $e['data']['cats_in_xml']
                . PHP_EOL . ' то ва ров : ' . $e['data']['items_colvo']
                . PHP_EOL . 'комп. времени: ' . \f\timer_stop(223);

        \nyos\Msg::sendTelegramm($msg, null, 2);

        // \f\redirect('/', 'index.php', $_GET);
    }

     die('1');
}
