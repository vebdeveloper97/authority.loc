<?php

namespace app\api\modules\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use app\models\Constants;
use yii\httpclient\Client;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use app\modules\base\models\Goods;
use yii\filters\ContentNegotiator;
use app\modules\tikuv\models\TikuvGoodsDocAccepted;
use app\api\modules\v1\components\CorsCustom;
use app\modules\tikuv\models\TikuvGoodsDocPack;

/**
 * Country Controller API
 *
 * @author Omadbek Onorov <omadbek.onorov@gmail.com>
 */
class ProductController extends ActiveController
{
    const POST_DEV_HOST = 'https://devsamo.dataprizma.uz/api/v.1/';
    const POST_PROD_HOST = 'https://devsamo.dataprizma.uz/api/v.1/';

    public $modelClass = 'app\modules\base\models\TikuvGoodsDocPack';

    public $enableCsrfValidation = false;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => CorsCustom::className()
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'pack' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'list' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'total-pack' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'wrapper-item' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                    'accepted' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
                ],
            ],
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @param $getData
     * @return array
     */
    public function conditions($getData)
    {

        $conditions = [];
        $conditions['page'] = 1;
        $conditions['limit'] = 10;
        $conditions['lang'] = 'uz';
        $conditions['sort'] = 'DESC';
        if (!empty($getData)) {
            if (!empty($getData['limit'])) {
                $conditions['limit'] = $getData['limit'];
            }
            if (!empty($getData['page'])) {
                $conditions['page'] = $getData['page'];
            }
            if (!empty($getData['lang'])) {
                $conditions['lang'] = $getData['lang'];
            }
            if (!empty($getData['sort'])) {
                $conditions['sort'] = $getData['sort'];
            }
        }
        return $conditions;
    }

    public function actionReturned(){
        $data = Yii::$app->request->post();
        $productjson = json_encode($data);
        $jsonfile= Yii::getAlias('@webroot/config/aresult.json');
        $fp = fopen($jsonfile, 'w+');
        fwrite($fp, $productjson);
        fclose($fp);
        return true;
    }

    public function actionPack()
    {
        $auth = Yii::$app->request->post();
        $response['status'] = false;
        $response['message'] = "Auth Error";
        $response['data'] = [];
        $response['total'] = 0;
        $response['auth'] = $auth;

        if (isset($auth['user']) && isset($auth['password'])) {
            if ($auth['user'] == Constants::$API_USER && $auth['password'] == Constants::$API_PASSWORD) {
                $getData = $this->conditions(Yii::$app->request->get());
                $offset = ($getData['page'] - 1) * $getData['limit'];
                $sql = "select tgdp.id,
                               tgdp.doc_number,
                               tgdp.reg_date,
                               tgdp.to_department as department,
                               tgdp.model_list_id,
                               tgdp.model_var_id 
                        from tikuv_goods_doc_pack tgdp
                        WHERE tgdp.status = 3 AND tgdp.is_incoming = 2
                        ORDER BY tgdp.id DESC LIMIT :limit OFFSET :offset;";
                $total = Yii::$app->db->createCommand("select COUNT(id) as c from tikuv_goods_doc_pack where status = 3 AND is_incoming = 2;")->queryScalar();
                $machines = Yii::$app->db->createCommand($sql)
                    ->bindValues([
                        'offset' => $offset,
                        'limit' => $getData['limit']
                    ])->queryAll();
                $response = [
                    'status' => true,
                    'message' => 'OK',
                    'data' => $machines,
                    'total' => $total,
                    'auth' => $auth
                ];
            }
        }
        return $response;
    }

    public function actionList()
    {

        $auth = Yii::$app->request->post();
        $flagUser = false;
        $flagId = false;

        if ((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != Constants::$API_USER || $auth['password'] != Constants::$API_PASSWORD) {
            $flagUser = true;
        }
        if (!isset($auth['id']) || empty($auth['id'])) {
            $flagId = true;
        }
        if ($flagUser) {
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        if ($flagId) {
            $response['status'] = false;
            $response['message'] = "Not found package ID";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }

        $modelPack = TikuvGoodsDocPack::findOne($auth['id']);
        if ($modelPack === null) {
            $response['status'] = false;
            $response['message'] = "Invalid Package ID";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        $getData = $this->conditions(Yii::$app->request->get());
        $offset = ($getData['page'] - 1) * $getData['limit'];
        $sql = "select g.name as gname,
                       tgd.quantity,
                       (select SUM(a.quantity) from tikuv_goods_doc_accepted a
                        WHERE a.goods_id = tgd.goods_id
                          AND a.model_list_id = tgdp.model_list_id
                          AND a.model_var_id = tgdp.model_var_id
                        GROUP BY a.goods_id, a.model_list_id, a.model_var_id) as accepted,
                       g.type,
                       ROUND(IF(SUM(gi.quantity) IS NULL,1, SUM(gi.quantity))*
                        (IF(gi2.quantity IS NULL,1, gi2.quantity))*
                        (IF(gi3.quantity IS NULL,1, gi3.quantity))*
                        (IF(gi4.quantity IS NULL,1, gi4.quantity))) as volume,
                       g.id as gid,
                       g.size_collection,
                       g.color_collection,
                       g.barcode,
                       g.model_no,
                       s.name as sizeName,
                       s.code as sizeCode,
                       cp.code as colorCode,
                       cp.name as colorName,
                       tgd.weight,
                       u.name as unitName,
                       u.code as unitCode,
                       tgdp.model_list_id,
                       tgdp.model_var_id,
                       pb.code as currency,
                       mrd.price,
                       ml.long_name,
                       b.name as brandName,
                       b.code as brandCode,
                       mv.code as genderCode,
                       mv.name as genderName,
                       ms.code as seasonCode,
                       ms.name as seasonName,
                       att.path,
                       att.id as photoId
                from tikuv_goods_doc tgd
                         left join tikuv_goods_doc_pack tgdp on tgd.tgdp_id = tgdp.id
                         inner join model_rel_doc mrd on mrd.model_var_id = tgdp.model_var_id
                         left join models_list ml on mrd.model_list_id = ml.id
                         left join brend b on ml.brend_id = b.id
                         left join model_view mv on ml.view_id = mv.id
                         left join model_season ms on ml.model_season = ms.id
                         left join model_rel_attach mra on ml.id = mra.model_list_id
                         left join attachments att on mra.attachment_id = att.id
                         left join pul_birligi pb on mrd.pb_id = pb.id
                
                         left join goods g on tgd.goods_id = g.id
                         left join goods_items gi on gi.parent = g.id
                         left join goods g2 on gi.child = g2.id
                         left join goods_items gi2 on gi2.parent = g2.id
                         left join goods g3 on gi2.child = g3.id
                         left join goods_items gi3 on gi3.parent = g3.id
                         left join goods g4 on gi3.child = g4.id
                         left join goods_items gi4 on gi4.parent = g4.id
                
                         left join unit u on tgd.unit_id = u.id
                         left join size s on g.size = s.id
                         left join color_pantone cp on g.color = cp.id
                WHERE tgdp.id = :packId and
                        mrd.model_list_id = tgdp.model_list_id and
                        mrd.order_id = tgdp.order_id and
                        mrd.order_item_id = tgdp.order_item_id
                  AND tgdp.status = 3 GROUP BY g.id ORDER BY g.name, g.model_no LIMIT :limit OFFSET :offset;";

        $results = Yii::$app->db->createCommand($sql)->bindValues([
            'packId' => $auth['id'],
            'limit' => $getData['limit'],
            'offset' => $offset
        ])->queryAll();
        $totalSql = "select count(tgd.id) from tikuv_goods_doc tgd
                         left join tikuv_goods_doc_pack tgdp on tgd.tgdp_id = tgdp.id
                WHERE tgd.tgdp_id = :packId
                  AND tgdp.status = 3 GROUP BY tgdp.id";
        $total = Yii::$app->db->createCommand($totalSql)
            ->bindValue('packId', $auth['id'])
            ->queryScalar();
        $out = [];
        $samo = Constants::$brandSAMO;
        foreach ($results as $result){
            $out[] = [
                'gid' => $result['gid'],
                'gname' => $result['gname'],
                'sizeCollection' => $result['size_collection'],
                'colorCollection' => $result['color_collection'],
                'volume' => $result['volume'],
                'accepted' => $result['accepted'],
                'type' => $result['type'],
                'barcode' => $result['barcode'],
                'model_no' => $result['model_no'],
                'model_list_id' => $result['model_id'],
                'model_var_id' => $result['model_var'],
                'quantity' => $result['quantity'],
                'sizeName' => $result['size_name'],
                'sizeCode' => $result['size_code'],
                'price' => $result['price'],
                'currency' => $result['currency'],
                'modelInfo' => [
                    'longName' => $result['long_name'],
                    'brandCode' => $result['brandCode'],
                    'brandName' => $result['brandName'],
                    'manufacturerCode' => $samo,
                    'manufacturerName' => $samo,
                    'seasonName' => $result['seasonName'],
                    'seasonCode' => $result['seasonCode'],
                    'genderName' => $result['genderName'],
                    'genderCode' => $result['genderCode'],
                    'mainPhotoId' => $result['photoId'],
                    'colorName' => $result['colorName'],
                    'colorCode' => $result['colorCode'],
                    'photos' => [
                        $result['photoId'] => 'http://'.$_SERVER['HTTP_HOST'].'/'.$result['path'],
                    ]
                ]
            ];
        }
        $response = [
            'status' => true,
            'message' => 'OK',
            'data' => $out,
            'total' => $total,
            'auth' => $auth
        ];
        return $response;
    }


    public function actionTotalPack()
    {

        $auth = Yii::$app->request->post();
        $flagUser = false;

        if ((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != Constants::$API_USER || $auth['password'] != Constants::$API_PASSWORD) {
            $flagUser = true;
        }
        if ($flagUser) {
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        $total = Yii::$app->db->createCommand("select COUNT(id) as c from tikuv_goods_doc_pack where status = 3 AND is_incoming = 2;")->queryScalar();
        $response = [
            'status' => true,
            'message' => 'OK',
            'total' => $total,
            'auth' => $auth
        ];
        return $response;
    }

    public function actionWrapperItem()
    {
        $auth = Yii::$app->request->post();
        $flagUser = false;
        $flagId = false;
        $flagPack = false;
        $flagUrl = false;

        if ((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != Constants::$API_USER || $auth['password'] != Constants::$API_PASSWORD) {
            $flagUser = true;
        }
        if (!isset($auth['gid']) || empty($auth['gid'])) {
            $flagId = true;
        }

        if (!isset($auth['packId']) || empty($auth['packId'])) {
            $flagPack = true;
        }

        if (!isset($auth['url']) || empty($auth['url'])) {
            $flagUrl = true;
        }

        if (!isset($auth['type']) || empty($auth['type'])) {
            $auth['type'] = 4;
        }
        if ($flagUser) {
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        if ($flagUrl) {
            $response['status'] = false;
            $response['message'] = "Not found url";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        if ($flagId) {
            $response['status'] = false;
            $response['message'] = "Not found Goods ID";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        if ($flagPack) {
            $response['status'] = false;
            $response['message'] = "Not found package ID";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }

        $sql = "select g.id as g1_id,
                       g.type as g1_type,
                       gi.quantity as g1_qty,
                       g2.id as g2_id,
                       g2.type as g2_type,
                       gi2.quantity as g2_qty,
                       g3.id as g3_id,
                       g3.type as g3_type, 
                       gi3.quantity as g3_qty,
                       g4.id as g4_id,
                       g4.type as g4_type from goods g
                left join goods_items gi on gi.parent = g.id
                left join goods g2 on g2.id = gi.child
                left join goods_items gi2 on gi2.parent = g2.id
                left join goods g3 on gi2.child = g3.id
                left join goods_items gi3 on gi3.parent = g3.id
                left join goods g4 on gi3.child = g4.id
                left join goods_items gi4 on gi4.parent = g4.id
                where g.id IN (%s)";
        $sql = sprintf($sql,  $auth['gid']);
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        $dataGoods = [];
        foreach ($result as $m) {
            if (!empty($m['g1_id']) && $m['g1_type'] > 1) {
                $qty = $m['g1_qty'];
                if (!empty($m['g2_id']) && $m['g2_type'] > 1) {
                    $qty = $qty * $m['g2_qty'];
                    if (!empty($m['g3_id']) && $m['g3_type'] > 1) {
                        $dataGoods[$m['g4_id'].$m['g1_id']]['parent'] = $m['g1_id'];
                        $dataGoods[$m['g4_id'].$m['g1_id']]['child'] = $m['g4_id'];
                        if (array_key_exists($m['g4_id'].$m['g1_id'], $dataGoods)) {
                            $dataGoods[$m['g4_id'].$m['g1_id']]['qty'] += $qty * $m['g3_qty'];
                        } else {
                            $dataGoods[$m['g4_id'].$m['g1_id']]['qty'] = $qty * $m['g3_qty'];
                        }
                    } else {
                        $dataGoods[$m['g3_id'].$m['g1_id']]['parent'] = $m['g1_id'];
                        $dataGoods[$m['g3_id'].$m['g1_id']]['child'] = $m['g3_id'];
                        if (array_key_exists($m['g3_id'].$m['g1_id'], $dataGoods)) {
                            $dataGoods[$m['g3_id'].$m['g1_id']]['qty'] += $qty;
                        } else {
                            $dataGoods[$m['g3_id'].$m['g1_id']]['qty'] = $qty;
                        }
                    }
                } else {
                    $dataGoods[$m['g2_id'].$m['g1_id']]['parent'] = $m['g1_id'];
                    $dataGoods[$m['g2_id'].$m['g1_id']]['child'] = $m['g2_id'];
                    if (array_key_exists($m['g2_id'].$m['g1_id'], $dataGoods)) {
                        $dataGoods[$m['g2_id'].$m['g1_id']]['qty'] += $m['g2_qty'];
                    } else {
                        $dataGoods[$m['g2_id'].$m['g1_id']]['qty'] = $qty;
                    }
                }
            } else {
                $dataGoods[$m['g1_id'].$m['g1_id']]['parent'] = $m['g1_id'];
                $dataGoods[$m['g1_id'].$m['g1_id']]['child'] = $m['g1_id'];
                if (array_key_exists($m['g1_id'].$m['g1_id'], $dataGoods)) {
                    $dataGoods[$m['g1_id'].$m['g1_id']]['qty'] += $m['g1_qty'];
                } else {
                    $dataGoods[$m['g1_id'].$m['g1_id']]['qty'] = $m['g1_qty'];
                }
            }
        }
        $out1 = [];
        $out1['packId'] = $auth['packId'];
        $out1['items'] = [];
        $goodsId = [];
        $unitList = [
            1 => [
                'code' => 'DONA',
                'name' => 'dona'
            ],
            2 => [
                'code' => 'PAKET',
                'name' => 'paket'
            ],
            3 => [
                'code' => 'BLOK',
                'name' => 'blok'
            ],
            4 => [
                'code' => 'QOP',
                'name' => 'qop'
            ],
        ];
        if (!empty($dataGoods)) {
            $samo = Constants::$brandSAMO;
            $ssd = [];
            foreach ($dataGoods as $id => $item) {
                $ssd[$item['parent']]['gid'] = $item['parent'];
                $sql = "select     g.name,
                                   IF(gb.barcode IS NULL,g.barcode,gb.barcode) as barcode,
                                   g.type, 
                                   g.model_no,
                                   g.model_id,
                                   g.model_var,
                                   s.name  as sizeName,
                                   s.code  as sizeCode,
                                   mrd.price,
                                   pb.code as currency,
                                   ml.long_name,
                                   bc.code as manufacturerCode,
                                   bc.name as manufacturerName,
                                   bc.code as brandCode,
                                   bc.name as brandName,
                                   ms.name as seasonName,
                                   ms.code as seasonCode,
                                   mv.name as genderName, 
                                   mv.code as genderCode,
                                   att.path,
                                   att.id as photoId,
                                   IF(cp.name_ru IS NULL,cp.name,cp.name_ru) as colorName,
                                   cp.code as colorCode,
                                   sn.name as sortName,
                                   sn.code as sortCode,
                                   g.package_code as packageCode,
                                   tgdp.brand_type 
                            from goods g
                                     left join size s on g.size = s.id
                                     left join color_pantone cp on g.color = cp.id
                                     left join tikuv_goods_doc_pack tgdp on tgdp.id = %d
                                     left join barcode_customers bc on tgdp.barcode_customer_id = bc.id
                                     left join goods_barcode gb on g.id = gb.goods_id
                                     left join tikuv_goods_doc tgd on tgdp.id = tgd.tgdp_id
                                     left join sort_name sn on tgd.sort_type_id = sn.id  
                                     inner join model_rel_doc mrd on mrd.model_var_id = tgdp.model_var_id
                                     inner join models_list ml on ml.id = mrd.model_list_id
                                     left join model_view mv on ml.view_id = mv.id
                                     left join model_season ms on ml.model_season = ms.id
                                     left join model_rel_attach mra on ml.id = mra.model_list_id
                                     left join attachments att on mra.attachment_id = att.id
                                     left join pul_birligi pb on mrd.pb_id = pb.id
                            where g.id = %d AND tgdp.model_list_id = mrd.model_list_id AND gb.bc_id = bc.id AND
                                  tgdp.order_item_id = mrd.order_item_id AND tgdp.order_id = mrd.order_id AND mra.is_main = 1
                            GROUP BY g.id LIMIT 1;";
                $sql = sprintf($sql, $auth['packId'], $item['child']);
                $goods = Yii::$app->db->createCommand($sql)->queryOne();
                if (!empty($goods)) {
                    $itemt = [
                        'gid' => $item['child'],
                        'parent_package_id' => $item['parent'],
                        'gname' => $goods['name'],
                        'type' => $goods['type'],
                        'barcode' => $goods['barcode'],
                        'packageCode' => $goods['packageCode'],
                        'model_no' => $goods['model_no'],
                        'model_list_id' => $goods['model_id'],
                        'model_var_id' => $goods['model_var'],
                        'quantity' => $item['qty'],
                        'sizeName' => $goods['sizeName'],
                        'sizeCode' => $goods['sizeCode'],
                        'price' => $goods['price'],
                        'currency' => $goods['currency'],
                        'colorName' => $goods['colorName'],
                        'colorCode' => $goods['colorCode'],
                        'sortCode' => $goods['sortCode'],
                        'sortName' => $goods['sortName'],
                        'unitCode' => $unitList[$goods['type']]['code'],
                        'unitName' => $unitList[$goods['type']]['name'],
                        'modelInfo' => [
                            'longName' => $goods['long_name'],
                            'brandCode' => $goods['brandCode'],
                            'brandName' => $goods['brandName'],
                            'manufacturerCode' => $samo,
                            'manufacturerName' => $samo,
                            'seasonName' => $goods['seasonName'],
                            'seasonCode' => $goods['seasonCode'],
                            'genderName' => $goods['genderName'],
                            'genderCode' => $goods['genderCode'],
                            'mainPhotoId' => $goods['photoId'],
                            'photos' =>  'http://'.$_SERVER['HTTP_HOST'].'/'.$goods['path']
                        ]
                    ];
                    $ssd[$item['parent']]['wrappedItems'][] = $itemt;
                }
            }
            $out1['items'] =  array_values($ssd);
        }
        $out = [
            'user' => Constants::$API_USER,
            'password' => Constants::$API_PASSWORD,
            'data' => $out1
        ];
        $client = new Client();

        $responseClient = $client->createRequest()
            ->setMethod('post')
            ->setUrl($auth['url'])
            ->addHeaders(['content-type' => 'application/json'])
            ->setContent(json_encode($out))
            ->send();
        $response['status'] = false;
        $response['data'] = $responseClient->data;
        if ($responseClient->isOk) {
            $response['statusCode'] = $responseClient->statusCode;
            $response['status'] = true;
        }
        return $response;
    }

    /**
     * @return mixed
     */
    public function actionAccepted()
    {

        $auth = Yii::$app->request->post();
        $flagUser = false;
        $flagItem = false;

        if ((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != Constants::$API_USER || $auth['password'] != Constants::$API_PASSWORD) {
            $flagUser = true;
        }
        if (!isset($auth['items']) || empty($auth['items'])) {
            $flagItem = true;
        }

        if ($flagUser) {
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['auth'] = $auth;
            return $response;
        }
        if ($flagItem) {
            $response['status'] = false;
            $response['message'] = "Items are empty";
            $response['auth'] = $auth;
            return $response;
        }

        $savedItem = false;
        $isNotEmpty = false;

        foreach ($auth['items'] as $item) {
            $packId = $auth['packId'];
            $savedItem = false;
            if (!empty($item['accepted'])) {
                $model = new TikuvGoodsDocAccepted();
                $model->setAttributes([
                    'model_list_id' => $item['model_list_id'],
                    'model_var_id' => $item['model_var_id'],
                    'doc_number' => (string)$auth['docNumber'],
                    'goods_id' => $item['gid'],
                    'reg_date' => date('Y-m-d H:i:s'),
                    'quantity' => $item['accepted'],
                    'barcode' => $item['barcode'],
                    'pack_id' => $packId
                ]);
                if ($model->save()) {
                    $savedItem = true;
                    $isNotEmpty = true;
                    unset($model);
                }
            } else {
                $savedItem = true;
            }
        }
        if ($savedItem && $isNotEmpty) {
            $response['status'] = true;
            $response['message'] = "OK";
            $response['auth'] = $auth;
            return $response;
        } else {
            $response['status'] = false;
            $response['message'] = "All items are empty";
            $response['auth'] = $auth;
            return $response;
        }
    }

    public function actionCreateDoc()
    {

        $auth = Yii::$app->request->post();
        $flagUser = false;
        $flagId = false;

        if ((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != Constants::$API_USER || $auth['password'] != Constants::$API_PASSWORD) {
            $flagUser = true;
        }
        if (!isset($auth['id']) || empty($auth['id'])) {
            $flagId = true;
        }

        if ($flagUser) {
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['auth'] = $auth;
            return $response;
        }
        if ($flagId) {
            $response['status'] = false;
            $response['message'] = "ID not found";
            $response['auth'] = $auth;
            return $response;
        }
        $sql = "select tgdp.doc_number as docNumber,
                       tgdp.reg_date as regDate,
                       tgdp.to_department as department,
                       ml.name as gname,
                       ml.long_name as longName, 
                       tgd.quantity,
                       0 as volume,
                       0 as accepted,
                       g.size_collection as sizeCollection, 
                       g.color_collection as colorCollection, 
                       g.type,
                       g.id as gid,
                       mrd.price as price,
                       pb.code as currency,
                       tgd.barcode,
                       g.model_no,
                       s.name as sizeName,
                       s.code as sizeCode,
                       cp.code as colorCode,
                       cp.name as colorName,
                       tgd.weight,
                       u.name as unitName,
                       u.code as unitCode,
                       g.model_id as model_list_id,
                       tgdp.model_var_id as model_var_id,
                       ml.long_name,
                       b.code as manufacturerCode,
                       b.name as manufacturerName,
                       b.code as brandCode,
                       b.name as brandName,
                       ms.name as seasonName,
                       ms.code as seasonCode,
                       mv.name as genderName, 
                       mv.code as genderCode,
                       att.path,
                       att.id as photoId from tikuv_goods_doc tgd
                left join tikuv_goods_doc_pack tgdp on tgdp.id = tgd.tgdp_id
                left join model_rel_doc mrd on mrd.model_list_id = tgdp.model_list_id
                left join pul_birligi pb on mrd.pb_id = pb.id
                left join goods g on tgd.goods_id = g.id
                left join models_list ml on g.model_id = ml.id
                left join brend b on ml.brend_id = b.id
                left join model_view mv on ml.view_id = mv.id
                left join model_season ms on ml.model_season = ms.id
                left join model_rel_attach mra on ml.id = mra.model_list_id
                left join attachments att on mra.attachment_id = att.id
                left join size s on g.size = s.id
                left join color_pantone cp on g.color = cp.id
                left join unit u on tgd.unit_id = u.id
                where tgdp.id = :id AND
                      mrd.model_var_id = tgdp.model_var_id AND
                      tgdp.order_id = mrd.order_id AND
                      tgdp.order_item_id = mrd.order_item_id
                GROUP BY mrd.model_list_id, mrd.model_var_id;";

        $results = Yii::$app->db->createCommand($sql)->bindValue('id', $auth['id'])->queryAll();
        $child = [];
        $model = [];
        $samo = Constants::$brandSAMO;
        foreach ($results as $result){
            $model = [
                'docNumber' => $result['docNumber'],
                'regDate' => $result['regDate'],
                'department' => $result['department'],
                'comments' => '',
            ];
            $child[] = [
                'gid' => $result['gid'],
                'model_no' => $result['model_no'],
                'gname' => $result['gname'],
                'quantity' => $result['quantity'],
                'volume' => $result['volume'],
                'accepted' => $result['accepted'],
                'type' => $result['type'],
                'sizeCollection' => $result['sizeCollection'],
                'colorCollection' => $result['colorCollection'],
                'price' => $result['price'],
                'currency' => $result['currency'],
                'barcode' => $result['barcode'],
                'weight' => $result['weight'],
                'sizeName' => $result['sizeName'],
                'sizeCode' => $result['sizeCode'],
                'colorName' => $result['colorName'],
                'colorCode' => $result['colorCode'],
                'unitCode' => $result['unitCode'],
                'unitName' => $result['unitName'],
                'model_list_id' => $result['model_list_id'],
                'model_var_id' => $result['model_var_id'],
                'modelInfo' => [
                    'longName' => $result['longName'],
                    'brandCode' => $result['brandCode'],
                    'brandName' => $result['brandName'],
                    'manufacturerCode' => $samo,
                    'manufacturerName' => $samo,
                    'seasonName' => $result['seasonName'],
                    'seasonCode' => $result['seasonCode'],
                    'genderName' => $result['genderName'],
                    'genderCode' => $result['genderCode'],
                    'mainPhotoId' => $result['photoId'],
                    'photos' => 'http://'.$_SERVER['HTTP_HOST'].'/'.$result['path']
                ]
            ];
        }
        $model['departmentItems'] = $child;
        $out = [
            'user' => Constants::$API_USER,
            'password' => Constants::$API_PASSWORD,
            'document' => $model
        ];
        $client = new Client();
        $url = self::POST_DEV_HOST;
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl("{$url}document-by-api/create")
            ->setData($out)
            ->send();
        if ($response->isOk) {
            return $response->data;
        }

    }

}
