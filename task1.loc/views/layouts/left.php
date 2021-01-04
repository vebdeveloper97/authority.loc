<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <?php
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => Yii::t('app', 'Settings'),
                        'icon' => 'cogs',
                        'visible' => Yii::$app->user->can('settings'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Users'),
                                'icon' => 'users',
                                'active' => $controller == 'user',
                                'url' => ['user/index']
                            ],
                            [
                                'label' => Yii::t('app', 'Access'),
                                'icon' => 'universal-access',
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Auth Item'),
                                        'icon' => 'align-justify',
                                        'active' => $controller == 'auth-item',
                                        'url' => ['auth-item/index']
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Auth Assignment'),
                                        'icon' => 'align-justify',
                                        'active' => $controller == 'auth-assignment',
                                        'url' => ['auth-assignment/index']
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Auth Item Child'),
                                        'icon' => 'align-justify',
                                        'active' => $controller == 'auth-item-child',
                                        'url' => ['auth-item-child/index']
                                    ]
                                ]
                            ],

                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Profile'),
                        'icon' => 'user',
                        'active' => $controller == 'profile',
                        'visible' => Yii::$app->user->can('simple'),
                        'url' => ['profile/index']
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
