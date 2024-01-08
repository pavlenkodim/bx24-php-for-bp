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
    'payFor' => $document['PROPERTY_ZA_CHTO_PLATIM'],
    'myCompany' => $document['PROPERTY_NASHA_KOMPANIYA_ORGANIZATSIYA_PLATELSHCHIK'],
    'companyInvoice' => $document['PROPERTY_FIRMA_VYSTAVLYAYUSHCHAYA_SCHET_NA_OPLATU'],
    'deal' => $document['PROPERTY_SDELKA_DLYA_KOTOROY_PRIOBRETAETSYA_TOVAR'],
    'pay1cash' => $document['PROPERTY_SUMMA_PERVOY_OPLATY'],
    'pay1date' => $document['PROPERTY_DATA_PERVOY_OPLATY'],
    'pay2cash' => $document['PROPERTY_SUMMA_VTOROY_OPLATY'],
    'pay2date' => $document['PROPERTY_DATA_VTOROY_OPLATY'],
    'pay3cash' => $document['PROPERTY_SUMMA_TRETEY_OPLATY'],
    'pay3date' => $document['PROPERTY_DATA_TRETEY_OPLATY'],
    'pay4cash' => $document['PROPERTY_SUMMA_CHETVYERTOY_OPLATY'],
    'pay4date' => $document['PROPERTY_DATA_CHETVERTOY_OPLATY'],
    'pay5cash' => $document['PROPERTY_SUMMA_PYATOY_OPLATY'],
    'pay5date' => $document['PROPERTY_DATA_PYATOY_OPLATY'],
);

$fields = array(
    'plan' => '',
    'note3' => 0,
    'inCurrency' => '',
    'dealBitrix' => $arFields['payFor'],
    'recipient' => '',
    'currencyValue' => 0
);

if (is_array($arFields['companyInvoice'])) {
    foreach ($arFields['companyInvoice'] as $id) {
        $company = CompanyTable::getById($id)->fetch();
        $companyName = $company['TITLE'];
        $companyUrl = '[url=https://' . $_SERVER['SERVER_NAME'] . '/crm/company/details/' . $company['ID'] . '/]' . $companyName . '[/url]';
        $fields['recipient'] = $companyUrl;
    }
} else {
    $company = CompanyTable::getById($arFields['companyInvoice'])->fetch();
    $companyName = $company['TITLE'];
    $companyUrl = '[url=https://' . $_SERVER['SERVER_NAME'] . '/crm/company/details/' . $company['ID'] . '/]' . $companyName . '[/url]';
    $fields['recipient'] = $companyUrl;
}

$myCompany = '';
$dealID = 0;

foreach ($arFields['myCompany'] as $mC) {
    $myCompany = $mC;
}

if ($arFields['deal']) {
    $dealUrl = '';
    if (Loader::includeModule('crm')) {
        if (is_array($arFields['deal'])) {
            foreach ($arFields['deal'] as $id) {
                $deal = DealTable::getByID($id)->fetch();
                $dealTitle = $deal['TITLE'];
                $dealUrl = "[url=" . $_SERVER['SERVER_NAME'] . "/crm/deal/details/" . $id . "/]" . $dealTitle . "[/url]";
            }
        } else {
            $deal = DealTable::getByID($arFields['deal'])->fetch();
            $dealTitle = $deal['TITLE'];
            $dealUrl = "[url=" . $_SERVER['SERVER_NAME'] . "/crm/deal/details/" . $arFields['deal'] . "/]" . $dealTitle . "[/url]";
        }
    }
    $fields['dealBitrix'] = $dealUrl;
}

$date = date('d.m.Y');

if ($currency != 'KZT') {
    $url = 'https://nationalbank.kz/rss/get_rates.cfm';

    $options = array(
        'fdate' => $date,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($options));

    $response = curl_exec($ch);
    curl_close($ch);

    $result_xml = new SimpleXMLElement($response);

    foreach ($result_xml->item as $item) {
        if ($item->title == $currency) {
            $fullname = $item->fullname;
            $title = $item->title;
            $description = +$item->description;
            $fields['currencyValue'] = $description;
        }
    }
} else {
    $title = "KZT";
    $fields['currencyValue'] = 1;
}

$entryTable = '';

$finTable = array(
    'Pay1' => array(
        'cash' => $arFields['pay1cash'],
        'payDate' => $arFields['pay1date']
    ),
    'Pay2' => array(
        'cash' => $arFields['pay2cash'],
        'payDate' => $arFields['pay2date']
    ),
    'Pay3' => array(
        'cash' => $arFields['pay3cash'],
        'payDate' => $arFields['pay3date']
    ),
    'Pay4' => array(
        'cash' => $arFields['pay4cash'],
        'payDate' => $arFields['pay4date']
    ),
    'Pay5' => array(
        'cash' => $arFields['pay5cash'],
        'payDate' => $arFields['pay5date']
    ),
);

$totalCash = 0;
$transh = 0;

foreach ($finTable as $pay) {
    $totalCash = $totalCash + $pay['cash'];
}

$fields['note3'] = $totalCash;

foreach ($finTable as $pay) {
    if ($currency == 'KZT') {
        $fields['plan'] = $pay['cash'];
    } else {
        $fields['plan'] = ($pay['cash'] * $fields['currencyValue']) * 1.1;
    }
    $fields['inCurrency'] = $pay['cash'];

    if ($pay['cash']) {
        $transh++;
        $entryTable = $entryTable . "[TR][TD]" . $myCompany . "[/TD]
            [TD][/TD]
            [TD][/TD]
            [TD]" . $fields['plan'] . "[/TD]
            [TD]" . $fields['currencyValue'] . "[/TD]
            [TD]" . $pay['payDate'] . "[/TD]
            [TD]" . $pay['payDate'] . "[/TD]
            [TD]безнал[/TD]
            [TD][/TD]
            [TD]" . $fields['dealBitrix'] . "[/TD]
            [TD]Транш " . $transh . "[/TD]
            [TD]" . $fields['recipient'] . "[/TD]
            [TD]" . $currency . "[/TD]
            [TD]" . $fields['note3'] . "[/TD]
            [TD]" . $fields['inCurrency'] . "[/TD][/TR]";
    }
}

// Передаем данные в переменную БП
$this->SetVariable('EntryInTable', $entryTable);