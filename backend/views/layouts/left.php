<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                    // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Serikat Inti', 'icon' => 'bank', 'url' => ['/serikatinti']],
                    [
                        'label' => 'Serikat SDM',
                        'icon' => 'building-o',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Ketua', 'icon' => 'file-code-o', 'url' => ['/chief'],],
                            ['label' => 'Departemen', 'icon' => 'file-code-o', 'url' => ['/department'],],
                            ['label' => 'Seksi', 'icon' => 'file-code-o', 'url' => ['/section'],],
                            
                        ],
                    ],
                    [
                        'label' => 'Modul Bendahara',
                        'icon' => 'archive',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Sumber Dana', 'icon' => 'file-code-o', 'url' => ['/budget'],],
                            ['label' => 'Relokasi Dana', 'icon' => 'dashboard', 'url' => ['/transfer'],],
                            ['label' => 'Pemindahan Dana', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            
                        ],
                    ],
                    [
                        'label' => 'Uang Muka',
                        'icon' => 'money',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                        ],
                    ],

                    
                ],
            ]
        ) ?>

    </section>

</aside>
