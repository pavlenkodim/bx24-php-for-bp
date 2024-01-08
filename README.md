# PHP for Buisness Process


## Getting started

Make scripts for buisness process in Bitrix24.

## Usage
Use this scripts for your Business process.

## Support
If there were problems   
Write/Call to   
Extension number: [1033](tel:1033)   
WhatsApp, Telegram, phone number: [+7 (705) 559-81-46](tel:+77055598146)
E-mail: [admin3@triline.kz](mailto:admin3@triline.kz)   
Bitrix24:  [Павленко Дмитрий Евгеньевич](https://bitrix.triline.kz/company/personal/user/1194/)

## Roadmap

- [table for tasks](table for tasks) - use in BP "Закуп товара и услуг (безнал)" and "Налог".
- [vacation_holiday](vacation_holiday) - use in BP "Заявление на отгул/Компенсацию отпуска".

## Memo

Bitrix24 official resources
- [BB codes](https://helpdesk.bitrix24.ru/open/6060589/)
- [Data modification](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=57&LESSON_ID=12407&LESSON_PATH=5442.5446.5059.12407)
- [Expression calculator functions](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=57&LESSON_ID=4912)

Links create  

- BP document: [url={{Константы глобальные: ДоменСайта}}/bizproc/processes/{{ID информационного блока}}/element/0/{{ID элемента}}/?list_section_id=]{{Название}}[/url]   
- BP task: [url={{Константы глобальные: ДоменСайта}}/company/personal/bizproc/{=Workflow:ID}/]Ссылка для просмотра задания[/url]   
- Task: [url={{Константы глобальные: ДоменСайта}}/company/personal/user/{=GlobalConst:Director1 > int}/tasks/task/view/{=A14363_11761_87521_1324:TaskId}/]Название задачи[/url]   
- Deal: [url={{Константы глобальные: ДоменСайта}}/crm/deal/details/{{ID}}/]{{Название}}[/url]
- Company: [url={{Константы глобальные: ДоменСайта}}/crm/company/show/{{Компания}}/]{{Компания: Название компании}}[/url]   


DateTime 18h   
1. Date now: {{=dateadd({=System:Date},"18h")}}
2. Date element modify: {{=dateadd({{Дата изменения элемента > date}},"18h")}}
3. Add in field "ДатаТест" (type: Дата): {{=dateadd({{ДатаТест}},"18h")}}

Task deadline
1. Add workdays (time): {{=workdateadd({=GlobalVar:DateTime},"+1d")}}
2. Add calendar days (time): {{=dateadd({=GlobalVar:DateTime},"+1d")}}
3. Add in field "ДатаТест" (type: Дата): {{=dateadd({{ДатаТест}},"18h")}}
4. Add in field "ДатаВремяТест" (type: Дата/Время): {{=dateadd({{ДатаВремяТест > date}},"18h")}}


Additional information in the file [memo.txt](memo.txt).

## Authors
Author: Pavlenko Dmitry  
E-mail: [admin3@triline.kz](mailto:admin3@triline.kz)

## License
This project is distributed under the [MIT license](LICENCE). We invite you to participate and contribute your ideas!

## Project status
In developing.
