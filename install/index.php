<?php

use Bitrix\Main\Localization\Loc;

use Bitrix\Main\ModuleManager;

use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\SystemException;

use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;


class gbxtp_atmc extends CModule
{
    public $MODULE_ID = 'gbxtp.atmc';

    public $MODULE_VERSION      = null;
    public $MODULE_VERSION_DATE = null;

    public $MODULE_NAME         = null;
    public $MODULE_DESCRIPTION  = null;

    public $PARTNER_NAME = null;
    public $PARTNER_URI  = null;

    private $PATH_TO_DIR_INSTALL = null;

    public function __construct()
    {
        $pF     = str_replace('\\', '/', __FILE__);
        $ptDi   = substr($pF, 0, strrpos($pF, '/'));

        $pI     = $ptDi .'/version.php';

                                    $arModuleVersion = [

                                    ];

                                    if (file_exists($pI))
                                    {
                                        include $pI;
                                    }

        $this->PATH_TO_DIR_INSTALL = $ptDi;

        $this->MODULE_VERSION 	   = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

                                     Loc::loadMessages(__FILE__);

        $this->MODULE_NAME 		   = Loc::getMessage("GBXTP_ATMC_INSTALL_MODULE_NAME");
        $this->MODULE_DESCRIPTION  = Loc::getMessage("GBXTP_ATMC_INSTALL_MODULE_DESCRIPTION");

        $this->PARTNER_NAME 	   = Loc::getMessage("GBXTP_ATMC_INSTALL_PARTNER_NAME");
        $this->PARTNER_URI  	   = Loc::getMessage("GBXTP_ATMC_INSTALL_PARTNER_URI");


        return false;
    }

    public function DoInstall()
    {
        if(CheckVersion(ModuleManager::getVersion("main"), "14.00.00"))
        {
            $this->InstallFiles();
            $this->InstallDB();

            ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallEvents();
        }
        else
        {
            throw new SystemException(Loc::getMessage("GBXTP_ATMC_INDEX_DOINSTALL_THROWEXCEPTION"));
        }

        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();

        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            $this->PATH_TO_DIR_INSTALL .'/css/',
            Application::getDocumentRoot() .'/bitrix/css',
            true,
            true
        );

        CopyDirFiles(
            $this->PATH_TO_DIR_INSTALL .'/js/',
            Application::getDocumentRoot() .'/bitrix/js',
            true,
            true
        );

        CopyDirFiles(
            $this->PATH_TO_DIR_INSTALL .'/themes/icons',
            Application::getDocumentRoot() .'/bitrix/themes/.default/icons',
            true,
            true
        );

        return true;
    }

    public function UnInstallFiles()
    {
        Directory::deleteDirectory(
            Application::getDocumentRoot(). '/bitrix/css/'. $this->MODULE_ID
        );

        Directory::deleteDirectory(
            Application::getDocumentRoot(). '/bitrix/js/'. $this->MODULE_ID
        );

        Directory::deleteDirectory(
            Application::getDocumentRoot(). '/bitrix/themes/.default/icons/'. $this->MODULE_ID
        );

        Directory::deleteDirectory(
            Application::getDocumentRoot(). '/bitrix/css/'. $this->MODULE_ID .'/vendor'
        );

        Directory::deleteDirectory(
            Application::getDocumentRoot(). '/bitrix/js/'. $this->MODULE_ID .'/vendor'
        );

        return true;
    }

    public function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(
            "main",
            "OnBeforeEndBufferContent",
            $this->MODULE_ID,
            "Gbxtp\Atmc\Main",
            "run",
            100
        );

        return true;
    }

    public function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            "main",
            "OnBeforeEndBufferContent",
            $this->MODULE_ID,
            "Gbxtp\Atmc\Main",
            "run",
            100
        );

        return true;
    }

    public function InstallDB()
    {
                 $dL = $this->getDbListOption();

        foreach ($dL as $namedL => $datadL)
        {
            Option::set($this->MODULE_ID, str_replace('"', '', $namedL), $datadL['VALUE_DEFAULT'], null);
        }

        return true;
    }

    public function UnInstallDB()
    {
                 $dL = $this->getDbListOption();

        foreach ($dL as $namedL => $datadL)
        {
            Option::delete($this->MODULE_ID, [
                "name"    => str_replace('"', '', $namedL),
                "site_id" => null
            ]);
        }

        return true;
    }

    private function getDbListOption()
    {
                    $pIDb = dirname($this->PATH_TO_DIR_INSTALL) .'/lib/Db.php';

        if (file_exists($pIDb))
        {
            include $pIDb;
        }

        $dLE = Gbxtp\Atmc\Db::$listOptionNameEditable;
        $dLC = Gbxtp\Atmc\Db::$listOptionNameCalculated;

        return array_merge($dLE, $dLC);
    }
}
