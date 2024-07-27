<?php

namespace App\Http\Controllers\Web;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\RdInstallment;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RazorpayPaymentController extends Controller
{
    /**
     * Write code on Method
     *
     * @return View
     */
    public function index(): View
    {
        return view('razorpayView');
    }

    /**
     * Write code on Method
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $input = $request->all();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture([
                    'amount' => $payment['amount']
                ]); 
                
                $rd_subscription_payment = new RdInstallment();
                $rd_subscription_payment->rd_subscription_id = $request->input('rd_subscription_id');
                $rd_subscription_payment->method = PaymentMethod::PREPAID;
                $rd_subscription_payment->payment_status = PaymentStatus::COMPLETED;
                $rd_subscription_payment->date = Carbon::now();
                $rd_subscription_payment->amount = $request->input('amount');
                $rd_subscription_payment->transaction_id = $request->input('razorpay_payment_id');
                $rd_subscription_payment->meta_data = json_encode($response);
                $rd_subscription_payment->save();

                return redirect()->route('web.view.rdsubscription.preview', ['id' => $request->input('rd_subscription_id')])->with('message', [
                    'status' => 'success',
                    'title' => 'Payment successful',
                    'description' => 'Your payment is successfully completed'
                ]);

            } catch (Exception $exception) {
                return redirect()->route('web.view.rdsubscription.preview', [
                    'id' => $request->input('rd_subscription_id')
                ])->with('message', [
                    'status' => 'error',
                    'title' => 'Payment Failed',
                    'description' => $exception->getMessage()
                ]);
                
            }
        }
        
        return redirect()->route('web.view.rdsubscription.preview', ['id' => $request->input('rd_subscription_id')])->with('message', [
            'status' => 'error',
            'title' => 'Payment Failed',
            'description' => 'Your payment is failed please try again'
        ]);
    }
}
