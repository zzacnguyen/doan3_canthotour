<?php

namespace App\Http\Controllers;
use usersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\servicesModel;
use App\Http\Controllers\SearchController;
use App\touristPlacesModel;
use App\provincecityModel;
use Illuminate\Database\Eloquent\Colection;

class pageController extends Controller
{
    public function layindex()
    {
        return view('VietNamTour.content.index');
    }

    public function getindex()
    {   
        $placecount       = $this::count_place_display();

        $services_eat     = $this::getservicestake(1,8);
        $services_hotel   = $this::getservicestake(2,6);
        $services_tran    = $this::getservicestake(3,8);
        $services_see     = $this::getservicestake(4,8);
        $services_enter   = $this::getservicestake(5,8);
        // dd($services_hotel);
    	return view('VietNamTour.content.index',compact('placecount','services_hotel','services_eat','services_enter','services_see','services_tran'));
    }

    public function getlogin()
    {
    	return view('VietNamTour.login');
    }

    public function getregister()
    {
        return view('VietNamTour.register');
    }

    public function getregisterSuccess()
    {
        return view('VietNamTour.registerSuccess');
    }

    public function getuser()
    {
        return view('VietNamTour.user');
    }

    public function getdetail($idservices,$type)
    {
        $placecount       = $this::count_place_display();
        $detailServices = $this::getServiceType($idservices,$type);
        $place = $this::findplace_service($idservices);
        
        $dichvulancan = $this::searchServicesVicinity($place->pl_latitude,$place->pl_longitude,5,5000);
        // print_r($dichvulancan)
        $lam = var_dump($dichvulancan);
        // dd($lam);
        return view('VietNamTour.content.detail',compact('placecount','detailServices','dichvulancan'));
    }

    public function getaddplace()
    {
        return view('VietNamTour.addplace');
    }

    public function getaddservice()
    {
        return view('VietNamTour.addservice');
    }

    // funtion

    public function getServiceType($sv_id,$sv_types)
    {
        switch ($sv_types) {
            case 2:
                $result = DB::table('vnt_services')
                                    ->join('vnt_hotels','vnt_services.id','=','vnt_hotels.service_id')
                                    ->join('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->join('vnt_tourist_places as p','vnt_services.tourist_places_id','=','p.id')
                                    ->select('vnt_services.id as sv_id','sv_description','sv_phone_number','sv_open','sv_close','vnt_services.sv_types','hotel_name as sv_name','vnt_images.id as id_image','vnt_images.image_details_1','sv_highest_price','sv_lowest_price','p.pl_latitude','p.pl_longitude','vnt_images.image_details_2','vnt_images.image_banner')
                                    ->where('sv_status',"Active")
                                    ->where('sv_types',$sv_types)->first();
                $name = 'hotel_name';
                
                return $result;
                break;  
            case 1:
                $result = DB::table('vnt_services')
                                    ->join('vnt_eating', 'vnt_services.id', '=', 'vnt_eating.service_id')
                                    ->join('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id','sv_description','sv_phone_number','sv_open','sv_close','vnt_services.sv_types','eat_name','vnt_images.id as id_image','vnt_images.image_details_1','sv_highest_price','sv_lowest_price')
                                    ->where('sv_status',"Active")
                                    ->where('sv_types',$sv_types)->first();
                                    $name = 'eat_name';
                return $result;
                break;  
            case 3:
                $result = DB::table('vnt_services')
                                    ->leftJoin('vnt_transport','vnt_services.id','=','vnt_transport.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id','sv_types','sv_description','sv_open','sv_close','sv_highest_price','sv_lowest_price','sv_phone_number','vnt_transport.transport_name','vnt_images.id as id_image','vnt_images.image_details_1')
                                    ->where('sv_status',"Active")
                                    ->where('sv_types',$sv_types)->first();
                return $result;
                break;
            case 4:
                $result = DB::table('vnt_services')
                                    ->join('vnt_sightseeing','vnt_services.id','=','vnt_sightseeing.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->join('vnt_tourist_places','vnt_services.tourist_places_id','=','vnt_tourist_places.id')
                                    ->select('vnt_services.id','sv_types','sv_description','sv_open','sv_close','sv_highest_price','sv_lowest_price','sv_phone_number','sightseeing_name','vnt_images.id as id_image','vnt_images.image_details_1', 'pl_latitude','pl_longitude')
                                    ->where('vnt_services.id',$sv_id)
                                    ->where('tourist_places_id',$tourist_places_id)
                                    ->where('sightseeing_status','Active')
                                    ->where('sv_types',$sv_types)->first();
                return $result;
                break;
            case 5:
                $result = DB::table('vnt_services')
                                    ->join('vnt_entertainments','vnt_services.id','=','vnt_entertainments.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->join('vnt_tourist_places','vnt_services.tourist_places_id','=','vnt_tourist_places.id')
                                    ->select('vnt_services.id','sv_types','sv_description','sv_open','sv_close','sv_highest_price','sv_lowest_price','sv_phone_number','entertaiments_name','vnt_images.id as id_image','vnt_images.image_details_1', 'pl_latitude','pl_longitude')
                                    ->where('vnt_services.id',$sv_id)
                                    ->where('tourist_places_id',$tourist_places_id)
                                    ->where('entertaiments_status','Active')
                                    ->where('sv_types',$sv_types)->first();
                return $result;
                break;
        }
    }

    public function getServiceTypeVicinity()
    {
        $lam = SearchController::distance(1,2,3,4);
        return $lam;
    }

    public function getplaceCity($idcity)
    {
        // $place = DB::table('vnt_tourist_places')
        //             ->where('id',$idcity)->take(10)->get();

        $place = touristPlacesModel::all();
        return $place;
    }

    public function get5dichvu($sv_types,$tourist_places_id)
    {
        dd($p);
        switch ($sv_types) {
            case 2:
                $result = DB::table('vnt_services')
                                    ->where('tourist_places_id',$sv_types)->get();
                return $result;
                break;
        }
    }


    // get cho 
    public function getservicestake($sv_types,$take)
    {
        switch ($sv_types) {
            case 1:
                $result = DB::select('CALL top8eat');
                                    $name = 'eat_name';
                break; 
            case 2:
                $result = DB::select('CALL top8hotel');
                $name = 'sv_name';
                break;  
            case 3:
                $result = DB::select('CALL top8stranport');
                                    $name = 'transport_name';
                break;
            case 4:
                $result = DB::select('CALL top8sightseeing');
                                    $name = 'sightseeing_name';
                break;
            case 5:
                $result = DB::select('CALL top8entertaiment');
                                    $name = 'entertainments_name';
                break;
        }

        if (isset($result)) {

            foreach ($result as $value) {

                $id_city = $this::findservicetocity($value->sv_id);
                
                $likes = DB::table('vnt_likes')->where('service_id', '=',$value->sv_id)->count();

                $ratings = DB::table('vnt_visitor_ratings')->where('service_id')->first();
                if (!empty($ratings)) {
                    $ponit_rating = $ratings->vr_rating;
                }else{ $ponit_rating = 0; }

                $city = DB::table('vnt_province_city')->where('id',$id_city)->first();
                $name_city = $city->province_city_name;
                $mang[] = array(
                    'id_service'=>$value->sv_id,
                    'name'=>$value->$name,
                    'image'=>$value->image_details_1,
                    'id_city'=>$id_city,
                    'name_city'=>$name_city,
                    'sv_highest_price'=>$value->sv_highest_price,
                    'sv_lowest_price'=>$value->sv_lowest_price,
                    'like'=>$likes,
                    'rating'=>$ponit_rating,
                    'sv_type'=>$sv_types);
            }
            if (isset($mang)) {return $mang;}else{ return null; }
        }
        else
            return null;
    }
    
    public function findservicetocity($idservice)
    {
        $services = DB::table('vnt_services')
        ->join('vnt_tourist_places','vnt_services.tourist_places_id','=','vnt_tourist_places.id')
        ->select('vnt_services.id','sv_types','tourist_places_id')->where('vnt_services.id',$idservice)->first();
        $sv_types          = $services->sv_types;
        $tourist_places_id = $services->tourist_places_id;

        $war = DB::table('vnt_tourist_places')->where('id',$tourist_places_id)->first();
        $id_ward = $war->id_ward;

        $district = DB::table('vnt_ward')->where('id',$id_ward)->first();
        $id_district = $district->district_id;

        $city = DB::table('vnt_district')->where('id',$id_district)->first();
        $id_city = $city->province_city_id;

        return $id_city;
    }

    // count place province city
    public function countplaceAllcity()
    {
        $p = DB::table('vnt_province_city')
            ->get();
        foreach ($p as $value) {

            $dis = DB::table('vnt_district')->where('province_city_id',$value->id)->get();
            $dem = 0;
            foreach ($dis as $district) {
                $ward = DB::table('vnt_ward')->where('district_id',$district->id)->get();
                foreach ($ward as $place) {
                    $place_ = DB::table('vnt_tourist_places')->where('id_ward',$place->id)->get();
                    $dem += count($place_);
                }
            }

            $placecount[] = array('id'=>$value->id,'city_name'=>$value->province_city_name,'amount_place'=>$dem);
        }
        return $placecount;

   
        

    }

    public function getlam($type)
    {
    
        $result = DB::table('vnt_services')
                                    ->leftJoin('vnt_hotels', 'vnt_services.id', '=', 'vnt_hotels.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id','sv_types','hotel_name','hotel_number_star','vnt_images.id as id_image','vnt_images.image_details_1')
                                    ->where('hotel_status','Active')
                                    ->where('sv_types',$type)->take(8)->get();
                                    $name = 'hotel_name';
        return $result;
    }

    public function getcount_place_city()
    {
        $p = DB::table('vnt_province_city')
            ->get();
        foreach ($p as $value) {

            $dis = DB::table('vnt_district')->where('province_city_id',$value->id)->get();
            $dem = 0;
            foreach ($dis as $district) {
                $ward = DB::table('vnt_ward')->where('district_id',$district->id)->get();
                foreach ($ward as $place) {
                    $place_ = DB::table('vnt_tourist_places')->where('id_ward',$place->id)->get();
                    $dem += count($place_);
                }
            }
            // $placecount[] = array('id'=>$value->id,'city_name'=>$value->province_city_name,'amount_place'=>$dem);

            echo "<li class='selectItem' data-name=";
            echo "'".$value->province_city_name."'>";

            echo "<a href='https://www.google.com.vn' class='selectItem-name'>";
            echo "<label>".$value->province_city_name."</label>";
            echo "<span class='float-right'>".$dem."</span>";
            echo "</a>";

            echo "</li>";
        }
    }

    public function count_place_Allcity()
    {
        $result = DB::select('CALL count_city_place()');
        foreach ($result as $value) {
            echo "<li class='selectItem' data-name=";
            echo "'".$value->province_city_name."'>";

            echo "<a href='https://www.google.com.vn' class='selectItem-name'>";
            echo "<label>".$value->province_city_name."</label>";
            echo "<span class='float-right'>".$value->amount_palce."</span>";
            echo "</a>";

            echo "</li>";
        }
    }

    public function count_place_display()
    {
        $result = DB::select('CALL count_city_place()');
        return $result;
    }


    // 
    public static function distance($lat1, $lon1, $lat2, $lon2) 
    {
        // có thế thêm tham số $unit vào hàm để tính theo các đơn vị khác 
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        // $unit = strtoupper($unit);
        return ($miles * 1.609344)*1000; //trả về mét
        
    }

    public function distanceRadius($latitude, $longitude, $radius)
    {
        $places = touristPlacesModel::all();
        foreach ($places as $p) {
            $p_latitude = (double)$p['pl_latitude'];
            $p_longitude = (double)$p['pl_longitude'];
            $distancePlace = $this::distance($latitude, $longitude, $p_latitude, $p_longitude);
            if ($distancePlace <= $radius && $distancePlace > 1) {
                $result[$distancePlace] = array('id' => $p['id'],'pl_name' => $p['pl_name'],'distantce' => $distancePlace, 'latitude' => $p['pl_latitude'],'longitude' => $p['pl_longitude']);
            }
        }
        if (isset($result)) { return $result; }
        else{ return null; } 
    }

    public static function getServicesAll($place_id,$typeServices, $distance)
    {
        switch ($typeServices) {
            case '1':
                $result = DB::table('vnt_services')
                                    ->Join('vnt_eating', 'vnt_services.id', '=', 'vnt_eating.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id', 'vnt_eating.eat_name','vnt_images.id as image_id','vnt_images.image_details_1')
                                    ->where('tourist_places_id',$place_id)
                                    ->where('sv_types',$typeServices)->take(5)->get()->toArray();
                if (!empty($result)) {
                    // foreach ($result as $value) {
                    //     $resultCustom[] = array('id'=> $value->id,'eat_name' => $value->eat_name,'image_id' => $value->image_id,'image_details_1' => 'image_details_1','distance' => $distance);
                    // }
                    if (isset($result)) {
                        return $result;
                    }
                    else{ return null;  }
                }else{ return null; }
                break;
            case '2':
                $result = DB::table('vnt_services')
                                    ->Join('vnt_hotels', 'vnt_services.id', '=', 'vnt_hotels.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id','vnt_hotels.hotel_name','vnt_images.id as image_id','vnt_images.image_details_1')
                                    ->where('tourist_places_id',$place_id)
                                    ->where('sv_types',$typeServices)->take(5)->get();
                if (!empty($result)) {
                    foreach ($result as $value) {
                        $resultCustom[] = array('id'=> $value->id,'hotel_name' => $value->hotel_name,'image_id' => $value->image_id,'image_details_1' => 'image_details_1','distance' => $distance);
                    }
                    if (isset($resultCustom)) {
                        return $resultCustom;
                    }
                    else{ return null;  }
                }else{ return null; }
                break;
            case '3':
                $result = DB::table('vnt_services')
                                    ->Join('vnt_transport','vnt_services.id','=','vnt_transport.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id','vnt_transport.transport_name','vnt_images.id as image_id','vnt_images.image_details_1')
                                    ->where('tourist_places_id',$place_id)
                                    ->where('sv_types',$typeServices)->take(5)->get();
                if (!empty($result)) {
                    foreach ($result as $value) {
                        $resultCustom[] = array('id'=> $value->id,'transport_name' => $value->transport_name,'image_id' => $value->image_id,'image_details_1' => 'image_details_1','distance' => $distance);
                    }
                    if (isset($resultCustom)) {
                        return $resultCustom;
                    }
                    else{ return null;  }
                }else{ return null; }
                break;
            case '4':
                $result = DB::table('vnt_services')
                                    ->join('vnt_sightseeing','vnt_services.id','=','vnt_sightseeing.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id','vnt_sightseeing.sightseeing_name','vnt_images.id as image_id','vnt_images.image_details_1')
                                    ->where('tourist_places_id',$place_id)
                                    ->where('sv_types',$typeServices)->take(5)->get();
                if (!empty($result)) {
                    foreach ($result as $value) {
                        $resultCustom[] = array('id'=> $value->id,'sightseeing_name' => $value->sightseeing_name,'image_id' => $value->image_id,'image_details_1' => 'image_details_1','distance' => $distance);
                    }
                    if (isset($resultCustom)) {
                        return $resultCustom;
                    }
                    else{ return null;  }
                }else{ return null; }
                break;
            case '5':
                $result = DB::table('vnt_services')
                                    ->join('vnt_entertainments','vnt_services.id','=','vnt_entertainments.service_id')
                                    ->leftJoin('vnt_images','vnt_services.id','=','vnt_images.service_id')
                                    ->select('vnt_services.id','vnt_entertainments.entertainments_name','vnt_images.id as image_id','vnt_images.image_details_1')
                                    ->where('tourist_places_id',$place_id)
                                    ->where('sv_types',$typeServices)->get();
                if (!empty($result)) {
                    foreach ($result as $value) {
                        $resultCustom[] = array('id'=> $value->id,'entertainments_name' => $value->entertainments_name,'image_id' => $value->image_id,'image_details_1' => 'image_details_1','distance' => $distance);
                    }
                    if (isset($resultCustom)) {
                        return $resultCustom;
                    }
                    else{ return null;  }
                }else{ return null; }
                break;
        }
    }

    //tìm dịch vụ lân cận
    public function searchServicesVicinity($latitude,$longitude, $type,int $radius)
    {
        if ($radius >=100) 
        {
            $arr_distance = $this::distanceRadius($latitude,$longitude,$radius);
            if (!empty($arr_distance)) {
                ksort($arr_distance);
                foreach ($arr_distance as $value) {
                    $arr_distancePlace[$value['id']] = $value['distantce'];
                }
                foreach ($arr_distancePlace as $key => $value) {
                    if (!empty($this::getServicesAll($key,$type,$value))) {
                        foreach ($this::getServicesAll($key,$type,$value) as $k => $v) {
                            $r[] = $v;

                        }
                        // $r = $this::getServicesAll($key,$type,$value);
                    }
                }
                if (isset($r)) {
                    // $resultAll['data'] = $r;
                    // $resultAll['status'] = "OK";

                    return json_encode($r);
                }
                else{
                    $resultAll = array('data' => null,'status' => 'NOT FOUND');
                    return json_encode($resultAll);
                }
            }
            else{
                $resultAll = array('data' => null,'status' => 'NOT FOUND');
                return json_encode($resultAll);
            }
        }
        else
        {
            $resultAll = array('data' => null,'status' => 'DISTANCE');
            return json_encode($resultAll);
        } 
    }

    //tim dia diem theo ma dich vu
    public function findplace_service($id_service)
    {
        $result = DB::table('vnt_services')
                                    ->join('vnt_tourist_places as p','vnt_services.tourist_places_id','=','p.id')
                                    ->select('p.pl_latitude','p.pl_longitude')
                                    ->where('vnt_services.id','=',$id_service)->first();
        return $result;
    }

}
