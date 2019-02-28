<?php

/* @var $this yii\web\View */

$this->title = 'E-Serikat';
$noKegiatanBelum = 1;
$noKegiatan = 1;
$noKegiatanRutin = 1;
?>
<div class="site-index">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-user-secret"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Ketua</span>
                            <span class="info-box-number"><?= $jumlahKetua ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Departemen</span>
                            <span class="info-box-number"><?= $jumlahDepartemen ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Seksi</span>
                            <span class="info-box-number"><?= $jumlahSeksi ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Kegiatan Belum Disetujui</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Kegiatan</th>
                                        <th>Judul Kegiatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kegiatanBelum as $key => $value) { ?>
                                        <tr>
                                            <td><?=$noKegiatanBelum++?></td>
                                            <td><?=$value->activity_code?></td>
                                            <td><?=$value->title?></td>
                                            <td>
                                            <?php if($value->finance_status == '0')
                                            { ?>
                                                <span class="label label-info">Belum Dikonfirmasi</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '1' && $value->chief_status == '1')
                                            { ?>
                                                <span class="label label-success">Diterima</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '0' && $value->chief_status == '0')
                                            { ?>
                                                <span class="label label-success">Diterima Bendahara</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '1' && $value->chief_status == '0')
                                            { ?>
                                                <span class="label label-success">Diterima Departemen</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '2' && $value->chief_status == '0')
                                            { ?>
                                                <span class="label label-warning">Draft Departemen</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '1' && $value->chief_status == '2')
                                            { ?>
                                                <span class="label label-warning">Draft Ketua</span>
                                            <?php }
                                            else if($value->finance_status == '2')
                                            { ?>
                                                <span class="label label-warning">Draft Bendahara</span>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php foreach ($kegiatanRutinBelum as $key => $value) { ?>
                                        <tr>
                                            <td><?=$noKegiatanBelum++?></td>
                                            <td><?=$value->activity_code?></td>
                                            <td><?=$value->title?></td>
                                            <td>
                                            <?php if($value->finance_status == '0')
                                            { ?>
                                                <span class="label label-info">Belum Dikonfirmasi</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '1' && $value->chief_status == '1')
                                            { ?>
                                                <span class="label label-success">Diterima</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '0' && $value->chief_status == '0')
                                            { ?>
                                                <span class="label label-success">Diterima Bendahara</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '1' && $value->chief_status == '0')
                                            { ?>
                                                <span class="label label-success">Diterima Departemen</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '2' && $value->chief_status == '0')
                                            { ?>
                                                <span class="label label-warning">Draft Departemen</span>
                                            <?php }
                                            else if($value->finance_status == '1' && $value->department_status == '1' && $value->chief_status == '2')
                                            { ?>
                                                <span class="label label-warning">Draft Ketua</span>
                                            <?php }
                                            else if($value->finance_status == '2')
                                            { ?>
                                                <span class="label label-warning">Draft Bendahara</span>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                    <!-- /.box-footer -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Kegiatan</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Kegiatan</th>
                                        <th>Judul Kegiatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataKegiatan as $key => $value) { ?>
                                        <tr>
                                            <td><?=$noKegiatan++?></td>
                                            <td><?=$value->activity_code?></td>
                                            <td><?=$value->title?></td>
                                            <td><span class="label label-success">Diterima</span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                    <!-- /.box-footer -->
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Kegiatan Rutin</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Kegiatan</th>
                                        <th>Judul Kegiatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataKegiatanRutin as $key => $value) { ?>
                                        <tr>
                                            <td><?=$noKegiatanRutin++?></td>
                                            <td><?=$value->activity_code?></td>
                                            <td><?=$value->title?></td>
                                            <td><span class="label label-success">Diterima</span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
