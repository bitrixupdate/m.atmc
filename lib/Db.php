<?php


namespace Gbxtp\Atmc;


use Gbxtp\Atmc\Main;

use Bitrix\Main\Application;


class Db
{
    private static $tableNameOption = 'b_option';

    public static $listOptionNameEditable = [
        '"color_1"' => [
            'VALUE_DEFAULT' => '#222425'
        ],
        '"color_2"' => [
            'VALUE_DEFAULT' => '#222425'
        ],
    ];

    public static $listOptionNameCalculated = [
        '"color_1_rgba_menu_main"'=> [
            'VALUE_DEFAULT' => 'rgba(34,36,37,0.5)'
        ],
    ];

    public static function getEditableOptionValues()
    {
        $lfNIm = implode(', ', array_keys(self::$listOptionNameEditable));

        $c = Application::getConnection();

        $sql = 'SELECT NAME, VALUE 
                FROM '. self::$tableNameOption .' 
                WHERE MODULE_ID="'. Main::MODULE_ID .'" AND NAME IN ('. $lfNIm .')';

        $r = $c->query($sql);

            $result = [];

        while ($rI = $r->fetch())
        {
            $result[$rI['NAME']] = $rI;
        }

        return $result;
    }
}