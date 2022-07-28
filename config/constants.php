<?php

return [ 
    "CSV_FILE_PATH" => env("CSV_FILE_PATH"),

    "WITHDRAW_FEE_FOR_BUSINESS_USER" => 0.5,
    "WITHDRAW_FEE_FOR_PRIVATE_USER" => 0.3,
    "DEPOSITE_FEE" => 0.03,
    
    "DEPOSITE" => "deposit",
    "WITHDRAW" => "withdraw",

    "USER_PRIVATE" => "private",
    "USER_BUSINESS" => "business",

    "OPERATION_DATE" => 0,
    "OPERATION_USER_IDENTITY" => 1,
    "OPERATION_USER_TYPE" => 2,
    "OPERATION_TYPE" => 3,
    "OPERATION_AMOUNT" => 4,
    "OPERATION_CURRENCY" => 5,

];