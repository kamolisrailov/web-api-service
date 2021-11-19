Как видите, это полуживой веб апи сервис.

Я в нем реализовал 3 метода: 
GetInformation
PerformTransaction.
CheckTransaction(с заглушкой)

Тестирование проводилось на Postman.

Методы, как я понимаю, начинается с маленькой авторизации, то есть проверки username и password.
Авторизационные данные: 
username: clickuser
password: !QAZxsw2 (хешируется через sha512)

------------------------GetInformation---------------------------------------start
Дальше отправка параметров.
Для метода GetInformation есть два типа параметров,

1. Проверка баланса и лимита через телефон
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:uws="http://yourhost/">
    <soapenv:Header/>
    <soapenv:Body>
        <uws:GetInformationArguments>
                <password>!QAZxsw2</password>
                <username>clickuser</username>
            <parameters>
                <paramKey>phone_number</paramKey>
                <paramValue>998946201611</paramValue>
            </parameters>
            <serviceId>1</serviceId>
        </uws:GetInformationArguments>
    </soapenv:Body>
</soapenv:Envelope>
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




2. Проверка баланса и лимита через номер карты.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:uws="http://yourhost/">
    <soapenv:Header/>
    <soapenv:Body>
        <uws:GetInformationArguments>
                <password>!QAZxsw2</password>
                <username>clickuser</username>
            <parameters>
                <paramKey>card_number</paramKey>
                <paramValue>99913</paramValue>
            </parameters>
            <serviceId>1</serviceId>
        </uws:GetInformationArguments>
    </soapenv:Body>
</soapenv:Envelope>
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Кстати про виртуальную карту. Я понял так: карта состоит из 5 цифр 99913. Если это некорректно, прошу прощения.

Проверка на ошибок:
Проверка подлинности карты по алгоритму луна.
Проверяется длина карты и существование в бд.

------------------------GetInformation---------------------------------------end






------------------------------------------PerformTransaction---------------------------------------------------start
В этом методе реализован отправка по номеру карты получателя и по номеру телефона получателя.
Отправитель может только через карту.
карта отправителя:99796
карта получателя:99804


Проведение транзакии через виртуального кошелька получателя.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:uws="http://yourhost/">
   <soapenv:Header/>
   <soapenv:Body>
      <uws:PerformTransactionArguments>
         <password>!QAZxsw2</password>
         <username>clickuser</username>
         <amount>70000</amount>
         <!--Zero or more repetitions:-->
         <parameters>
            <paramKey>sender_card</paramKey>
            <paramValue>99796</paramValue>
         </parameters>
          <parameters>
            <paramKey>recipient_card</paramKey>
            <paramValue>99804</paramValue>
         </parameters>
         <serviceId>2</serviceId>
         <transactionId>{{$randomInt}}</transactionId>
         <transactionTime>{{$isoTimestamp}}</transactionTime>
      </uws:PerformTransactionArguments>
   </soapenv:Body>
</soapenv:Envelope>
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







Проведение транзакии через номера телефона получателя.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:uws="http://yourhost/">
   <soapenv:Header/>
   <soapenv:Body>
      <uws:PerformTransactionArguments>
         <password>!QAZxsw2</password>
         <username>clickuser</username>
         <amount>70000</amount>
         <!--Zero or more repetitions:-->
         <parameters>
            <paramKey>sender_card</paramKey>
            <paramValue>99796</paramValue>
         </parameters>
          <parameters>
            <paramKey>recipient_phone</paramKey>
            <paramValue>998946201612</paramValue>
         </parameters>
         <serviceId>2</serviceId>
         <transactionId>{{$randomInt}}</transactionId>
         <transactionTime>{{$isoTimestamp}}</transactionTime>
      </uws:PerformTransactionArguments>
   </soapenv:Body>
</soapenv:Envelope>
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Метод проверяет правильность карт, их существование в базе, сумма не превышает ли лимита и хватает ли баланс отправителя.


------------------------------------------PerformTransaction---------------------------------------------------












Вот и все, если есть какие то вопросы или замечания, то я на связи)
+998946201611
tg: @israilovk