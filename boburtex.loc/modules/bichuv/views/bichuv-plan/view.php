<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 02.03.20 15:34
 */

use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $model null|static */
?>
<?php if (!empty($models) && !empty($model)):?>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading_<?=$model['id']?>">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($models as $item):?>
                         <tr>
                            <td></td>
                        </tr>
                   <?php endforeach;?>
                </tbody>

            </table>
        </div>
    </div>
<?php endif;?>
