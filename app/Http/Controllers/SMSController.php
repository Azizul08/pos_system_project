<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;
use App\Notifications\CustomerNotification;
use App\Notifications\SupplierNotification;
use App\NotificationTemplate;
use App\Restaurant\Booking;
use App\Transaction;
use App\Utils\NotificationUtil;
use App\Utils\TransactionUtil;
use Notification;
class SMSController extends Controller
{
    private $apiUrl = 'https://api.sms.net.bd';
    private $apiKey;

    protected $notificationUtil;

    protected $transactionUtil;

    /**
     * Constructor
     *
     * @param  NotificationUtil  $notificationUtil, TransactionUtil $transactionUtil
     * @return void
     */
    public function __construct(NotificationUtil $notificationUtil, TransactionUtil $transactionUtil)
    {
        $this->notificationUtil = $notificationUtil;
        $this->transactionUtil = $transactionUtil;

        $this->apiKey = env('SMS_NET_BD_API_KEY');
    }

    public function sendSMS()
    {   
        // dd('hlw');
        return view('sms_settings.sms_send');
    }

    public function postSendSMS(Request $request)
    {
        // check api key
        if (empty($this->apiKey)) {
            throw new \Exception('API key is required in .env file');
        }

        $url = "{$this->apiUrl}/sendsms";

        $message=$request->msg;
        $recipients=$request->to;
        $senderId = null;
        $params = [
            'api_key' => $this->apiKey,
            'msg' => $message,
            'to' => $recipients,
            'sender_id' => $senderId,
        ];
        // dd($params);
        $response = $this->makeRequest('POST', $url, $params);
        // dd( $response);
        return $this->handleResponse($response);
    }

    private function makeRequest($method, $url, $params)
    {
        if ($method === 'GET') {
            $response = Http::acceptJson()->get($url, $params);
        } else {
            $response = Http::asForm()->acceptJson()->post($url, $params);
        }

        return $response->json();
    }

    private function handleResponse($response)
    {
        if (isset($response['error']) && $response['error'] == 0) {
            //return $response['data'] ?? $response['msg'];
            // return 'SMS successfully sent';
            Session::flash('success','Message has been successfully sent!');
            return redirect('/send_sms');
        }

        // Log or handle the error as needed
        // For now, let's throw an exception with the error message
        throw new \Exception($response['msg'] ?? 'Unknown error');
    }

    public function postSendMsg(Request $request)
    {
        $customer_notifications = NotificationTemplate::customerNotifications();
        $supplier_notifications = NotificationTemplate::supplierNotifications();
        // dd($supplier_notifications);
         $data = $request->only(['to','msg']);

            $transaction_id = $request->input('transaction_id');
            $business_id = request()->session()->get('business.id');

            $transaction = ! empty($transaction_id) ? Transaction::find($transaction_id) : null;

            $orig_data = [
                'msg' => $data['msg'],
            ];

            if ($request->input('template_for') == 'new_booking') {
                $tag_replaced_data = $this->notificationUtil->replaceBookingTags($business_id, $orig_data, $transaction_id);
                $data['msg'] = $tag_replaced_data['msg'];
            } else {
                $tag_replaced_data = $this->notificationUtil->replaceTags($business_id, $orig_data, $transaction_id);
                $data['msg'] = $tag_replaced_data['msg'];
            }
            //dd($tag_replaced_data);

            // check api key
            if (empty($this->apiKey)) {
                throw new \Exception('API key is required in .env file');
            }

            $url = "{$this->apiUrl}/sendsms";
            $senderId = null;
            $params = [
                'api_key' => $this->apiKey,
                'msg' => $data['msg'],
                'to' => $data['to'],
                'sender_id' => $senderId,
            ];
            // dd($params);
        if (array_key_exists($request->input('template_for'), $customer_notifications)) {
            $response = $this->makeRequestMsg('POST', $url, $params);
            // dd( $response);
            return $this->handleResponseMsg($response);
        } elseif (array_key_exists($request->input('template_for'), $supplier_notifications)) {
            $response = $this->makeRequestMsg('POST', $url, $params);
            // dd( $response);
            return $this->handleResponseMsg($response);
        }
    }

    private function makeRequestMsg($method, $url, $params)
    {
        if ($method === 'GET') {
            $response = Http::acceptJson()->get($url, $params);
        } else {
            $response = Http::asForm()->acceptJson()->post($url, $params);
        }

        return $response->json();
    }

    private function handleResponseMsg($response)
    {
        if (isset($response['error']) && $response['error'] == 0) {
            //return $response['data'] ?? $response['msg'];
            // return 'SMS successfully sent';
            Session::flash('success','Message has been successfully sent!');
            return redirect('/sells');
        }

        // Log or handle the error as needed
        // For now, let's throw an exception with the error message
        throw new \Exception($response['msg'] ?? 'Unknown error');
    }
}
