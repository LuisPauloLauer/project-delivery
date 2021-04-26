@extends('admin.layout_master.admin_design')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <br>
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $countDemandsFood ?? '' }}</h3>
                                <p>Novos pedidos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag" style="color: #6c757d"></i>
                            </div>
                            @if( $countDemandsFood ?? '' > 0 )
                                <a href="{{ route('view.orders', ['status' => 'included' ]) }}" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
                            @endif
                        </div>
                    </div>
                    <!-- Main row -->
                    <!-- /.row (main row) -->
                </div>
                <div>
                    <div>

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
