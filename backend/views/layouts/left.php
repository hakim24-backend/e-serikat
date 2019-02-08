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
                          ['label' => 'Pemindahan Dana', 'icon' => 'dashboard', 'url' => ['/relokasi'],],

                      ],
                  ],
                  [
                      'label' => 'Uang Muka',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/kegiatan'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/kegiatan-rutin'],],
                          ['label' => 'Pertanggungjawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                          ['label' => 'Pertanggungjawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-responsibility'],],
                      ],
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
                      'label' => 'Approval',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/kegiatan'],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/bendahara'],],
                          ['label' => 'Pertanggungjawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                          ['label' => 'Pertanggungjawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/bendahara-activity-daily-responsibility'],],

                      ],
                  ],
                  [
                      'label' => 'Laporan',
                      'icon' => 'money',
                      'url' => '#',
                      'items' => [
                          ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => [''],],
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => [''],],
                          ['label' => 'Pertanggungjawaban Rutin', 'icon' => 'file-code-o', 'url' => [''],],

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
                          ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/kegiatan-rutin'],],
                          ['label' => 'Pertanggung Jawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                          ['label' => 'Pertanggung Jawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-responsibility'],],
                      ],
                  ],
                  [
                    'label' => 'Approval',
                    'icon' => 'money',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Uang Muka Kegiatan', 'icon' => 'file-code-o', 'url' => ['/kegiatan'],],
                        ['label' => 'Uang Muka Kegiatan Rutin', 'icon' => 'file-code-o', 'url' => ['/bendahara'],],
                        ['label' => 'Pertanggungjawaban', 'icon' => 'file-code-o', 'url' => ['/activity-responsibility'],],
                        ['label' => 'Pertanggungjawaban Rutin', 'icon' => 'file-code-o', 'url' => ['/activity-daily-responsibility'],],

                    ],
                ],

              ],
          ]
      ) ?>
    <?php } ?>

    </section>

</aside>
