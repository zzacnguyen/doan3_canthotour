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
use Auth;
use Validator;

class pageController extends Controller
{
    public function layindex()
    {
        return view('VietNamTour.content.index');
    }

    public function getindex()
    {   
        $placecount       = $this::count_city_service_all_image();

        $services_eat     = $this::getservicestake(1,8);
        $services_hotel   = $this::getservicestake(2,6);
        $services_tran    = $this::getservicestake(3,8);
        $services_see     = $this::getservicestake(4,8);
        $services_enter   = $this::getservicestake(5,8);

        $checkLogin = $this::checkLogin();

        // dd($services_hotel);
    	return view('VietNamTour.content.index',compact('placecount','services_hotel','services_eat','services_enter','services_see','services_tran','checkLogin'));
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
                $city = $this::FindServiceToCity($value->sv_id); // lay name city chua service
                if ($city == null) { $name_city = null;}
                else{
                    foreach ($city as $key => $c) {
                        $name_city = $c->name_city;
                    }
                }
                    
                $likes = DB::table('vnt_likes')->where('service_id', '=',$value->sv_id)->count();

                $ratings = DB::table('vnt_visitor_ratings')->where('service_id')->first();
                if (!empty($ratings)) {
                    $ponit_rating = $ratings->vr_rating;
                }else{ $ponit_rating = 0; }

                if (isset($value->hotel_number_star )) {
                    $hotel_number_star = $value->hotel_number_star;
                    $mang[] = array(
                        'id_service'        => $value->sv_id,
                        'name'              => $value->sv_name,
                        'hotel_number_star' => $value->hotel_number_star,
                        'description'       => $value->sv_description,
                        'image'             => $value->image_details_1,
                        'name_city'         => $name_city,
                        'sv_highest_price'  => $value->sv_highest_price,
                        'sv_lowest_price'   => $value->sv_lowest_price,
                        'like'              => $likes,
                        'view'              => $value->sv_counter_view,
                        'point'             => $value->sv_counter_point,
                        'rating'            => $ponit_rating,
                        'sv_type'           => $sv_types);
                }
                else{
                    $mang[] = array(
                        'id_service'        => $value->sv_id,
                        'name'              => $value->sv_name,
                        'image'             => $value->image_details_1,
                        'name_city'         => $name_city,
                        'sv_highest_price'  => $value->sv_highest_price,
                        'sv_lowest_price'   => $value->sv_lowest_price,
                        'like'              => $likes,
                        'view'              => $value->sv_counter_view,
                        'point'             => $value->sv_counter_point,
                        'rating'            => $ponit_rating,
                        'sv_type'           => $sv_types);
                }
                
            }
            if (isset($mang)) {return $mang;}else{ return null; }
        }
        else
            return null;
    }
    
    public function FindServiceToCity($idservice)
    {
        $result = DB::select("CALL find_serviceOfcity(?)",array($idservice));
        return $result;
    }


    //=============================== NEW ===========================================
    public function count_city_service_all()
    {
        $result = DB::select("CALL c_count_service_city_all()");
        return ($result);
    }


    public function count_city_service_all_image() //load anh len city voi service co point cao nhat
    {
        $result_city = DB::select("CALL c_count_service_city_all()");
        foreach ($result_city as $value) {
            $id_service = DB::select("CALL get_idServicePointMax_city(?)",array($value->id_city));
            foreach ($id_service as $v) {
                $id_sv = $v->id_service;
            }
            $image = DB::table('vnt_images')->where('service_id',$id_sv)->first();
            if ($image == null) {
                $img = null;
            }
            else{
                $img = $image->image_details_1;
            }
            $result[] = array(
                'id_city' => $value->id_city,
                'name_city' => $value->name_city,
                'num_service' => $value->num_service,
                'image' => $img
            );
        }
        return $result;
    }

    public function numberToK($num)
    {
        if ($num >= 1000) {
            $n = $num / 1000; // phan nguyen
            $d = $num % 1000; // phan du
            if ($d > 100) {
                $c = $d/100;
                return $n.$c."K";
            }
            else{
                return $n."K";
            }
        }
        else{
            return $num;
        }
    }



    //================================= LOGIN =====================================
    public function postLoginW(Request $request)
    {
        $messages = [
            'required' => 'Trường bắt buộc nhập',
            'username.min'    => 'Tài khoản có độ dài từ 4-20 ký tự'
        ];
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4',
            'password' => 'required'
        ],$messages);
        if ($validator->fails()) {
            return redirect('loginW')->withErrors($validator)->withInput();
        } 
        else 
        {
            $username = $request->input('username');
            $pass = $request->input('password');
            if( Auth::attempt(['username' => $username, 'password' => $pass])) {
                $placecount       = $this::count_city_service_all_image();

                $services_eat     = $this::getservicestake(1,8);
                $services_hotel   = $this::getservicestake(2,6);
                $services_tran    = $this::getservicestake(3,8);
                $services_see     = $this::getservicestake(4,8);
                $services_enter   = $this::getservicestake(5,8);

                $user = Auth::user();

                // dd($services_hotel);
                // return view('VietNamTour.content.index',compact('placecount','services_hotel','services_eat','services_enter','services_see','services_tran','user'));
                return redirect()->intended('/');
                // return Auth::user()->user_id;
            } else {
                return redirect()->back()->with(['erro'=>'Tên tài khoản hoặc mật khẩu không đúng','userold'=>$username]);
            }
        }
    }

    public function checkLogin()
    {
        $result = 0;
        if (Auth::check()) {
            $result = 1;
        }
        else{$result = -1;}
        return json_encode($result);
    }






    // ====== TIM KIEM ======
    public function searchServices_All($keyword)
    {
        $keyword_handing = str_replace("+", " ", $keyword);
        $result_eat = DB::select("select s.sv_id, s.sv_name,s.image_details_1,s.sv_description FROM sv_eat AS s WHERE s.sv_name LIKE '%$keyword_handing%'");
        $result['eat'] = $result_eat;

        $result_hotel = DB::select("select s.sv_id, s.sv_name,s.image_details_1,s.sv_description FROM sv_hotel AS s WHERE s.sv_name LIKE '%$keyword_handing%'");
        $result['hotel'] = $result_hotel;

        $result_tran = DB::select("select s.sv_id, s.sv_name,s.image_details_1,s.sv_description FROM sv_stranport AS s WHERE s.sv_name LIKE '%$keyword_handing%'");
        $result['tran'] = $result_tran;

        $result_see = DB::select("select s.sv_id, s.sv_name,s.image_details_1,s.sv_description FROM sv_sightseeting AS s WHERE s.sv_name LIKE '%$keyword_handing%'");
        $result['see'] = $result_see;

        $result_enter = DB::select("select s.sv_id, s.sv_name,s.image_details_1,s.sv_description FROM sv_entertaiment AS s WHERE s.sv_name LIKE '%$keyword_handing%'");
        $result['enter'] = $result_enter;

        return json_encode($result);
    }
}
