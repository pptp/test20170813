<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Transaction */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', [
        'model' => $searchModel,
        'totalCount' => $dataProvider->totalCount
    ]); ?>

    <div>Total USD: <strong><?=$sum['changeUSD']?></strong></div>
    <div>Total in customer currency: <strong><?=$sum['changeCustom']?></strong></div>
    <hr />

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'changeUSD',
            'changeCustom',
            'datetime',
        ],
    ]); ?>
</div>
