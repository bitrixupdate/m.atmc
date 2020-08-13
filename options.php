<?php

use Bitrix\Main\Localization\Loc;
use	Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

use Gbxtp\Atmc\CreateAsset;
use Gbxtp\Atmc\FormHandler;
use Gbxtp\Atmc\Main;


$request = HttpApplication::getInstance()->getContext()->getRequest();

                      $idModule = 'gbxtp.atmc';

Loader::includeModule($idModule);


$tabs = [
    [
        "DIV" 	  => "edit",
        "TAB" 	  => Loc::getMessage("GBXTP_ATMC_OPTIONS_TAB_NAME"),
        "TITLE"   => Loc::getMessage("GBXTP_ATMC_OPTIONS_TAB_NAME"),
        "OPTIONS" => [
            Loc::getMessage("GBXTP_ATMC_OPTIONS_TAB_APPEARANCE_1"),
                [
                    "color_1",
                    Loc::getMessage("GBXTP_ATMC_OPTIONS_TAB_COLOR_1"),
                    "#222425",
                    "text",
                    'color_picker_spectrum_1',
                    'html' => [
                        [
                            'input',
                            '',
                            'button',
                            '...',
                            'spectrum-bitrix input-button',
                        ],
                    ]
                ],

            Loc::getMessage("GBXTP_ATMC_OPTIONS_TAB_APPEARANCE_2"),
                [
                    "color_2",
                    Loc::getMessage("GBXTP_ATMC_OPTIONS_TAB_COLOR_2"),
                    "#222425",
                    "text",
                    'color_picker_spectrum_2',
                    'html' => [
                        [
                            'input',
                            '',
                            'button',
                            '...',
                            'spectrum-bitrix input-button',
                        ],
                    ]
                ],
        ]
    ]
];

if($request->isPost() && check_bitrix_sessid())
{
            $listOptionValue = [];

    foreach($tabs as $tab)
    {
        foreach($tab["OPTIONS"] as $option)
        {
            if(!is_array($option))
            {
                continue;
            }

            if(!empty($request["apply"]))
            {
                $optionValue = $request->getPost($option[0]);
            }

            if(!empty($request["default"]))
            {
                $optionValue = $option[2];
            }

                $isValidColor = preg_match('/^((0x){0,1}|#{0,1})([0-9A-F]{8}|[0-9A-F]{6})$/i', $optionValue);

            if ($isValidColor)
            {
                echo CAdminMessage::ShowNote(
                    str_replace('#FIELD_NAME',$option[1], Loc::GetMessage("GBXTP_ATMC_OPTIONS_MESSAGE_1_SUCCESS"))
                );
            }

            if (empty($isValidColor) && !empty($optionValue))
            {
                echo CAdminMessage::ShowMessage(
                    str_replace('#FIELD_NAME',$option[1], Loc::GetMessage("GBXTP_ATMC_OPTIONS_MESSAGE_1_ERROR"))
                );
                continue;
            }

            if (empty($optionValue))
            {
                echo CAdminMessage::ShowMessage(
                    str_replace('#FIELD_NAME',$option[1], Loc::GetMessage("GBXTP_ATMC_OPTIONS_MESSAGE_2_ERROR"))
                );
                continue;
            }

            Option::set($idModule, $option[0], $optionValue, null);

            $listOptionValue[] = $optionValue;
        }
    }

                                      $optionIrtColor1rgbaMenuMain = FormHandler::hex2rgba($listOptionValue[0], 0.5);

            Option::set($idModule, 'color_1_rgba_menu_main', $optionIrtColor1rgbaMenuMain, null);

                 $listOptionValue[] = $optionIrtColor1rgbaMenuMain;

        if(count($listOptionValue) == 3)
        {
            CreateAsset::create($listOptionValue);
        }
}




$tabControl = new CAdminTabControl('tabControl', $tabs);

$tabControl->Begin();

?>
    <form method="post" action="<?=$request->getRequestedPage();?>?mid=<?=htmlspecialcharsbx($idModule);?>&lang=<?=LANGUAGE_ID;?>" class="gbxtp_atmc_form">

        <?
        foreach($tabs as $tab)
        {
            if($tab["OPTIONS"])
            {
                $tabControl->BeginNextTab();

                foreach($tab["OPTIONS"] as $option)
                {
                    if(!is_array($option))
                    {
                        ?>
                        <tr class="heading">
                            <td colspan="2"><?=$option?></td>
                        </tr>
                        <?
                        continue;
                    }

                        $val = Option::get($idModule, $option[0], $option[2], null);

                        ?>
                        <tr class="">
                            <td width="50%" class="adm-detail-content-cell-l">
                                <?=$option[1]?>
                            </td>
                            <td width="50%" class="adm-detail-content-cell-r">
                                <input type="<?=$option[3]?>" size="" maxlength="255" value="<?=htmlspecialcharsbx($val)?>" name="<?=$option[0]?>" id="<?=$option[4]?>">

                                <?foreach ($option['html'] as $optionHtml):?>
                                <?
                                    if(empty($optionHtml[1]))
                                    {
                                        ?>
                                        <<?=$optionHtml[0]?> type="<?=$optionHtml[2]?>" value="<?=$optionHtml[3]?>" class="<?=$optionHtml[4]?>" />
                                        <?
                                    }
                                    else
                                    {

                                    }
                                ?>
                                <?endforeach;?>
                            </td>
                        </tr>
                        <?
                }
            }
        }
                $tabControl->Buttons();
        ?>



        <input type="submit" name="apply"   value="<?=Loc::GetMessage("GBXTP_ATMC_OPTIONS_INPUT_APPLY");?>"/>
        <input type="submit" name="default" value="<?=Loc::GetMessage("GBXTP_ATMC_OPTIONS_INPUT_DEFAULT");?>"/>

        <?=bitrix_sessid_post();?>

    </form>
<?

$tabControl->End();


