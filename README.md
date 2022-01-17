# Aspire code challange

## Require

Docker & docker-compose

## Start API

```
docker-compose build
docker-compose up -d
```

Laravel applicaton will serve at http://localhost:8080

## Database migration and seeder
```
docker-compose exec laravel php artisan migrate
docker-compose exec laravel php artisan db:seed
```

## Postman collection file

```
Aspire.postman_collection.json
```

## API

* Login [POST]: Login API with email and password. See users were created at UserPermissionSeeder
* Me [GET]: Get current user detail
* Terms [GET]: List all terms in system
* Terms [POST]: Create a new loan term with data
    ```
        "apr": 52, // Annual percentage rate: 52% per year
        "length": 20, // 20 weeks
        "interest_type": 1, // Type 0 is Non-Amortized Loan. 1 is Amortized Loan
        "fee": 1000 // Fee for per term. Charge only 1 time
    ```
* Contract [POST]: User apply to a Loan term with an amount
* Contract status [GET]: Show detail of needed repayment infomation
    ```
        "debtAmount": 50000, // Debt amount of this contract
        "repaymentAmount": 2000, // Repayment amount needed
        "fee": 10, // Fee for this contract
        "interest": 10 // Interst to now calculated base on interest_type is 1 or 0
    ```
* Contract repayment history [GET]: Show all repayment of contracty
* Submit repayment [POST]: Submit a repayment to a contract
   ```
    "contract_id": 1, // Contract ID
    "amount": 73 // Amount needed for a weekly repayment, get amount from Contract status API
   ```
   `After submit a repayment, use Contract Status to see the next repayment`
