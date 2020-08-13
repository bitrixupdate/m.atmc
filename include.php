<?

\Bitrix\Main\Loader::registerAutoLoadClasses('gbxtp.atmc', [
    'Gbxtp\Atmc\Main'        => 'lib/Main.php',
    'Gbxtp\Atmc\CreateAsset' => 'lib/CreateAsset.php',

    'Gbxtp\Atmc\FormHandler' => 'lib/FormHandler.php',
    'Gbxtp\Atmc\Db'          => 'lib/Db.php',
]);