$(document).ready(function(){

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
        login: {
            ru: 'Войти',
            en: 'Sign in',
        },
        placeholder: {
            login: {
                ru: 'логин',
                en: 'login',
            },
            password: {
                ru: 'пароль',
                en: 'password',
            },
            email: {
                ru: 'e-mail',
                en: 'e-mail',
            },
            checkword: {
                ru: 'контрольная строка',
                en: 'checkword',
            },
            passwordConfirm: {
                ru: 'пароль',
                en: 'password',
            },
        }
    };

        render();

    $(document.body).on('click', '.login-popup-link.login-popup-return-auth', function () {
        render();
    });

    function render() {

        renderBtnSignIn();
        renderPlaceholders();
    }

    function renderBtnSignIn() {

        var target = $('.login-popup .login-popup-checbox-block + .login-popup-forget-pas');

        if (target.length === 0)
        {
            return;
        }

        if ($('#gbxtp_atmc_loginform_submit').length !== 0)
        {
            return;
        }
        if ($('#gbxtp_atmc_loginform_preloader').length !== 0)
        {
            return;
        }

        $('<span id="gbxtp_atmc_loginform_submit">'+ dictionary.login[languageId] +'</span>').insertBefore(target);

        $('<img id="gbxtp_atmc_loginform_preloader" src="/bitrix/themes/.default/icons/gbxtp.atmc/preloader.gif">').insertBefore('#gbxtp_atmc_loginform_submit');


        $(document.body).on('click', '#gbxtp_atmc_loginform_submit', function () {
            $('.login-btn-green').click();
        });
    }

    function renderPlaceholders() {
        // Add placeholders
        var collectionI = [
            {
                el: 'input[name="USER_LOGIN"]',
                key: 'login'
            },
            {
                el: 'input[name="USER_PASSWORD"]',
                key: 'password'
            },
            {
                el: 'input[name="USER_EMAIL"]',
                key: 'email'
            },
            {
                el: 'input[name="USER_CHECKWORD"]',
                key: 'checkword'
            },
            {
                el: 'input[name="USER_CONFIRM_PASSWORD"]',
                key: 'passwordConfirm'
            },
        ];

        $(collectionI).each(function (index, item) {
            if (item.el.length === 0)
            {
                return;
            }

            $(item.el).attr('placeholder', dictionary.placeholder[item.key][languageId]);
        });
    }
});