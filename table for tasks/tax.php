<?php

use \Bitrix\Main\Loader;
use \Bitrix\Crm\DealTable;
use \Bitrix\Crm\CompanyTable;

// Считываем данные из поля документа
$documentService = $this->workflow->GetService("DocumentService");
$document = $documentService->getDocument($this->getDocumentId());
$rootActivity = $this->GetRootActivity();
$currency = $rootActivity->GetVariable("Currency");
$elementID = $rootActivity->GetVariable("elementID");

$arFields = array(
    'myCompany' => $document['PROPERTY_S_KAKOY_ORGANIZATSII'],
);

$finTable = array(
    'Pay1' => array(
        'cash' => $document['PROPERTY_SUMMA_1_PLATEZHA'],
        'cash_to_pay' => $document['PROPERTY_SUMMA_K_OPLATE_1'],
        'date' => $document['PROPERTY_DATA_1_PLATEZHA'],
        'type' => $document['PROPERTY_TIP_1_PLATEZHA'],
    ),
    'Pay2' => array(
        'cash' => $document['PROPERTY_SUMMA_2'],
        'cash_to_pay' => $document['PROPERTY_SUMMA_K_OPLATE_2'],
        'date' => $document['PROPERTY_DATA_2'],
        'type' => $document['PROPERTY_TIP_2_'],
    ),
    'Pay3' => array(
        'cash' => $document['PROPERTY_SUMMA_3'],
        'cash_to_pay' => $document['PROPERTY_SUMMA_K_OPLATE_3'],
        'date' => $document['PROPERTY_DATA_3'],
        'type' => $document['PROPERTY_TIP_3_'],
    ),
    'Pay4' => array(
        'cash' => $document['PROPERTY_SUMMA_4'],
        'cash_to_pay' => $document['PROPERTY_SUMMA_K_OPLATE_4'],
        'date' => $document['PROPERTY_DATA_4'],
        'type' => $document['PROPERTY_TIP_4_'],
    ),
    'Pay5' => array(
        'cash' => $document['PROPERTY_SUMMA_5'],
        'cash_to_pay' => $document['PROPERTY_SUMMA_K_OPLATE_5'],
        'date' => $document['PROPERTY_DATA_5'],
        'type' => $document['PROPERTY_TIP_5_'],
    ),
    'Pay6' => array(
        'cash' => $document['PROPERTY_SUMMA_6'],
        'cash_to_pay' => $document['PROPERTY_SUMMA_K_OPLATE_6'],
        'date' => $document['PROPERTY_DATA_6'],
        'type' => $document['PROPERTY_TIP_6_'],
    ),
    'Pay7' => array(
        'cash' => $document['PROPERTY_SUMMA_7'],
        'cash_to_pay' => $document['PROPERTY_SUMMA_K_OPLATE_7'],
        'date' => $document['PROPERTY_DATA_7'],
        'type' => $document['PROPERTY_TIP_7_'],
    )
);

$entryTable = '';

$totalCash = 0;
$totalCashToPay = 0;
$transh = 0;

$fields = array(
    'cashFlow' => 'налоги',
    'plan' => '',
    'note3' => 0,
    'inCurrency' => '',
    'dealBitrix' => $arFields['payFor'],
    'recipient' => '',
    'currencyValue' => 0
);

foreach ($finTable as $pay) {
    $totalCash = $totalCash + $pay['cash'];
    $totalCashToPay = $totalCashToPay + $pay['cash_to_pay'];
}

$fields['note3'] = $totalCashToPay;

foreach ($finTable as $pay) {

    $fields['plan'] = $pay['cash'];

    $fields['inCurrency'] = $pay['cash'];

    if ($pay['cash_to_pay']) {
        $transh++;
        $entryTable = $entryTable . "[TR][TD]" . $arFields['myCompany'] . "[/TD]
            [TD]" . $fields['cashFlow'] . "[/TD]
            [TD][/TD]
            [TD]" . $fields['plan'] . "[/TD]
            [TD][/TD]
            [TD]" . $pay['payDate'] . "[/TD]
            [TD]" . $pay['payDate'] . "[/TD]
            [TD]безнал[/TD]
            [TD][/TD]
            [TD][/TD]
            [TD][/TD]
            [TD]" . $pay['type'] . "[/TD]
            [TD]" . "KZT" . "[/TD]
            [TD]" . $fields['note3'] . "[/TD]
            [TD][/TD][/TR]";
    }
}

// Передаем данные в переменную БП
$this->SetVariable('EntryInTable', $entryTable);