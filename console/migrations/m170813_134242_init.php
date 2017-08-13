<?php

use yii\db\Migration;

class m170813_134242_init extends Migration
{
    public function safeUp()
    {
        $sqls = [
            'CREATE TABLE `migration` (
              `version` varchar(180) NOT NULL,
              `apply_time` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;',

            'CREATE TABLE `tb_cities` (
              `id` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `countryId` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',

            'CREATE TABLE `tb_countries` (
              `id` int(11) NOT NULL,
              `name` varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',

            'CREATE TABLE `tb_currencies` (
              `id` int(11) NOT NULL,
              `name` varchar(255) DEFAULT NULL,
              `alias` varchar(3) NOT NULL,
              `rate` decimal(20,10) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',

            'CREATE TABLE `tb_customers` (
              `id` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `cityId` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',

            'CREATE TABLE `tb_rates` (
              `id` int(11) NOT NULL,
              `date` date NOT NULL,
              `currencyId` int(11) NOT NULL,
              `rate` decimal(20,10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',

            'CREATE TABLE `tb_transactions` (
              `id` int(11) NOT NULL,
              `senderId` int(11) DEFAULT NULL,
              `receiverId` int(11) NOT NULL,
              `senderSum` decimal(20,10) DEFAULT NULL,
              `receiverSum` decimal(20,10) NOT NULL,
              `sum` decimal(20,10) NOT NULL,
              `datetime` datetime NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',

            'CREATE TABLE `tb_wallets` (
              `id` int(11) NOT NULL,
              `customerId` int(11) NOT NULL,
              `currencyId` int(11) NOT NULL,
              `balance` decimal(20,10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',


            'ALTER TABLE `migration`
              ADD PRIMARY KEY (`version`);',

            'ALTER TABLE `tb_cities`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `name_2` (`name`,`countryId`),
              ADD KEY `countryId` (`countryId`),
              ADD KEY `name` (`name`);',

            'ALTER TABLE `tb_countries`
                          ADD PRIMARY KEY (`id`),
                          ADD UNIQUE KEY `name` (`name`) USING BTREE;',

            'ALTER TABLE `tb_currencies`
                          ADD PRIMARY KEY (`id`),
                          ADD UNIQUE KEY `alias` (`alias`);',

            'ALTER TABLE `tb_customers`
                          ADD PRIMARY KEY (`id`),
                          ADD UNIQUE KEY `name` (`name`),
                          ADD KEY `city` (`cityId`);',

            'ALTER TABLE `tb_rates`
                          ADD PRIMARY KEY (`id`),
                          ADD UNIQUE KEY `date_2` (`date`,`currencyId`),
                          ADD KEY `date` (`date`),
                          ADD KEY `currencyId` (`currencyId`);',

            'ALTER TABLE `tb_transactions`
                          ADD PRIMARY KEY (`id`),
                          ADD KEY `senderId` (`senderId`),
                          ADD KEY `receiverId` (`receiverId`),
                          ADD KEY `datetime` (`datetime`);',

            'ALTER TABLE `tb_wallets`
                          ADD PRIMARY KEY (`id`),
                          ADD KEY `clientId` (`customerId`),
                          ADD KEY `currencyId` (`currencyId`);',


            'ALTER TABLE `tb_cities`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',

            'ALTER TABLE `tb_countries`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
            
            'ALTER TABLE `tb_currencies`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',

            'ALTER TABLE `tb_customers`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',

            'ALTER TABLE `tb_rates`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',

            'ALTER TABLE `tb_transactions`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',

            'ALTER TABLE `tb_wallets`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',


            'ALTER TABLE `tb_cities`
                          ADD CONSTRAINT `tb_cities_ibfk_1` FOREIGN KEY (`countryId`) REFERENCES `tb_countries` (`id`);',


            'ALTER TABLE `tb_customers`
                          ADD CONSTRAINT `tb_customers_ibfk_1` FOREIGN KEY (`cityId`) REFERENCES `tb_cities` (`id`);',


            'ALTER TABLE `tb_rates`
                          ADD CONSTRAINT `tb_rates_ibfk_1` FOREIGN KEY (`currencyId`) REFERENCES `tb_currencies` (`id`);',


            'ALTER TABLE `tb_transactions`
                          ADD CONSTRAINT `tb_transactions_ibfk_1` FOREIGN KEY (`senderId`) REFERENCES `tb_wallets` (`id`),
                          ADD CONSTRAINT `tb_transactions_ibfk_2` FOREIGN KEY (`receiverId`) REFERENCES `tb_wallets` (`id`);',


            'ALTER TABLE `tb_wallets`
                          ADD CONSTRAINT `tb_wallets_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `tb_customers` (`id`),
                          ADD CONSTRAINT `tb_wallets_ibfk_2` FOREIGN KEY (`currencyId`) REFERENCES `tb_currencies` (`id`);',
        ];
    }

    public function safeDown()
    {
        echo "m170813_134242_init cannot be reverted.\n";

        return false;
    }

}
