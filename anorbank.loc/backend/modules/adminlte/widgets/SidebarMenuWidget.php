<?php

namespace backend\modules\adminlte\widgets;

use Exception;
use yii\base\Widget;
use yii\widgets\Menu;

class SidebarMenuWidget extends Widget
{
    public array $items = []; // [icon, title, url, active]

    /**
     * @throws Exception
     * @return string|void
     */
    public function run()
    {
        $items = [];

        foreach ($this->items as $item) {
            if (isset($item['icon'], $item['title'])) {
                $item['label'] = "<i class='fa {$item['icon']}'></i><span>{$item['title']}</span>";
                unset($item['icon'], $item['name']);
            }

            if (isset($item['right-label'])) {
                $item['label'] += $item['right-label'];
                unset($item['right-label']);
            }

            $items[] = $item;
        }

        echo Menu::widget([
            'options'         => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
            'encodeLabels'    => false,
            'items'           => $items,
            'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>",
            'linkTemplate'    => '<a href="{url}">{label}</a>',
            'activateParents' => true,
        ]);
    }
}
