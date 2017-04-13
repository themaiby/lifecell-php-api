# lifecell API from mobile application "MyLifecell"

Набор API методов на PHP для получения информации по номеру и суперпаролю оператора <b>lifecell</b>.<br>
<b>lifecell</b> - украинский оператор мобильной связи.

# Использование
```
include ("api.php");
```

## Авторизация

Авторизоваться могут только те абоненты, номера которых обслуживаются на предоплаченной форме обслуживания. Корпоративные и индивидульные контракты НЕ поддерживаются.

Вход осуществляется с помощью метода <b>signIn</b>, в который передаётся номер в формате <b>380х3ххххххх</b> и шестизначный пароль.
Возвращает уникальный <b>token</b>, который действует ограниченное время.
В дальнейшем обращение ко всем остальным методам выполняется по номеру - <b>$msisdn</b>, и токену - <b>$token</b>.

## Пример ответа - Метод getSummaryData

Возвращает общую информацию по номеру в XML-формате:

```
{"method"=>"getSummaryData",
 "responseCode"=>"0",
 "subscriber"=>
  {"attribute"=>
    [{"name"=>"ICCID", "content"=>"864355xxxxxxxxxxxxxx"},
     {"name"=>"PUK", "content"=>"12345678"},
     {"name"=>"PUK2", "content"=>"87654321"},
     {"name"=>"PIN2", "content"=>"1234"},
     {"name"=>"IMSI", "content"=>"412522xxxxxxxxx"},
     {"name"=>"PIN", "content"=>"4321"},
     {"name"=>"LINE_STATE", "content"=>"ACT/STD"},
     {"name"=>"USE_COMMON_MAIN", "content"=>"false"},
     {"name"=>"LINE_ACTIVATION_DATE", "content"=>"2008-04-04T19:15:22.254+02:00"},
     {"name"=>"LANGUAGE_ID", "content"=>"ru"},
     {"name"=>"DEVICE_NAME", "content"=>"SM-G935F"},
     {"name"=>"LINE_SUSPEND_DATE", "content"=>"2017-12-12T00:00:00+02:00"}],
   "balance"=>
    [{"code"=>"Line_Main", "amount"=>"251.62"},
     {"code"=>"Line_Bonus", "amount"=>"15.00"},
     {"code"=>"Line_Debt", "amount"=>"0.00"}],
   "bundleFreeMigration"=>{"amount"=>"0"},
   "tariff"=>{"code"=>"IND_PRE_3G_FREEDOM_M", "name"=>"3G+ СВОБОДА M"}}}
```
### Остальные методы

* callMeBack - запрос на перезвон от другого абонента
* changeLanguage - смена языка
* changeSuperPassword - смена суперпароля
* changeTariff - смена тарифа (метод не описан, но должен принимать аргументы $oldTariffCode, $newTariffCode)
* getAvailableTariffs - получить список доступных тарифов
* getBalances - получить все балансы по номеру, даже по прошлым тарифам
* getExpensesSummary - сводка расходов
* getLanguages - доступные языки
* getPaymentsHistory - история платежей за определённый период
* getServices - список доступных услуг для подключения
* getSummaryData - общая сводка по номеру
* getToken - получение токена по SubscriberID ($subId) и номеру телефона ($msisdn)
* refillBalanceByScratchCard - пополнение счёта скретч-картой
* removeFromPreProcessing - отключение услуги, которая ждёт внесения денег на счёт (принимает параметр SERVICE_CODE)
* requestBalanceTransfer - запрос перевода баланса
* signIn - авторизация
* signOut - выход (токен становится недоступный, НО не всегда!)
* transferBalance - перевод баланса (не реализован)
* getUIProperties - unknown

## Отправка запросов и получение ответов - httpful.phar

В данном примере была использована библиотека httpful.phar, позволяющая без лишних телодвижений отправлять запросы на сервер в любом формате и таким же образом их получать.
Причина использования: лень.

