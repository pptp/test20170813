<?php

namespace backend\controllers;

use Yii;
use backend\models\CustomerTransaction;
use backend\models\search\CustomerTransaction as CustomerTransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TransactionsController implements the CRUD actions for CustomerTransaction model.
 */
class TransactionsController extends Controller
{
    /**
     * Lists all CustomerTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* Get Params */
        $params = Yii::$app->request->queryParams;

        $searchModel = new CustomerTransactionSearch();
        $searchModel->load($params);

        $dataProvider = $searchModel->search();

        $sum = $searchModel->getSum();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sum' => $sum
        ]);
    }

    /* Download CSV file by link. We get filters in GET-request */
    public function actionDownload() {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=file.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        $params = Yii::$app->request->queryParams;

        
        $searchModel = new CustomerTransactionSearch();
        $searchModel->load($params);
        if (!$searchModel->validate()) {
            return;
        }

        $query = $searchModel->query();

        /*
            Transactions CANNOT be changed or removed;
            So we can fetch them without database transaction (which decrease performance a little bit).
            But here we are faced with a problem - what if some
                transaction will be added while we are reading
            To prevent it we can add conditions for our query:
                to add additions boundaries by time
        */

        /* At first we have to determine time boundaries */
        $dateBoundaries = $searchModel->getDateBoundaries();

        /* And add them to our main query */
        $query
            ->andWhere(['>=', 'datetime', $dateBoundaries['min']])
            ->andWhere(['<=', 'datetime', $dateBoundaries['max']]);

        /* we need not ActiveRecord here. Just raw data from DB */
        $query = $query->asArray();

        /* Let us write responce directly in the output stream */
        $output = fopen('php://output', 'w');

        $pageSize = 20;
        $pageNo = 0;

        /* if it is the first row - we have to write csv headers */
        $first = true;
        while ($rows = $query->limit($pageSize)->offset($pageSize * $pageNo)->all()) {
            foreach ($rows as &$row) {
                /* write csv headers */
                if ($first) {
                    $first = false;
                    fputcsv($output, array_keys($row));
                }

                /* write new row in csv */
                fputcsv($output, $row);
            }
            /* do not forget to unlink */
            unset($row);

            /* go to the next page */
            $pageNo++;
        }
        fclose($output);
    }

    /**
     * Finds the CustomerTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = CustomerTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
