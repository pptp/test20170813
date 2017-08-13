<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use backend\models\CustomerTransaction as CustomerTransactionModel;

use backend\models\Customer;
use backend\models\Wallet;
/**
 * CustomerTransaction represents the model behind the search form about `backend\models\CustomerTransaction`.
 */
class CustomerTransaction extends CustomerTransactionModel
{
    public $customerName;
    public $startDate;
    public $endDate;

    protected $requiredWalletIds;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerName', 'startDate', 'endDate'], 'string'],
            [['customerName'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /*
        Returns SQL clause '`rw`.`id` = {wallet-id}' or '`rw`.`id` IN ({wallet-id})'
        It is a bad practice and it will be great to fix it as soon as possible
    */
    protected function generateSqlClauseIsReceiver() {
        $requiredWalletIds = $this->requiredWalletIds;

        /*
         * We set intval element of $requiredWalletIds to defend from sqlInjection
         * because we will generate SQL clause by ourselfs.
         * Anyway I believe there is a better method to put "IN" clause in SELECT in Yii2
        */
        $sqlIsReceiverClause = '`rw`.`id`';
        if (count($requiredWalletIds) == 1) {
            /* it will replace in sql id = IN(someId) by id = someId */
            $requiredWalletIds = intval($requiredWalletIds[0]);
            $sqlIsReceiverClause .= ' = ' . $requiredWalletIds;
        } else {
            $requiredWalletIds = array_map(function($i) {
                return intval($i);
            }, $requiredWalletIds);
            $sqlIsReceiverClause .= ' IN (' . implode(',', $requiredWalletIds) . ') ';
        }

        return $sqlIsReceiverClause;
    }

    /* Generate the common query for getting CustomerTransactions */
    protected function generateQuery() {
        $query = CustomerTransactionModel::find();

        $sqlIsReceiverClause = $this->generateSqlClauseIsReceiver();

        $query
            ->select([
                't.id',
                't.datetime',
                new Expression('IF(' . $sqlIsReceiverClause . ', `t`.`sum`, `t`.`sum` * -1) as `changeUSD`'),
                new Expression('IF(' . $sqlIsReceiverClause . ', `t`.`receiverSum`, `t`.`senderSum` * -1) as `changeCustom`')
            ])

            /* Transaction - t */
            ->from(CustomerTransactionModel::tableName() . ' t')

            /* Receiver Wallet - rw */
            ->innerJoin(
                Wallet::tableName() . ' rw',
                'rw.id = t.receiverId'
            )

            /* Sender Wallet - sw */
            ->leftJoin(
                Wallet::tableName() . ' sw',
                'sw.id = t.senderId'
            )
            
            /* "where" with "or" in ActiveQuery is an arrays madness */
            ->andWhere(['or', 
                    ['rw.id' => $this->requiredWalletIds],
                    ['sw.id' => $this->requiredWalletIds]
            ])
            ->andFilterWhere(['>=', 'datetime', $this->startDate])
            ->andFilterWhere(['<=', 'datetime', $this->endDate]);

        return $query;
    }


    public function load($params, $formName = NULL) {
        parent::load($params, $formName);

        // Cache CustomerIDs on each load
        if ($this->customerName) {
            $this->requiredWalletIds = Wallet::findIdsByCustomerName($this->customerName);
        }
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search() {
        if (!$this->validate()) {
            // dummy answer instance of BaseDataProvider. Bad practice
            return new ArrayDataProvider([
                'allModels' => []
            ]);
        }

        $query = $this->generateQuery();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $dataProvider;
    }

    public function query() {
        if (!$this->validate()) {
            // Dummy answer instance of Query. Bad practice
            $query = CustomerTransactionModel::find();
            $query->where('0 = 1');
            return $query;
        }

        $query = $this->generateQuery();
        return $query;
    }

    public function getSum() {
        if (!$this->validate()) {
            // Dummy result the form was not validated. Bad practice
            return [
                'changeUSD' => 0,
                'changeCustom' => 0
            ];
        }

        $sqlIsReceiverClause = $this->generateSqlClauseIsReceiver();

        $query = $this->generateQuery()
            ->limit(null)
            ->select([
                new Expression('SUM(IF(' . $sqlIsReceiverClause . ', `t`.`sum`, `t`.`sum` * -1)) as `changeUSD`'),
                new Expression('SUM(IF(' . $sqlIsReceiverClause . ', `t`.`receiverSum`, `t`.`senderSum` * -1)) as `changeCustom`')
            ]);

        return $query->asArray()->one();
    }

    /*  Returns query which fetchs min and max date */
    public function getDateBoundaries() {
        if (!$this->validate()) {
            // Dummy result the form was not validated. Bad practice
            return [
                'min' => 0,
                'max' => 0
            ];
        }

        $query = $this->generateQuery()
            ->select([
                new Expression('MIN(datetime) as min'),
                new Expression('MAX(datetime) as max')
            ]);

        return $query->asArray()->one();
    }
}
