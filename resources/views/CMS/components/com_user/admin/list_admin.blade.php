@extends('CMS.components.index')
@section('content')


<div id="page-title">
    <h2>Danh sách quản trị viên</h2>
    <p>Dưới đây là dữ liệu của quản trị viên.</p>
    <div id="theme-options" class="admin-options">
    <a href="javascript:void(0);" class="btn btn-primary theme-switcher tooltip-button" data-placement="left" title="Color schemes and layout options">
        <i class="glyph-icon icon-linecons-cog icon-spin"></i>
    </a>
</div>
<div class="panel">
    <div class="panel-body">
    <h3 class="title-hero">
        DANH SÁCH ADMIN
    </h3>
        <div class="example-box-wrapper">
            <table id="datatable-reorder" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>User name</th>
                    <th>Login social ID</th>
                    <th>Name</th>
                    <th>Phone number</th>
                    
                    
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>User name</th>
                    <th>Login social ID</th>
                    <th>Name</th>
                    <th>Phone number</th>
                   
                </tr>
                </tfoot>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->social_login_id }}</td>
                        <td>{{ $item->contact_name }}</td>
                        <td>{{ $item->contact_phone }}</td>
                    
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $data->render() !!}
        </div>
    </div>
</div>
@endsection