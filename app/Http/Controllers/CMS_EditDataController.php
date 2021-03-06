<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pointModel;
use App\typesModel;
use App\Http\Requests\AddPointRequest;
use App\Http\Requests\AddTypeEventRequest;
use App\servicesModel;
use App\eventModel;

use App\touristPlacesModel;
use Carbon\Carbon;
class CMS_EditDataController extends Controller
{
    public function _POST_EDIT_POINT(AddPointRequest $request, $id)
    {
    	$point = pointModel::findOrFail($id);
        $point->point_title =  $request->input('point_title');
        $point->point_description =  $request->input('point_description');
        $point->point_rate =  $request->input('point_rate');
        $point->point_date =  $request->input('point_date');
        $title = $point->point_title;
        if ($request->get('action') == 'save_close') {
            $point->save();
            return redirect()->route('_GET_LIST_ALL_POINT')->with('message', "Hoàn tất, Đã chỉnh sửa ".$title."!");
        } 
        else
        {
            return json_encode("status:500");
        }
    }
    public function EDIT_TYPES_EVENT(AddTypeEventRequest $request, $id)
    {
        $type =  typesModel::findOrFail($id);
        $type->type_name = $request->input('name');
        $type->type_status = $request->input('status');
        $type_name  = $request->input('name');
        if ($request->get('action') == 'save_close') {
            $type->save();
            return redirect()->route('_GET_EVENT_TYPES')->with('message', "Hoàn tất, Đã sửa loại hình ".$type_name  ."!");
        } 
        else
        {
            return json_encode("status:500");
        }
    }
    public function EDIT_STATUS_UNACTIVE_SERVICES($id)
    {
        servicesModel::where('id', $id)
        ->update(['sv_status'=>1]);
        $user = servicesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(2,"Dịch vụ của bạn đã được duyệt", $user->user_id, $id);

        return redirect()->route('LIST_UNACTICE_SERVICES')->with('message', "Hoàn tất, duyệt thành công một dịch vụ!");
    }
    public function EDIT_STATUS_UNACTIVE_SERVICES2($id)
    {
        servicesModel::where('id', $id)
        ->update(['sv_status'=>-1]);

        $user = servicesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(-1,"Dịch vụ đã bị đánh dấu spam", $user->user_id, $id);

        return redirect()->route('LIST_UNACTICE_SERVICES')->with('message', "Hoàn tất, đánh dấu spam một dịch vụ!");
    }

     public function EDIT_STATUS_UNACTIVE_SERVICES3($id)
    {
        servicesModel::where('id', $id)
        ->update(['sv_status'=>0]);

        $user = servicesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(3,"Dịch vụ đã được bỏ đánh dấu spam", $user->user_id, $id);

        return redirect()->route('LIST_UNACTICE_SERVICES')->with('message', "Hoàn tất, bỏ đánh dấu spam một dịch vụ!");
    }   

    public function _DETAIL_ACTIVE_PLACE($id)
    {
        touristPlacesModel::where('id',$id)
        ->update(['pl_status'=>1]);

        $user = touristPlacesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(2,"Địa điểm của bạn đã được duyệt", $user->user_id, $id);

        return redirect()->route('_PLACES_DETAILS', $id);
    }
    public function _DETAIL_UNACTIVE_PLACE($id)
    {
        touristPlacesModel::where('id',$id)
        ->update(['pl_status'=>0]);

        $user = touristPlacesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(-1,"Địa điểm của bạn đã bị ẩn", $user->user_id);
        return redirect()->route('_PLACES_DETAILS', $id);   
    }
    public function _DETAIL_SPAM_PLACE($id)
    {
        touristPlacesModel::where('id',$id)
        ->update(['pl_status'=>-1]);

        $user = touristPlacesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(-1,"Địa điểm của bạn đã bị đánh dấu spam", $user->user_id, $id);

        return redirect()->route('_PLACES_DETAILS', $id);   
    }
    public function _DETAIL_UNSPAM_PLACE($id)
    {
        touristPlacesModel::where('id',$id)
        ->update(['pl_status'=>0]);

        $user = touristPlacesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(2,"Địa điểm của bạn đã được bỏ đánh dấu spam", $user->user_id, $id);
        return redirect()->route('_PLACES_DETAILS', $id);   
    }
    public function _DETAIL_ACTIVE_SERVICE($id)
    {
        servicesModel::where('id',$id)
        ->update(['sv_status'=>1]);

        $user = servicesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(2,"Dịch vụ của bạn đã được duyệt", $user->user_id, $id);

        return redirect()->route('_SERVICE_DETAILS', $id);
    }
    public function _DETAIL_UNACTIVE_SERVICES($id)
    {
        servicesModel::where('id',$id)
        ->update(['sv_status'=>0]);

        $user = servicesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(-1,"Dịch vụ của bạn đã bị ẩn", $user->user_id, $id);

        return redirect()->route('_SERVICE_DETAILS', $id);   
    }
    public function _AJAX_SPAM_SERVICES($id)
    {
        servicesModel::where('id',$id)
        ->update(['sv_status'=>-1]);

        $user = servicesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(1,"Dịch vụ của bạn đã bị đánh dấu spam", $user->user_id,$id);
        return redirect()->route('_SERVICE_DETAILS', $id);   
    }
    public function _DETAIL_UNSPAM_SERVICES($id)
    {
        servicesModel::where('id',$id)
        ->update(['sv_status'=>0]);

        $user = servicesModel::where('id', $id)->select('user_id')->first();
        $this::add_event(3,"Dịch vụ của bạn đã được hủy đánh dấu spam", $user->user_id, $id);

        return redirect()->route('_SERVICE_DETAILS', $id);   
    }


    public function ACTIVE_USER($id)
    {
        
    }


    public function EditPoint($id, $point_rate, $point_title, $point_description)
    {
        pointModel::where('id',$id)
        ->update(['point_title'=>$point_title, 'point_description'=>$point_description, 'point_rate'=>$point_rate]);
        return "";
    }


    public function add_event($type_event,$event_name, $user_id,$id_dv_pla){
        try 
        {
            $dt = Carbon::now();
            $event = new eventModel();
            $event->event_name   = $event_name;
            $event->user_id      = $user_id;
            $event->event_start  = $dt;
            $event->event_end    = $dt;
            $event->event_status = 0;
            $event->type_id      = 1;
            $event->event_user   = $type_event;
            $event->service_id   = $id_dv_pla;
            $event->save();
        } catch (Exception $e) {
            
        }
            
    }
    
}
