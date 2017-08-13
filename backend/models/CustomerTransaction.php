<?php

namespace backend\models;

/*
 * The model extends Transaction model but specified to 
 *  operate with data of transactions of certain customer
*/
class CustomerTransaction extends Transaction {
    public $changeUSD;
    public $changeCustom;
}
