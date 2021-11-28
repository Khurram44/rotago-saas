<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Employeesetting;
use App\Location;
use App\Profile;
use App\Settings;
use App\Utility;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\json_decode;

class EmployeesettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $userId = Auth::user()->id;
        $user = Auth::user();
        $created_by = $user->get_created_by();

        $date = date('Y-01-01');

        if(Auth::user()->type != 'super admin')
        {
            $profile = Employee::whereRaw('is_delete = 0')->WhereRaw('id ='. $userId.'')->first();        
            $company_setting = [];
            $company_setting['company_currency_symbol_position'] = 'pre';
            if(!empty($profile->company_setting) && Auth::user()->type == 'company')
            {
                $setting = json_decode($profile->company_setting,true);
                $company_setting['emp_show_rotas_price'] = (!empty($setting['emp_show_rotas_price'])) ? $setting['emp_show_rotas_price'] : 0 ;                
                $company_setting['emp_hide_rotas_hour']  = (!empty($setting['emp_hide_rotas_hour'])) ? $setting['emp_hide_rotas_hour'] : 0 ;                
                $company_setting['include_unpublished_shifts']  = (!empty($setting['include_unpublished_shifts'])) ? $setting['include_unpublished_shifts'] : 0 ;                
                $company_setting['emp_show_avatars_on_rota'] = (!empty($setting['emp_show_avatars_on_rota'])) ? $setting['emp_show_avatars_on_rota'] : 0 ;
                $company_setting['emp_only_see_own_rota'] = (!empty($setting['emp_only_see_own_rota'])) ? $setting['emp_only_see_own_rota'] : 0 ;
                $company_setting['emp_can_see_all_locations'] = (!empty($setting['emp_can_see_all_locations'])) ? $setting['emp_can_see_all_locations'] : 0 ;
                $company_setting['company_week_start'] = (!empty($setting['company_week_start'])) ? $setting['company_week_start'] : null ;
                $company_setting['company_time_format'] = (!empty($setting['company_time_format'])) ? $setting['company_time_format'] : null ;
                $company_setting['company_date_format'] = (!empty($setting['company_date_format'])) ? $setting['company_date_format'] : 'Y-m-d' ;
                $company_setting['company_currency_symbol'] = (!empty($setting['company_currency_symbol'])) ? $setting['company_currency_symbol'] : '$' ;
                $company_setting['company_currency_symbol_position'] = (!empty($setting['company_currency_symbol_position'])) ? $setting['company_currency_symbol_position'] : 'pre' ;
                $company_setting['leave_start_month'] = (!empty($setting['leave_start_month'])) ? $setting['leave_start_month'] : 1 ;                
                $company_setting['break_paid'] = (!empty($setting['break_paid'])) ? $setting['break_paid'] : 'paid' ;
                $company_setting['see_note'] = (!empty($setting['see_note'])) ? $setting['see_note'] : null ;
                $company_setting['enable_availability'] = (!empty($setting['enable_availability'])) ? $setting['enable_availability'] : 0 ;
                $company_setting['employees_can_set_availability'] = (!empty($setting['employees_can_set_availability'])) ? $setting['employees_can_set_availability'] : 0 ;
            }
            $settings = Utility::settings();
            return view('employeesetting.index',compact('profile','company_setting','settings'));
        }
        else
        {
            $profile = Employee::whereRaw('is_delete = 0')->WhereRaw('id ='. $userId.'')->first();        
            $settings = Utility::settings();
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            return view('employeesetting.superadminsetting',compact('profile','settings','admin_payment_setting'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employeesetting  $employeesetting
     * @return \Illuminate\Http\Response
     */
    public function show(Employeesetting $employeesetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employeesetting  $employeesetting
     * @return \Illuminate\Http\Response
     */
    public function edit(Employeesetting $employeesetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employeesetting  $employeesetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employeesetting $employeesetting, $id)
    {
        $user = Auth::user();
        $created_by = $user->get_created_by();
        
        $company_setting = Employee::find($id);
        if(\Auth::user()->type == 'super admin')
        {
            if($request->logo)
            {
                $request->validate(
                    [
                        'logo' => 'image|mimes:png|max:20480',
                    ]
                );

                $logoName = 'logo.png';
                $path     = $request->file('logo')->storeAs('uploads/logo/', $logoName);

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                    $logoName,
                                                                                                                                                    'logo',
                                                                                                                                                    $created_by,
                                                                                                                                                ]
                );
            }
            if($request->favicon)
            {
                $request->validate(
                    [
                        'favicon' => 'image|mimes:png|max:20480',
                    ]
                );
                $favicon = 'favicon.png';
                $path    = $request->file('favicon')->storeAs('uploads/logo/', $favicon);
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                    $favicon,
                                                                                                                                                    'favicon',
                                                                                                                                                    $created_by,
                                                                                                                                                ]
                );
            }

            
            $arrEnv = [
                'SITE_RTL' => !isset($request->SITE_RTL) ? 'off' : 'on',
            ];
            Utility::setEnvironmentValue($arrEnv);
            
            if(!empty($request->title_text) || !empty($request->footer_text) || !empty($request->default_language))
            {
                $post = $request->all();
                unset($post['_token'], $post['logo'], $post['small_logo'], $post['favicon']);
                
                if(!isset($request->display_landing_page))
                {
                    $post['display_landing_page'] = 'off';
                }

                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                        $data,
                                                                                                                                                        $key,
                                                                                                                                                        $created_by,
                                                                                                                                                    ]
                    );
                }
            }
        }
        else if(\Auth::user()->type == 'company')
        {
            if($request->company_logo)
            {

                $request->validate(
                    [
                        'company_logo' => 'image|mimes:png|max:20480',
                    ]
                );

                $logoName     = $user->id . '_logo.png';
                $path         = $request->file('company_logo')->storeAs('uploads/logo/', $logoName);

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $logoName,
                                                                                                                                                 'company_logo',
                                                                                                                                                 $created_by,
                                                                                                                                             ]
                );
            }

            if($request->company_favicon)
            {
                $request->validate(
                    [
                        'company_favicon' => 'image|mimes:png|max:20480',
                    ]
                );
                $favicon = $user->id . '_favicon.png';
                $path    = $request->file('company_favicon')->storeAs('uploads/logo/', $favicon);

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $favicon,
                                                                                                                                                 'company_favicon',
                                                                                                                                                 $created_by,
                                                                                                                                             ]
                );
            }

            if(!empty($request->title_text))
            {
                $post = $request->all();

                unset($post['_token'], $post['company_logo'], $post['company_small_logo'], $post['company_favicon']);
                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                     $data,
                                                                                                                                                     $key,
                                                                                                                                                     $created_by,
                                                                                                                                                 ]
                    );
                }
            }

            if($request->form_type == 'rotas_setting'  && Auth::user()->type == 'company')
            {
                $company_setting = User::find(Auth::user()->id);                
                $setting['emp_show_rotas_price'] = (!empty($request->emp_show_rotas_price)) ? $request->emp_show_rotas_price : 0 ;                  
                $setting['emp_hide_rotas_hour'] = (!empty($request->emp_hide_rotas_hour)) ? $request->emp_hide_rotas_hour : 0 ;                  
                $setting['include_unpublished_shifts'] = (!empty($request->include_unpublished_shifts)) ? $request->include_unpublished_shifts : 0 ;                  
                $setting['emp_show_avatars_on_rota'] = (!empty($request->emp_show_avatars_on_rota)) ? $request->emp_show_avatars_on_rota : 0 ;
                $setting['emp_only_see_own_rota'] = (!empty($request->emp_only_see_own_rota)) ? $request->emp_only_see_own_rota : 0 ;
                $setting['emp_can_see_all_locations'] = (!empty($request->emp_can_see_all_locations)) ? $request->emp_can_see_all_locations : 0 ;
                $setting['company_week_start'] = (!empty($request->company_week_start)) ? $request->company_week_start : '' ;
                $setting['company_time_format'] = (!empty($request->company_time_format)) ? $request->company_time_format : '' ;
                $setting['company_date_format'] = (!empty($request->company_date_format)) ? $request->company_date_format : 'Y-m-d' ;
                $setting['company_currency_symbol'] = (!empty($request->company_currency_symbol)) ? $request->company_currency_symbol : '$' ;
                $setting['company_currency_symbol_position'] = (!empty($request->company_currency_symbol_position)) ? $request->company_currency_symbol_position : 'pre' ;
                $setting['leave_start_month'] = (!empty($request->leave_start_month)) ? $request->leave_start_month : 01 ;
                $setting['break_paid'] = (!empty($request->break_paid)) ? $request->break_paid : 'paid' ;
                $setting['see_note'] = (!empty($request->see_note)) ? $request->see_note : '' ;                    
                $setting['employees_can_set_availability'] = (!empty($request->employees_can_set_availability)) ? $request->employees_can_set_availability : 0 ;                        
                if(!(empty($setting)))
                {
                    $company_setting->company_setting = json_encode($setting);
                }
                $company_setting->save();
            }
            else
            {
                return redirect()->back()->with('Error', __('Permission denied'));
            }
        }
        
        return redirect()->back()->with('success', __('Setting Update Successfully'));
    }

    
    public function saveEmailSettings(Request $request, Employeesetting $employeesetting)
    {
        if(Auth::user()->type == 'company' || Auth::user()->type == 'super admin')
        {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:50',
                    'mail_host' => 'required|string|max:50',
                    'mail_port' => 'required|string|max:50',
                    'mail_username' => 'required|string|max:50',
                    'mail_password' => 'required|string|max:50',
                    'mail_encryption' => 'required|string|max:50',
                    'mail_from_address' => 'required|string|max:50',
                    'mail_from_name' => 'required|string|max:50',
                ]
            );
            $arrEnv = [
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_NAME' => $request->mail_from_name,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            ];
            Utility::setEnvironmentValue($arrEnv);
            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employeesetting  $employeesetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employeesetting $employeesetting)
    {
        //
    }

    public function testMail()
    {
        return view('employeesetting.test_mail');
    }

    public function testSendMail(Request $request)
    {
        if(Auth::user()->type == 'company' || Auth::user()->type == 'super admin')
        {
            
            if(!empty($request->email))
            {            
                try
                {
                    Mail::to($request->email)->send(new TestMail());
                }
                catch(\Exception $e)
                {
                    dd($e->getMessage());
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }                
            }
            return redirect()->back()->with('success', __('Email send Successfully.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function savePaymentSettings(Request $request)
    {
        // dd($request->all());
        $userId = Auth::user()->id;
        $user = Auth::user();
        $created_by = $user->get_created_by();

        if(Auth::user()->type == 'super admin')
        {
            $request->validate(
                [
                    'currency' => 'required|string|max:255',
                    'currency_symbol' => 'required|string|max:255',
                ]
            );

            // if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
            // {
            //     $request->validate(
            //         [
            //             'stripe_key' => 'required|string|max:255',
            //             'stripe_secret' => 'required|string|max:255',
            //         ]
            //     );
            // }            
            // elseif(isset($request->enable_paypal) && $request->enable_paypal == 'on')
            // {
            //     $request->validate(
            //         [
            //             'paypal_mode' => 'required|string',
            //             'paypal_client_id' => 'required|string',
            //             'paypal_secret_key' => 'required|string',
            //         ]
            //     );
            // }


            $arrEnv = [
                'CURRENCY_SYMBOL' => $request->currency_symbol,
                'CURRENCY' => $request->currency
            ];
            Utility::setEnvironmentValue($arrEnv);

            // $post = $request->all();
            // unset($post['_token'], $post['stripe_key'], $post['stripe_secret']);

            // foreach($post as $key => $data)
            // {
            //     DB::insert(
            //         'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
            //                                                                                                                                                                      $data,
            //                                                                                                                                                                      $key,
            //                                                                                                                                                                      $created_by,
            //                                                                                                                                                                      date('Y-m-d H:i:s'),
            //                                                                                                                                                                      date('Y-m-d H:i:s'),
            //                                                                                                                                                                  ]
            //     );
            // }

            // dd($request->all());
            self::adminPaymentSettings($request);

            return redirect()->back()->with('success', __('Payment setting successfully saved.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function adminPaymentSettings($request)
    {


        if(isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on')
        {

            $post['is_stripe_enabled']     = $request->is_stripe_enabled;
            $post['stripe_secret']         = $request->stripe_secret;
            $post['stripe_key']            = $request->stripe_key;
        }
        else
        {
            $post['is_stripe_enabled'] = 'off';
        }

        if(isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on')
        {
            $request->validate(
                [
                    'paypal_mode' => 'required|string',
                    'paypal_client_id' => 'required|string',
                    'paypal_secret_key' => 'required|string',
                ]
            );

            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode']       = $request->paypal_mode;
            $post['paypal_client_id']  = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        }
        else
        {
            $post['is_paypal_enabled'] = 'off';
        }

        // dd($post, 'sd');

        if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
        {
            $request->validate(
                [
                    'paystack_public_key' => 'required|string',
                    'paystack_secret_key' => 'required|string',
                ]
            );
            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        }
        else
        {
            $post['is_paystack_enabled'] = 'off';
        }

        if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
        {
            $request->validate(
                [
                    'flutterwave_public_key' => 'required|string',
                    'flutterwave_secret_key' => 'required|string',
                ]
            );
            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        }
        else
        {
            $post['is_flutterwave_enabled'] = 'off';
        }
        if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
        {
            $request->validate(
                [
                    'razorpay_public_key' => 'required|string',
                    'razorpay_secret_key' => 'required|string',
                ]
            );
            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        }
        else
        {
            $post['is_razorpay_enabled'] = 'off';
        }

        if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
        {
            $request->validate(
                [
                    'mercado_app_id' => 'required|string',
                    'mercado_secret_key' => 'required|string',
                ]
            );
            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_app_id']     = $request->mercado_app_id;
            $post['mercado_secret_key'] = $request->mercado_secret_key;
        }
        else
        {
            $post['is_mercado_enabled'] = 'off';
        }

        if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
        {
            $request->validate(
                [
                    'paytm_mode' => 'required',
                    'paytm_merchant_id' => 'required|string',
                    'paytm_merchant_key' => 'required|string',
                    'paytm_industry_type' => 'required|string',
                ]
            );
            $post['is_paytm_enabled']    = $request->is_paytm_enabled;
            $post['paytm_mode']          = $request->paytm_mode;
            $post['paytm_merchant_id']   = $request->paytm_merchant_id;
            $post['paytm_merchant_key']  = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        }
        else
        {
            $post['is_paytm_enabled'] = 'off';
        }
        if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
        {
            $request->validate(
                [
                    'mollie_api_key' => 'required|string',
                    'mollie_profile_id' => 'required|string',
                    'mollie_partner_id' => 'required',
                ]
            );
            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key']    = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        }
        else
        {
            $post['is_mollie_enabled'] = 'off';
        }

        if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
        {
            $request->validate(
                [
                    'skrill_email' => 'required|email',
                ]
            );
            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email']      = $request->skrill_email;
        }
        else
        {
            $post['is_skrill_enabled'] = 'off';
        }

        if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
        {
            $request->validate(
                [
                    'coingate_mode' => 'required|string',
                    'coingate_auth_token' => 'required|string',
                ]
            );

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode']       = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        }
        else
        {
            $post['is_coingate_enabled'] = 'off';
        }

        foreach($post as $key => $data)
        {

            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];
            \DB::insert(
                'insert into admin_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );

        }
    }

    public function pusherSetting(Request $request)
    {
        $ENABLE_CHAT = (!empty($request->enable_chat) && $request->enable_chat == 'on') ? 'on' : 'off';
        $PUSHER_APP_ID = $request->pusher_app_id;
        $PUSHER_APP_KEY = $request->pusher_app_key;
        $PUSHER_APP_SECRET = $request->pusher_app_secret;
        $PUSHER_APP_CLUSTER = $request->pusher_app_cluster;

        if($ENABLE_CHAT == 'on')
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'pusher_app_id' => 'required|string',
                    'pusher_app_key' => 'required|string',
                    'pusher_app_secret' => 'required|string',
                    'pusher_app_cluster' => 'required|string',
                ]
            );
    
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $ENABLE_CHAT = (!empty($request->enable_chat) && $request->enable_chat == 'on') ? 'on' : 'off';
            $PUSHER_APP_ID = $request->pusher_app_id;
            $PUSHER_APP_KEY = $request->pusher_app_key;
            $PUSHER_APP_SECRET = $request->pusher_app_secret;
            $PUSHER_APP_CLUSTER = $request->pusher_app_cluster;
            
            $arrEnv = [
                'ENABLE_CHAT' => $ENABLE_CHAT,
                'PUSHER_APP_ID' => $PUSHER_APP_ID,
                'PUSHER_APP_KEY' => $PUSHER_APP_KEY,
                'PUSHER_APP_SECRET' => $PUSHER_APP_SECRET,
                'PUSHER_APP_CLUSTER' => $PUSHER_APP_CLUSTER,
            ];
            Utility::setEnvironmentValue($arrEnv);
        }
        else{
            $arrEnv = [
                'ENABLE_CHAT' => 'off',
                'PUSHER_APP_ID' => '',
                'PUSHER_APP_KEY' => '',
                'PUSHER_APP_SECRET' => '',
                'PUSHER_APP_CLUSTER' => '',
            ];
            Utility::setEnvironmentValue($arrEnv);
        }
        return redirect()->back()->with('success', __('Setting successfully updated.'));        
    }
}
