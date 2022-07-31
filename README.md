# Commission Calculator

This is project runs on PHP 8.1.* and Laravel 9.*

### Create .env
```bash 
    1.  change .env.example to ".env".

    2.  put a csv file into commission_calcualtor_p/public folder and named it "transactions.csv".
```
You can also modify the below things according to the client's requirements on .env
```bash 
CSV_FILE_PATH="transactions.csv"
WITHDRAW_FEE_FOR_BUSINESS_USER=0.5
WITHDRAW_FEE_FOR_PRIVATE_USER=0.3
DEPOSITE_FEE=0.03
CHARGE_FREE_WITHDRAW_TRANSACTION_PER_WEEK=3
CHARGE_FREE_WITHDRAW_AMOUNT_PER_WEEK=1000
BASE_CURRENCY=EUR
CURRENCY_EXCHANGE_API="https://developers.paysera.com/tasks/api/currency-exchange-rates"
```

### Composer Update :
```bash
composer update
```

### System run :
```bash
php artisan serve
```

### Browse Url
```bash
http://127.0.0.1:8000/
```

### Test :
All the tests can be accessed form <**commission_calcualtor_p/tests**>
#### Tests run : 
```bash
php artisan test
```
