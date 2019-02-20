<?php


$Role = Yii::$app->user->identity->roleName();


?>


<aside class="main-sidebar">

  <section class="sidebar">


<?php
    if($Role == "Super Admin"){ ?>
      <?= dmstr\widgets\Menu::widget(
          [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
              'items' => [
                  ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                  // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                  // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                  [
                    'label' => 'Serikat Inti',
                    'icon' => 'bank',
                    'url' => ['/serikatinti'],

                  ],
              ],
          ]
      ) ?>
    <?php }else if($Role == "Sekertaris Umum"){ ?>
      <?= dmstr\widgets\Menu::widget(
          [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
              'items' => [
                  ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                  // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                  // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                  [
                      'label' => 'Modul Bendahara',
                      'icon' => 'archive',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Sumber Dana', 'icon' => 'file-code-o', 'url' => ['/budget'],],
                          ['label' => 'Relokasi Dana', 'icon' => 'dashboard', 'url' => ['/transfer'],],

                      ],
                  ],

                  [
                      'label' => 'Uang Muka',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/kegiatan'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/kegiatan-rutin'],],
                          ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                          ['label' => 'Pertanggung Jawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-responsibility'],],
                      ],
                  ],
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

              ],
          ]
      ) ?>
    <?php }else if($Role == "Ketua Umum"){ ?>
      <?= dmstr\widgets\Menu::widget(
          [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
              'items' => [
                  ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                  // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                  // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                  [
                      'label' => 'Modul Bendahara',
                      'icon' => 'archive',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Sumber Dana', 'icon' => 'file-code-o', 'url' => ['/budget'],],
                          ['label' => 'Relokasi Dana', 'icon' => 'dashboard', 'url' => ['/transfer'],],

                      ],
                  ],
                  [
                      'label' => 'Uang Muka',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/kegiatan'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/kegiatan-rutin'],],
                          ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                          ['label' => 'Pertanggung Jawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-responsibility'],],
                      ],
                  ],


              ],
          ]
      ) ?>
    <?php }else if($Role == "Sekretariat"){ ?>
      <?= dmstr\widgets\Menu::widget(
          [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
              'items' => [
                  ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                  // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                  // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                  [
                      'label' => 'Uang Muka',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/kegiatan'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/kegiatan-rutin'],],
                          ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                          ['label' => 'Pertanggung Jawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-responsibility'],],
                      ],
                  ],


              ],
          ]
      ) ?>
    <?php } else if($Role == "Seksi"){ ?>
      <?= dmstr\widgets\Menu::widget(
          [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
              'items' => [
                  ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                  // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                  // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                  [
                      'label' => 'Uang Muka',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/kegiatan'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/kegiatan-rutin'],],
                          ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                          ['label' => 'Pertanggung Jawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-responsibility'],],
                      ],
                  ],


              ],
          ]
      ) ?>
    <?php } else if($Role == "Bendahara"){ ?>
      <?= dmstr\widgets\Menu::widget(
          [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
              'items' => [
                  ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                  [
                      'label' => 'Modul Bendahara',
                      'icon' => 'archive',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Sumber Dana', 'icon' => 'file-code-o', 'url' => ['/budget'],],
                          ['label' => 'Relokasi Dana', 'icon' => 'dashboard', 'url' => ['/transfer'],],
                          ['label' => 'Pemindahan Dana', 'icon' => 'dashboard', 'url' => ['/relokasi'],],

                      ],
                  ],
                  [
                      'label' => 'Approval',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/bendahara'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/bendahara-rutin'],],
                          ['label' => 'Pertanggungjawaban', 'icon' => 'file-code-o', 'url' => ['/bendahara-activity-responsibility'],],
                          ['label' => 'Pertanggungjawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/bendahara-activity-daily-responsibility'],],

                      ],
                  ],
                  [
                      'label' => 'Laporan',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Data Kegiatan', 'icon' => 'file-code-o', 'url' => ['/activity-report'],],
                          ['label' => 'Data Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-report'],],
                      ],
                  ],
              ],
          ]
      ) ?>
    <?php } else if($Role == "Departemen") {?>
        <?= dmstr\widgets\Menu::widget(
          [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
              'items' => [
                  ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                  // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                  // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                  [
                      'label' => 'Uang Muka',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/activity-department'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-department'],],
                          ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/department-activity-responsibility'],],
                          ['label' => 'Pertanggung Jawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/department-activity-daily-responsibility'],],
                      ],
                  ],
                  [
                    'label' => 'Approval',
                    'icon' => 'money',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/approval-department-activity'],],
                        ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/approval-department-activity-daily'],],
                        ['label' => 'Pertanggungjawaban', 'icon' => 'file-code-o', 'url' => ['/department-approval-activity-responsibility'],],
                        ['label' => 'Pertanggungjawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/department-approval-activity-daily-responsibility'],],

                    ],
                ],

              ],
          ]
      ) ?>
    <?php }else if($Role == "Ketua") { ?>
      <?= dmstr\widgets\Menu::widget(
        [
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => [
                ['label' => 'Menu E-Serikat', 'options' => ['class' => 'header']],
                // ['label' => 'Giis', 'icon' => 'file-code-o', 'url' => ['/gii']],
                // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                [
                    'label' => 'Uang Muka',
                    'icon' => 'money',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/activity-chief'],],
                        ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-chief'],],
                        ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/chief-activity-responsibility'],],
                        ['label' => 'Pertanggung Jawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/chief-activity-daily-responsibility'],],
                    ],
                ],
                [
                  'label' => 'Approval',
                  'icon' => 'money',
                  'url' => '#',
                  'items' => [
                      ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/approval-chief-activity'],],
                      ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/approval-chief-activity-daily'],],
                      ['label' => 'Pertanggungjawaban', 'icon' => 'file-code-o', 'url' => ['/chief-approval-activity-responsibility'],],
                      ['label' => 'Pertanggungjawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/chief-approval-activity-daily-responsibility'],],

                  ],
              ],

            ],
        ]
    ) ?>
    <?php } ?>

    </section>

</aside>
