@extends('CMS.components.index')
@section('content')
<script type="text/javascript">

    /* Datatables export */

    $(document).ready(function() {
        var table = $('#datatable-tabletools').DataTable();
        var tt = new $.fn.dataTable.TableTools( table );

        $( tt.fnContainer() ).insertBefore('#datatable-tabletools_wrapper div.dataTables_filter');

        $('.DTTT_container').addClass('btn-group');
        $('.DTTT_container a').addClass('btn btn-default btn-md');

        $('.dataTables_filter input').attr("placeholder", "Search...");

    } );

    /* Datatables reorder */

    $(document).ready(function() {
        $('#datatable-reorder').DataTable( {
            dom: 'Rlfrtip'
        });

        $('#datatable-reorder_length').hide();
        $('#datatable-reorder_filter').hide();

    });

    $(document).ready(function() {
        $('.dataTables_filter input').attr("placeholder", "Search...");
    });

</script>

<div id="page-title">
    <h2>Danh sách người dùng</h2>
    
    <div id="theme-options" class="admin-options">
    <a href="javascript:void(0);" class="btn btn-primary theme-switcher tooltip-button" data-placement="left" title="Color schemes and layout options">
        <i class="glyph-icon icon-linecons-cog icon-spin"></i>
    </a>
</div>
<div class="panel">
    <div class="panel-body">
    <h3 class="title-hero">
        HƯỚNG DẪN VIÊN DU LỊCH
    </h3>
        <div class="example-box-wrapper">
            <table id="datatable-reorder" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>User name</th>
                    <th>Login social ID</th>
                    <th>Name</th>
                    <th>Phone number</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Ngày đăng ký</th>
                    <th>Được duyệt</th>
                    <th>Thao tác</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>User name</th>
                    <th>Login social ID</th>
                    <th>Name</th>
                    <th>Phone number</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Ngày đăng ký</th>
                    <th>Được duyệt</th>
                    <th>Thao tác</th>
                </tr>
                </tfoot>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->social_login_id }}</td>
                        <td>{{ $item->contact_name }}</td>
                        <td>{{ $item->contact_phone }}</td>
                        <td>{{ $item->contact_email_address }}</td>
                        <td>{{ $item->contact_website }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td> <?php if($item->account_active == 1)  
                                echo "Được duyệt";
                                 else 
                                 echo "Chưa duyệt"; ?> </td>
                        <td><a href="{{ route('ACCTIVE_TOURGUIDE', $item->user_id) }}">
                            <i class="glyph-icon tooltip-button demo-icon icon-bolt bg-success"></i>
                        </a>
                            
                           <a href="{{ route('UNACCTIVE_TOURGUIDE', $item->user_id) }}"> <i class="glyph-icon tooltip-button demo-icon icon-bell-slash bg-danger"></a></i>
                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $data->render() !!}
        </div>
    </div>
</div>




@endsection