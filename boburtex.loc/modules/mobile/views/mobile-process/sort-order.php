<?php
/** @var $this \yii\web\View */
/** @var $processSortableList \app\modules\mobile\models\MobileProcess[] */

use kartik\sortable\Sortable;

$this->title = Yii::t('app', 'Sort order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mobile-process-sort-order">
    <div class="row">
        <div class="col-sm-6">
            <?= Sortable::widget([
                'options' => [
                    'id' => 'sortable_for_process'
                ],
                'type' => Sortable::TYPE_LIST,
                'items' => $processSortableList,
                'pluginEvents' => [
                    'sortupdate' => 'processSortUpdate'
                ]
            ]) ?>
        </div>
    </div>
</div>

<?php
$sortUpdateJS = <<<JS
const sortUpdateUrl = '/uz/mobile/mobile-process/sort-update';

function processSortUpdate(e) {    
    // prepare body
    let sortedItems = e.detail.destination.items.map((val, index) => {
        return {id: val.dataset.id, index: index + 1};
    })
    
    let bodyParams = {
        items: sortedItems
    };
    
    fetch(sortUpdateUrl, {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json;charset=utf-8',
        'X-CSRF-Token': yii.getCsrfToken()
        },
        body: JSON.stringify(bodyParams)
    })
      .then(response => response.json())
      .then(json => {
          console.log('-----------------RESPONSE------------------')
          console.dir(json);
          if (json.success) {
            PNotify.success({
                title: json.message
            });
          } else {
                PNotify.error({
                  title: json.message
                });
          }         
          
            setTimeout(function () {
                window.location.reload();
            }, 200)
      });
}
JS;
$this->registerJs($sortUpdateJS);

