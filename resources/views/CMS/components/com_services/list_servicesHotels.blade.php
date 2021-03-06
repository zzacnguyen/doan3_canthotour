@extends('CMS.components.index')
@section('content')


<div id="page-title">
    <h2>Danh sách dịch vụ</h2>
    <p>Dưới đây là dữ liệu dịch vụ khách sạn.</p>
    
    

</div>
<div class="panel">
    <div class="panel-body">
    <h3 class="title-hero">
        DANH SÁCH DỊCH VỤ
    </h3>
        <div class="example-box-wrapper">
            <table id="datatable-reorder" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Tên dịch vụ</th>
                    <th>Mở cửa</th>
                    <th>Số điện thoại</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Chỉnh sửa lần cuối</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Tên dịch vụ</th>
                    <th>Mở cửa</th>
                    <th>Giá</th>
                    <th>Chỉnh sửa lần cuối</th>
                    <th>Trạng thái</th>
                    <th>Lần cập nhật cuối</th>
                </tr>
                </tfoot>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td><a href="{{ route('_SERVICE_DETAILS', $item->id) }}">
                            @if($item->hotel_name != null)
                                -{{ $item->hotel_name }}
                            @endif
                            </a>
                        </td>
                        <td>Từ {{ $item->sv_open }} đến {{ $item->sv_close }}</td>
                        <td>{{ $item->sv_phone_number }} </td>
                        <td>Từ {{ $item->sv_lowest_price }} đến {{ $item->sv_highest_price }}</td>
                        <td>
                            <?php  
                               if($item->sv_status == 1)
                               {
                                    echo '<small class="label-success">Hiển thị</small>' ;
                               }
                               else if($item->sv_status == 0)
                               {
                                    echo '<small class="label-warning">Chờ duyệt</small>' ;
                               }
                               else if ($item->sv_status == -1 )
                               {
                                    echo '<small class="label-danger">Spam</small>' ;
                               }

                            ?> 
                            </td>

                        <td>{{ $item->updated_at }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $data->render() !!}
        </div>
    </div>
</div>




@endsection