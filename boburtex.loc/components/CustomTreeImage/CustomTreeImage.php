<?php

namespace app\components\CustomTreeImage;

use app\assets\TreeAsset;
use yii\helpers\Html;

class CustomTreeImage extends \yii\bootstrap\Widget{

    public $root = 'Root';

    public $icon = 'user';

    public $iconRoot = 'tree-conifer';

    public $query;


    public function init()
    {
        TreeAsset::register($this->getView());
        $this->initTreeView();
    }

    protected function initTreeView()
    {


        $iconRoot = '<span class="fa fa-'.$this->iconRoot.'"></span>';

        $dataArray = $this->query->asArray()->all();

        $nodeDepth = $currDepth = $counter = 0;

        echo Html::beginTag('div', ['class' => 'custom-tree']);
        echo Html::beginTag('ul') . "\n" .Html::beginTag('li') . "\n" ;
        echo '<a href="#" class="root-title">'.$iconRoot.'  '.$this->root.'</a>' . "\n" ;

        foreach ($dataArray as $key) {
            if ($key['lvl'] == 0 && $currDepth == 0)
            {
                echo Html::beginTag('ul') . "\n" .Html::beginTag('li') . "\n" ;
                echo $this->node($key);
            }  else
            {
                $as = $currDepth-1;
                $sa = ${'x'.$as}+1;
                if ($key['lvl'] == ${'x'.$as}) {
                    echo Html::beginTag('li') . "\n";
                    echo $this->node($key);
                    echo  Html::endTag('/li') . "\n";
                } else if ($key['lvl'] == $sa){
                    echo Html::beginTag('ul') . "\n" .Html::beginTag('li') . "\n" ;
                    echo $this->node($key) ;
                } else
                {
                    $da = ${'x'.$as}-1;
                    if ($key['lvl'] == $da) {
                        echo Html::endTag('li') . "\n" ;
                        echo Html::endTag('ul') . "\n" ;
                        echo Html::beginTag('li') . "\n" ;
                        echo $this->node($key);
                    }else
                    {
                        $hasil = ${'x'.$as} - $key['lvl'];
                        for ($i=0; $i < $hasil ; $i++) {
                            echo Html::endTag('li') . "\n" ;
                            echo Html::endTag('ul') . "\n" ;
                        }
                        echo Html::beginTag('li') . "\n" ;
                        echo $this->node($key);
                    }
                }
            }

            ${'x'.$currDepth} = $key['lvl'];
            ++$currDepth;
            ++$nodeDepth;
        }

        echo Html::endTag('div');
    }

    protected function node($key){
        $icon = '<span class="fa fa-'.$this->icon.'"></span>';
        return '<a href="#" class="tree-each-node" data-id="'.$key['id'].'">'.$icon.'  '.$key['name'].'<div id="treeEachNode-'.$key['id'].'" data-id="'.$key['id'].'"></div></a>' . "\n" ;
    }
}