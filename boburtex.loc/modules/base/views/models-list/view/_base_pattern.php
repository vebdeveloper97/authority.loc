<?php
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
?>
<?php if(!is_null($model)):?>
<div class="row">
  <div class="col-lg-12">

      <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
              'code',
              'counter',
              'name',
              [
                  'attribute' => 'constructor_id',
                  'value' => function ($model) {
                      return $model->constructor_id ? $model->getCustomerList($model->constructor_id) : '';
                  }
              ],
              [
                  'attribute' => 'designer_id',
                  'value' => function ($model) {
                      return $model->designer_id ? $model->getCustomerList($model->designer_id): '';
                  }
              ],
              [
                  'attribute' => 'customer_id',
                  'value' => function ($model) {
                      return $model->customer_id ? $model->getCustomerList($model->customer_id) : '';
                  }
              ],
              [
                  'attribute' => 'brend_id',
                  'value' => function ($model) {
                      return $model->getEntityList(\app\modules\base\models\Brend::className(), $model->brend_id);
                  }
              ],
              [
                  'attribute' => 'model_type_id',
                  'value' => function ($model) {
                      return $model->getEntityList(\app\modules\base\models\ModelTypes::className(), $model->model_type_id);
                  }
              ],
              [
                  'attribute' => 'status',
                  'value' => function ($model) {
                      $stts = app\modules\base\models\BasePatterns::getStatusList($model->status);
                      return $stts ? $stts : $model->status;
                  }
              ],
              [
                  'attribute' => 'created_by',
                  'value' => function ($model) {
                      return (\app\models\Users::findOne($model->created_by)) ? \app\models\Users::findOne($model->created_by)->user_fio : $model->created_by;
                  }
              ],
              [
                  'attribute' => 'created_at',
                  'value' => function ($model) {
                      return (time() - $model->created_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->created_at), 'relativeTime') : date('d.m.Y H:i', $model->created_at);
                  }
              ],
              [
                  'attribute' => 'updated_at',
                  'value' => function ($model) {
                      return (time() - $model->updated_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->updated_at), 'relativeTime') : date('d.m.Y H:i', $model->updated_at);
                  }
              ],
          ],
      ]) ?>
      <h4><?= Yii::t('app','Qolip rasmlari');?></h4>
      <?php 
        $attachments = $model->basePatternRelAttachments;
        ?>
      <?php if(!empty($attachments)):?>
          <div class="row">
              <?php foreach ($attachments as $attachment):?>
                  <div class="col-md-3">
                      <div class="thumbnail">
                          <img src="<?= $attachment->attachment['path']?>" alt="IMG" class="img-responsive"/>
                      </div>
                  </div>
              <?php endforeach;?>
          </div>
      <?php endif;?>
      <?php $postals = $model->basePatternMiniPostal;?>
      <?php if(!empty($postals)):?>
          <fieldset><h4><?= Yii::t('app','Mini postal');?></h4></fieldset>
          <div class="parentDiv">
              <?php
              if(!empty($postals)):?>
                  <table class="table table-bordered text-center">
                      <thead>
                      <tr>
                          <th>
                              <span><?php echo Yii::t('app',"O'lchamlar")?></span>
                          </th>
                          <th>
                              <span><?php echo Yii::t('app',"Fayl")?></span>
                          </th>
                      </tr>
                      </thead>
                      <?php foreach ($postals as $key => $postal) :
                          ?>
                          <tr>
                              <th>
                                  <?php if(!empty($postal->basePatternMiniPostalSizes)){
                                      foreach ($postal->basePatternMiniPostalSizes as $basePatternMiniPostalSize) {?>
                                          <code><?=$basePatternMiniPostalSize->size->name?></code>
                                      <?php }
                                  }?>
                              </th>
                              <th>
                                  <?php if($postal['type']=='image/jpeg' || $postal['type']=='image/png' || $postal['type']=='image/jpg'){
                                      echo Html::img('/web/'.$postal['path'],['style'=>'width:40px','class'=>'imgPreview']);
                                  }else {
                                      echo "<a target='_blank' href='/web/".$postal['path']."'>".$postal['name']."</a>";
                                  }?>
                              </th>
                          </tr>
                      <?php endforeach;?>
                  </table>
              <?php endif;?>
          </div>
      <?php endif;?>
      <fieldset><h4><?= Yii::t('app','Qolip fayllari');?></h4></fieldset>
      <?php $attachments = $model->basePatternRelFiles;?>
      <div class="panel panel-default">
          <div class="panel-body" style="margin-bottom: 2px;padding: 0;">
              <?php if(!empty($attachments)):?>
                  <div class="row">
                      <?php foreach ($attachments as $attachment):?>
                          <div class="col-md-3">
                              <div class="thumbnail">
                                  <a href="/<?= $attachment['path']?>" class="img-responsive"> <?=$attachment->attachment->name?> </a>
                              </div>
                          </div>
                      <?php endforeach;?>
                  </div>
              <?php endif;?>
          </div>
      </div>
  </div>
</div>

<div class="panel panel-default">
   <fieldset><h4><?=Yii::t('app','Qolip andoza detal ro\'yxatlari:')?></h4></fieldset>

    <div class="panel-body" style="margin-bottom: 2px;padding: 0;">
        <table class="table table-bordered table-stripped">
            <thead>
                <tr>
                    <th><?=Yii::t('app','Qism nomi')?></th>
                    <th><?=Yii::t('app','Qolip')?></th>
                    <th><?=Yii::t('app','Andoza detali')?></th>
                    <th><?=Yii::t('app','Detal Guruhi')?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($model->basePatternItems as $item):?>
            <tr>
                <td>
                   <?=$item->basePatternPart->name?>
                </td>
                <td>
                    <?=$item->basePattern->name?>
                </td>
                <td>
                    <?=$item->baseDetailList->name?>
                </td>
                <td>
                    <?=$item->bichuvDetailType->name?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<?php endif;?>
