<?php

namespace Gbxtp\Atmc;


use CAdminMessage;

use Bitrix\Main\Localization\Loc;

use Bitrix\Main\Application;
use Bitrix\Main\IO\File;

use Bitrix\Main\IO\IoException;
use Bitrix\Main\IO\FileNotFoundException;
use Bitrix\Main\IO\FileOpenException;

use Gbxtp\Atmc\Main;


class CreateAsset
{
    const PATH_TO_ADMIN_CSS_TEMPLATE  = '/bitrix/css/'. Main::MODULE_ID .'/admin.css.template';
    const PATH_TO_PUBLIC_CSS_TEMPLATE = '/bitrix/css/'. Main::MODULE_ID .'/public.css.template';

    const PATH_TO_ADMIN_CSS_CUSTOM    = '/bitrix/css/'. Main::MODULE_ID .'/custom/admin.css';
    const PATH_TO_PUBLIC_CSS_CUSTOM   = '/bitrix/css/'. Main::MODULE_ID .'/custom/public.css';

    public static function create($listOptionValue)
    {
        self::createAdminCss($listOptionValue);
        self::createPublicCss($listOptionValue);
    }

    private static function writeFile($p)
    {
        $pathToTemplate = $p['PATH_TO_TEMPLATE'];
        $pathToTarget   = $p['PATH_TO_TARGET'];

        $listPatternSearch = $p['LIST_PATTERN_SEARCH'];
        $listReplaceValue  = $p['LIST_REPLACE_VALUE'];

        try
        {
            $s = File::getFileContents(Application::getDocumentRoot() . $pathToTemplate);
        }
        catch (FileNotFoundException $exception)
        {
                                           $p = $exception->getPath();

            echo CAdminMessage::ShowMessage(
                str_replace('#STR', $p, Loc::GetMessage("GBXTP_ATMC_OPTIONS_CREATE_ASSET_MESSAGE_1_ERROR"))
            );
            return;
        }
        catch (FileOpenException $exception)
        {
                                           $p = $exception->getPath();

            echo CAdminMessage::ShowMessage(
                str_replace('#STR', $p, Loc::GetMessage("GBXTP_ATMC_OPTIONS_CREATE_ASSET_MESSAGE_4_ERROR"))
            );
            return;
        }

        $sU = str_replace(
            $listPatternSearch,
            $listReplaceValue,
            $s
        );

        try
        {
            $r = File::putFileContents(Application::getDocumentRoot() . $pathToTarget, $sU);
        }
        catch (IoException $exception)
        {
            $p = $exception->getPath();

            echo CAdminMessage::ShowMessage(
                str_replace('#STR', $p, Loc::GetMessage("GBXTP_ATMC_OPTIONS_CREATE_ASSET_MESSAGE_2_ERROR"))
            );
            return;
        }

        if (!$r)
        {
            echo CAdminMessage::ShowMessage(
                str_replace('#STR', $pathToTarget, Loc::GetMessage("GBXTP_ATMC_OPTIONS_CREATE_ASSET_MESSAGE_2_ERROR"))
            );
            return;
        }
    }

    private static function createAdminCss($listOptionValue)
    {
        self::writeFile([
            'PATH_TO_TEMPLATE'      => self::PATH_TO_ADMIN_CSS_TEMPLATE,
            'PATH_TO_TARGET'        => self::PATH_TO_ADMIN_CSS_CUSTOM,

            'LIST_PATTERN_SEARCH'   => [
                '#COLOR_ADMIN_ADMIN_BG',
                '#COLOR_ADMIN_LOGIN_BG',
                '#COLOR_ADMIN_1_RGBA_MENU_MAIN',
            ],
            'LIST_REPLACE_VALUE'    => $listOptionValue,
        ]);
    }

    private static function createPublicCss($listOptionValue)
    {
        self::writeFile([
            'PATH_TO_TEMPLATE'      => self::PATH_TO_PUBLIC_CSS_TEMPLATE,
            'PATH_TO_TARGET'        => self::PATH_TO_PUBLIC_CSS_CUSTOM,

            'LIST_PATTERN_SEARCH'   => [
                '#COLOR_PUBLIC_BG',
                '#PLUG',
            ],
            'LIST_REPLACE_VALUE'    => $listOptionValue,
        ]);
    }
}