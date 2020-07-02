<?php

/**
  класс модуля
 * */

namespace Nyos\mod;

if (!defined('IN_NYOS_PROJECT'))
    throw new \Exception('Сработала защита от розовых хакеров, обратитесь к администрратору');

class parsing_xml1c {

    public static $mod_cats = '020.cats';
    public static $mod_items = '021.items';

//    public static $dir_img_server = false;
//
//    public static function creatFolderImage($folder) {
//        /**
//
//         * */
//    }

    /**
     * парсинг и запись данных из файла xml
     * @param type $db
     * @param type $folder
     * @param type $mod_cats
     * @param type $mod_items
     * @return type
     */
    public static function parsingXmlImport($db, $folder = null, $mod_cats = '020.cats', $mod_items = '021.items') {

        if (isset($_REQUEST['clear'])) {
            \Nyos\mod\items::deleteFromDops($db, self::$mod_cats);
            \Nyos\mod\items::deleteFromDops($db, self::$mod_items);
        } else {

            \f\timer_start(223);

            $res = self::scanNewDataFile($db, $folder);
            \f\pa($res, 2, '', 'res self::scanNewDataFile');

            if (isset($_REQUEST['show']))
                echo '<br/>step 1 : ' . \f\timer_stop(223);

            /**
             * если не пустой массив с каталогами, то сравниваем и добавляем
             */
            if (isset($_REQUEST['show']))
                echo '<br/>#' . __LINE__ . ' + cat';
            if (isset($_REQUEST['show']))
                echo '<div style="border: 1px solid red; padding: 15px; margin: 15px;" >';

            \f\timer_start(2);

            // добавляем каталоги
            if (1 == 1) {

                // \f\pa($cats_in, 2);
                $cats_now = \Nyos\mod\items::get($db, self::$mod_cats);
                if (isset($_REQUEST['show']))
                    \f\pa($cats_now, 2, '', 'cats now');

                $different = self::differentArray($cats_now, $res['data']['cats']);
                if (isset($_REQUEST['show']))
                    \f\pa($different, 2, '', '$different cat');

                $link_cat = [];
                foreach ($cats_now as $cat) {
                    if (!isset($link_cat[$cat['a_id']]))
                        $link_cat[$cat['a_id']] = $cat['id'];
                }
                if (isset($_REQUEST['show']))
                    \f\pa($link_cat, 2, '', '$link_cat');

                $new_cats = [];
                foreach ($different['new'] as $c) {
                    if (isset($c['a_parentId'])) {
                        if (isset($link_cat[$c['a_parentId']])) {
                            $c['up_id'] = $link_cat[$c['a_parentId']];
                            $new_cats[] = $c;
                        }
                    } else {
                        $new_cats[] = $c;
                    }
                }

                if (isset($_REQUEST['show']))
                    \f\pa($new_cats, 2, '', '$new_cats');

                \Nyos\mod\items::addNewSimples($db, self::$mod_cats, $new_cats);

                unset($new_cats, $cats_now);
            }

            if (isset($_REQUEST['show']))
                echo '<br/>timer : ' . \f\timer_stop(2);

            // return [ 'cats' => $cats_now, 'diff' => $different ];
            // \f\pa($res2, 2, '', 'res self::differentCats');
            if (isset($_REQUEST['show']))
                echo '</div>';

            echo '<br/>добавляем итемы';

            $items_old = \Nyos\mod\items::get($db, self::$mod_items);
            \f\pa($items_old, 2, '', '$items_old');
            //\f\pa($res['data']['items'], 2, '', '$res[data][items]');

            $diff_items = self::differentArray($items_old, $res['data']['items']);
            \f\pa($diff_items, 2, '', '$diff_items');

            \f\pa($link_cat, 2, '', '$link_cat');


            $nn = 0;
            $in_db = [];
            foreach ($diff_items['new'] as $item) {
                if (!empty($item['a_categoryId'])) {
                    if (!empty($link_cat[$item['a_categoryId']])) {

                        if ($nn >= 300)
                            break;

                        $nn++;

                        $item['cat_id'] = $link_cat[$item['a_categoryId']];
                        if( !empty($item['a_catNumber']) )
                        $item['catNumber_search'] = \f\translit($item['a_catNumber'], 'cifru_bukvu');
                        $in_db[] = $item;
                    }
                }
            }

            \Nyos\mod\items::addNewSimples($db, self::$mod_items, $in_db);

            echo '<br/>#' . __LINE__ . ' добавлено товаров ' . sizeof($in_db);

            // \f\pa($re, 2, '', '$re');
        }


//            $nn = 0;
//
//            $new_item = [];
//            foreach ($res3['new'] as $item) {
//                if (isset($link_cat[$item['a_categoryId']])) {
//                    if ($nn > 500)
//                        break;
//                    $item['cat_id'] = $link_cat[$item['a_categoryId']];
//                    $new_item[] = $item;
//                    $nn++;
//                }
//            }
//
//            \f\pa($new_item, 2, '', '$new_item');
//
//            echo '<br/>step 3 : ' . \f\timer_stop(223);
//
//            \Nyos\mod\items::addNewSimples($db, self::$mod_items, $new_item);
//
//            echo '<br/>step 4 add : ' . \f\timer_stop(223);
        // $link_cat
//            $new_i = [];
//        foreach( $res3['new'] as $item ){
//            $item['a_categoryId'] = 
//            $new_i
//        }
        // }
        // \Nyos\mod\items::addNewSimples($db, self::$mod_cats, $cats_in);

        return true;



        // echo \f\timer_stop(334);
        //   exit;
        // 
        // \f\pa($cats,2);
//        echo '<br/>#' . __LINE__;
//        return \f\end3('+ папки');

        if (isset($est_xml_file) && $est_xml_file === true) {

            if (3 == 4)
                rename($sc . $file, $sc . $file . '.' . date('Y-m-d_h-i-s') . '.old.xml');

            // \f\pa($cats, 2);

            /**
             * записываем каталоги которых нет в базе
             */
            $ww = \Nyos\mod\items::deleteFromDops($db, $mod_cats);

            $cats_now = \Nyos\mod\items::get($db, $mod_cats);

            $new_cats = self::differentCats($cats_now, $cats);
            \f\pa($new_cats, '', '', 'new_cats');

            // exit;
            // \Nyos\mod\items::addNewSimples($db, $mod_cats, $new_cats['new']);



            exit;



//        \Nyos\mod\items::saveNewDop($db, $new_cats['edit'] );
            // \f\pa($cats, 2);
            // $we = \Nyos\mod\items::get($db, $mod_cats);
            // \f\pa($we, 2, '', 'we');

            $ww = \Nyos\mod\items::deleteFromDops($db, $mod_items);

            $items_now = \Nyos\mod\items::get($db, $mod_items);
            $new_items = self::differentItems($items_now, $items);
            // \f\pa($new_items, '', '', 'new_items');

            $ar_cat_catid_dbid = [];
            $cats2 = \Nyos\mod\items::get($db, $mod_cats);
            // \f\pa($cats2);
            foreach ($cats2 as $k => $v) {
                $ar_cat_catid_dbid[$v['a_id']] = $v['id'];
            }

            unset($cats2);

            $items2 = [];

            foreach ($new_items['new'] as $k => $v) {

                if (isset($ar_cat_catid_dbid[$v['a_categoryId']]))
                    $v['cat_id'] = $ar_cat_catid_dbid[$v['a_categoryId']];

                $items2[] = $v;
            }

            unset($ar_cat_catid_dbid, $new_items['new']);

            \Nyos\mod\items::addNewSimples($db, $mod_items, $items2);

//        \Nyos\mod\items::saveNewDop($db, $new_items['edit'] );

            if (1 == 2) {

                $ar_catId_normId = [];

                foreach ($we as $k => $v) {

                    $ar_catId_normId[$v['a_id']] = $v['id'];
                }

                //\f\pa($ar_catId_normId);
                //exit;
                // echo '<br/>#'.__LINE__;
//        $nn = 1;
//
//        foreach ($we as $k => $v) {
//
//            if ($nn > 2)
//                break;
//
//            \f\pa($v);
//            $nn++;
//        }

                $now_items0 = \Nyos\mod\items::get($db, $mod_items);
                $now_items = [];
                foreach ($now_items0 as $k1 => $v1) {
                    $now_items[$v1['a_id']] = $v1;
                }
                unset($now_items0);
                // \f\pa($items);

                $items1 = [];

                foreach ($items as $k => $v) {

                    /**
                     * сравнение данных +
                     */
                    // если есть уже такой id товара то не добавляем
                    if (isset($now_items[$v['a_id']]))
                        continue;

                    /**
                     * сравнение данных -
                     */
                    if (!empty($v['a_categoryId']) && !empty($ar_catId_normId[$v['a_categoryId']]))
                        $v['cat_id'] = $ar_catId_normId[$v['a_categoryId']];

                    $items1[] = $v;
                }

                unset($items);
                // \f\pa($items1);
                // exit;
                // \Nyos\mod\items::deleteFromDops($db, $mod_items);
                \Nyos\mod\items::addNewSimples($db, $mod_items, $items1);
                // \f\pa($items, 2);

                $we = \Nyos\mod\items::get($db, $mod_items);
                // \f\pa($we, 2, '', 'we items');
//        $nn = 1;
//        foreach ($we as $k => $v) {
//
//            if ($nn > 2)
//                break;
//
//            \f\pa($v);
//
//            $nn++;
//        }
            }

            return \f\end3('файл обработали', true, [
                'cats_new' => sizeof($new_cats),
                'cats_in_xml' => sizeof($cats),
                'cats_in_db' => sizeof($cats_now),
//                'cats' => sort_cats($cats),
//                'cats_all' => $cats,
//                'items' => $items,
                'items_colvo' => sizeof($items),
//                '123' => 123
            ]);
            // break;
        }

        // echo '<br/>'.\f\timer_stop(334);
        // exit;

        return \f\end3('нет файла данных', false);
    }

    /**
     * сканим новый файл данных
     * @param type $db
     * @return json
     */
    public static function scanNewDataFile($db, $folder) {

        echo '<br/>#' . __LINE__ . ' scanNewDaFile';

        \f\timer_start(789);

        $sc = DR . dir_site_sd . (!empty($folder) ? $folder . '/' : '' );

        if (!is_dir($sc))
            throw new Exception('нет папки', 1);

        $cats = $items = [];

        // сканим папку с файлами и ищем новый
        if (1 == 1) {

            $sc_scan = scandir($sc);

            foreach ($sc_scan as $k => $file) {

                if (strpos($file, '.old.') !== false)
                    continue;

                if (strpos($file, '.xml') !== false) {

//                    \f\pa($sc . $file);
//                    continue;

                    $est_xml_file = true;

                    // echo '<br/>#' . __LINE__ . ' ' . $sc . $file;

                    $reader = new \XMLReader();

                    if (!$reader->open($sc . $file))
                        return \f\end3('Failed to open ' . $sc . $file, false);

                    $d = ['id' => 0, 'parentId' => 0, 'name' => 'head'];
                    $d_item = ['id' => 0, 'categoryId' => 0, 'price' => 0, 'in_stock' => 0];

                    $cats = [];
                    $items = [];

                    while ($reader->read()) {

                        if ($reader->nodeType == \XMLReader::ELEMENT && $reader->name == 'category') {

                            $d1 = [];
                            $node = (array) new \SimpleXMLElement($reader->readOuterXML());

                            if (!empty($node['@attributes']))
                                foreach ($node['@attributes'] as $k1 => $v1) {
                                    if (!empty($v1)) {
                                        if ($k1 == 'name') {
                                            $d1['head'] = $v1;
                                            // echo '<br/>'.$v1;
                                        } else {
                                            $d1['a_' . $k1] = $v1;
                                        }
                                    }
                                }
                            $cats[] = $d1;
                        }
                        //
                        elseif ($reader->nodeType == \XMLReader::ELEMENT && $reader->name == 'item') {

                            $d1 = [];

                            $node = (array) new \SimpleXMLElement($reader->readOuterXML());

                            if (!empty($node['name'])) {
                                $d1['head'] = $node['name'];

                                if (!empty($node['@attributes']))
                                    foreach ($node['@attributes'] as $k1 => $v1) {
                                        if (!empty($v1))
                                            $d1['a_' . $k1] = $v1;
                                    }
                            }

                            $items[] = $d1;
                        }
                    }

                    // echo '<br/>#' . __LINE__;

                    $reader->close();
                    break;
                }
            }

//            \f\pa([
//                'cats' => $cats,
//                    // 'items' => $items 
//                    ], 2, '', 'cat items 0');
//            \f\pa([
//                // 'cats' => $cats, 
//                'items' => $items
//                    ], 2, '', 'item items' 0);
        }

        return \f\end3('обработ', true,
                [
                    'cats' => $cats ?? [],
                    'items' => $items ?? [],
                    'time' => \f\timer_stop(789)
                ]
        );
    }

    /**
     * сравниваем 2 массива каталогов и выводим новые и отредактированные данные
     * @param type $ar_now
     * @param type $ar_in
     * @return type
     */
    public static function differentCats($db, $cats_in) {

        // \f\pa($cats_in, 2);
        $cats_now = \Nyos\mod\items::get($db, self::$mod_cats);
        // \f\pa($cats_now, 2,'','cats now');
        $different = self::differentArray($cats_now, $cats_in);

        return ['cats' => $cats_now, 'diff' => $different];



        // \Nyos\mod\items::addNewSimples($db, self::$mod_cats, );
        // \Nyos\mod\items::addNewSimples($db, self::$mod_cats, $cats_in);


        $cats2 = [];
        foreach ($cats_now as $v) {
            $cats2[$v['a_id']] = $v;
        }
        // unset($cats_now);

        echo '<br/>#' . __LINE__;

        $cats = [];
        foreach ($cats_in as $v) {

//            if( in_array( [ 'a_id' => $v['a_id'] ], $cats_now ) ){
//                echo '<br/>#'.__LINE__;
//            }

            if (!isset($cats2[$v['a_id']]))
                echo '<br/>#' . __LINE__;

            // $cats[$v['a_id']] = $v;
        }
        unset($cats_in);


        return;







        // \f\pa($ar_in, 2, '', 'ar_in');
        // \f\pa($ar_now, 2, '', 'ar_now');
        // exit;

        $now = [];
        foreach ($ar_now as $k => $v) {
            $now[$v['a_id']] = $v;
        }

        // unset($ar_now);
        // \f\pa($items);

        $return = [
            'new' => []
            , 'edit' => []
        ];

        foreach ($ar_in as $k => $v) {

            /**
             * сравнение данных +
             */
            // если есть уже такой id товара то не добавляем
            if (isset($now[$v['a_id']])) {

//                \f\pa($now[$v['a_id']]);
//                \f\pa($v);
//                echo '<hr>';

                continue;
            }

            /**
             * сравнение данных -
             */
//            if ( !empty($now[$v['a_id']]) )
//                $v['cat_id'] = $now[$v['a_id']];

            $return['new'][] = $v;
        }

        return $return;
    }

    public static function differentArray($old = [], $new = []) {

//function compare_by_area($a, $b) {
//    $areaA = $a->width * $a->height;
//    $areaB = $b->width * $b->height;
//    
//    if ($areaA < $areaB) {
//        return -1;
//    } elseif ($areaA > $areaB) {
//        return 1;
//    } else {
//        return 0;
//    }
//}
//
//print_r(array_udiff($array1, $array2, 'compare_by_area'));        

        $return = ['new' => [], 'edit' => []];

        $old2 = [];
        if (!empty($old))
            foreach ($old as $v) {
                $old2[$v['a_id']] = 1;
            }

//        $nn = 0;

        if (!empty($new))
            foreach ($new as $v) {

//                if ($nn >= 300)
//                    break;
//                $nn++;

                if (!isset($old2[$v['a_id']])) {
                    $return['new'][] = $v;
                }
            }

        return $return;
    }

    public static function differentItems($db, $items_in) {

        // \Nyos\mod\items::addNewSimples($db, self::$mod_cats, );
        // \f\pa($items_in, 2, '',' $items_in ');
        // \Nyos\mod\items::addNewSimples($db, self::$mod_cats, $cats_in);

        $items_old = \Nyos\mod\items::get($db, self::$mod_items);
        // \f\pa($cats_now, 2);
//        \f\pa($items_old, 2, '', '$items_old');
//        \f\pa($items_in, 2, '', '$items_in');

        $different = self::differentArray($items_old, $items_in);

//        echo '<div style="border:1px solid gray" >';
//        \f\pa($different, 2, '', '$different');
//        echo '</div>';

        return $different;

        $cats2 = [];
        foreach ($cats_now as $v) {
            $cats2[$v['a_id']] = $v;
        }
        // unset($cats_now);

        echo '<br/>#' . __LINE__;

        $cats = [];
        foreach ($cats_in as $v) {

//            if( in_array( [ 'a_id' => $v['a_id'] ], $cats_now ) ){
//                echo '<br/>#'.__LINE__;
//            }

            if (!isset($cats2[$v['a_id']]))
                echo '<br/>#' . __LINE__;

            // $cats[$v['a_id']] = $v;
        }
        unset($cats_in);






        return;







        // \f\pa($ar_in, 2, '', 'ar_in');
        // \f\pa($ar_now, 2, '', 'ar_now');
        // exit;

        $now = [];
        foreach ($ar_now as $k => $v) {
            $now[$v['a_id']] = $v;
        }

        // unset($ar_now);
        // \f\pa($items);

        $return = [
            'new' => []
            , 'edit' => []
        ];

        foreach ($ar_in as $k => $v) {

            /**
             * сравнение данных +
             */
            // если есть уже такой id товара то не добавляем
            if (isset($now[$v['a_id']])) {

//                \f\pa($now[$v['a_id']]);
//                \f\pa($v);
//                echo '<hr>';

                continue;
            }

            /**
             * сравнение данных -
             */
//            if ( !empty($now[$v['a_id']]) )
//                $v['cat_id'] = $now[$v['a_id']];

            $return['new'][] = $v;
        }

        return $return;
    }

    /**
     * сравниваем 2 массива каталогов и выводим новые и отредактированные данные
     * @param type $ar_now
     * @param type $ar_in
     * @return type
     */
    public static function differentItems_old($ar_now, $ar_in) {

        // \f\pa($ar_in, 2, '', 'ar_in');
        // \f\pa($ar_now, 2, '', 'ar_now');
        // exit;

        $now = [];
        foreach ($ar_now as $k => $v) {
            $now[$v['a_id']] = $v;
        }

        // unset($ar_now);
        // \f\pa($items);

        $return = [
            'new' => []
            , 'edit' => []
        ];

        foreach ($ar_in as $k => $v) {

            /**
             * сравнение данных +
             */
            // если есть уже такой id товара то не добавляем
            if (isset($now[$v['a_id']])) {

//                \f\pa($now[$v['a_id']]);
//                \f\pa($v);
//                echo '<hr>';

                continue;
            }

            /**
             * сравнение данных -
             */
//            if ( !empty($now[$v['a_id']]) )
//                $v['cat_id'] = $now[$v['a_id']];

            $return['new'][] = $v;
        }

        return $return;
    }

}
