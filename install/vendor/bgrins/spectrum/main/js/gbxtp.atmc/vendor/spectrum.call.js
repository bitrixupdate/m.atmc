$(document).ready(function(){

    var optionValues = gbxtp_atmc_options_values;

    var languageId = gbxtp_atmc_language_id;

    switch (languageId) {
        case 'ru':
            //
            break;
        case 'en':
            //
            break;
        default:
            languageId = 'en';
            break;
    }

    var dictionary = {
        chooseText: {
            ru: 'Применить',
            en: 'Apply',
        },
        cancelText: {
            ru: 'Отмена',
            en: 'Cancel',
        }
    };

    $("#color_picker_spectrum_1").spectrum({
        color: optionValues.color_1.VALUE,
        showButtons: false,
        showInput: true,
        showPalette: true,
        palette: [
            ['black', 'white', 'blanchedalmond'],
            ['rgb(255, 128, 0);', 'hsv 100 70 50', 'lightyellow']
        ],
        showInitial: true,

        chooseText: dictionary.chooseText[languageId],
        cancelText: dictionary.cancelText[languageId],

        preferredFormat: "hex",
    });

    $("#color_picker_spectrum_2").spectrum({
        color: optionValues.color_2.VALUE,
        showButtons: false,
        showInput: true,
        showPalette: true,
        palette: [
            ['black', 'white', 'blanchedalmond'],
            ['rgb(255, 128, 0);', 'hsv 100 70 50', 'lightyellow']
        ],
        showInitial: true,

        chooseText: dictionary.chooseText[languageId],
        cancelText: dictionary.cancelText[languageId],

        preferredFormat: "hex",
    });


    $('.spectrum-bitrix.input-button').each(function(index, el){

        $(el).on('click', function(e){
            $(e.target).prev().prev().spectrum("toggle");
            return false;
        });
    });
});