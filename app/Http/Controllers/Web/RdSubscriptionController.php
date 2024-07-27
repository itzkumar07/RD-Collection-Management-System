<?php

namespace App\Http\Controllers\Web;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\RdInstallment;
use App\Models\RdPlan;
use App\Models\RdSubscription;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;
use Barryvdh\DomPDF\Facade\Pdf;

interface RdSubscriptionInterface
{
    public function viewRdPlanList();
    public function viewRdSubscriptionList();
    public function viewRdSubscriptionCreate();
    public function viewRdSubscriptionPreview($id);
    public function handleRdSubscriptionCreate(Request $request);
    public function viewRdInstallmentCreate($rd_subscription_id);
    public function handleRdInstallmentCreate(Request $request);
}

class RdSubscriptionController extends Controller implements RdSubscriptionInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('logout');
    }

    /**
     * View RD Plan List
     *
     * @return mixed
     */
    public function viewRdPlanList(): mixed
    {
        try {

            $rd_plans = RdPlan::all();

            return view('web.pages.rd-subscription.rd-plan-list', [
                'rd_plans' => $rd_plans
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View RD Subscription List
     *
     * @return mixed
     */
    public function viewRdSubscriptionList(): mixed
    {
        try {

            $rd_subscriptions = RdSubscription::where('user_id', auth()->user()->id)->get();

            return view('web.pages.rd-subscription.rd-subscription-list', [
                'rd_subscriptions' => $rd_subscriptions
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View RD Subscription Create
     *
     * @return mixed
     */
    public function viewRdSubscriptionCreate(): mixed
    {
        try {

            $rd_plans = RdPlan::all();

            return view('web.pages.rd-subscription.rd-subscription-create', [
                'rd_plans' => $rd_plans
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View RD Subscription Preview
     *
     * @return mixed
     */
    public function viewRdSubscriptionPreview($id): mixed
    {
        try {
            
            $rd_subscription = RdSubscription::find($id);

            if (!$rd_subscription) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'RD Subscription not found',
                    'description' => 'RD Subscription not found with specified ID'
                ]);
            }
            
            $rd_plan = RdPlan::find($rd_subscription->rd_plan_id);
            $rd_installments = RdInstallment::where('rd_subscription_id', $rd_subscription->id)->get();

            return view('web.pages.rd-subscription.rd-subscription-preview', [
                'rd_subscription' => $rd_subscription,
                'rd_plan' => $rd_plan,
                'rd_installments' => $rd_installments
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle RD Subscription Create
     *
     * @return mixed
     */
    public function handleRdSubscriptionCreate(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'email' => ['required', 'string', 'email',  'min:1', 'max:250'],
                'phone' => ['required', 'numeric', 'digits:10'],
                'start_date' => ['required', 'string'],
                'payment_date' => ['required', 'string'],
                'instalment_amount' => ['required', 'numeric','min:1' ,'max:10000000'],
                'rd_plan_id' => ['required', 'string', 'exists:rd_plans,id'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $rd_plan = RdPlan::find($request->input('rd_plan_id'));

            $rd_subscription = new RdSubscription();
            $rd_subscription->ref_no = "RD". time();
            $rd_subscription->name = $request->input('name');
            $rd_subscription->email = $request->input('email');
            $rd_subscription->phone = $request->input('phone');
            $rd_subscription->start_date = $request->input('start_date');
            $rd_subscription->payment_date = $request->input('payment_date');
            $rd_subscription->instalment_amount = $request->input('instalment_amount');
            $rd_subscription->end_date = Carbon::parse($request->input('start_date'))->addMonths($rd_plan->tenure);
            $rd_subscription->rd_plan_id = $request->input('rd_plan_id');
            $rd_subscription->user_id = auth()->user()->id;
            $rd_subscription->save();

            return redirect()->route('web.view.rdsubscription.preview',['id' => $rd_subscription->id])->with('message', [
                'status' => 'success',
                'title' => 'RD Subscription created',
                'description' => 'The RD subscription is successfully created.'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View RD Installment Create
     *
     * @return mixed
     */
    public function viewRdInstallmentCreate($rd_subscription_id): mixed
    {
        try {

            $rd_subscription = RdSubscription::find($rd_subscription_id);

            if (!$rd_subscription) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'RD Subscription not found',
                    'description' => 'RD Subscription not found with specified ID'
                ]);
            }

            $payment_methods = PaymentMethod::class;

            return view('web.pages.rd-subscription.rd-subscription-installment-create', [
                'payment_methods' => $payment_methods,
                'rd_subscription' => $rd_subscription
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle RD Installment Create
     *
     * @return mixed
     */
    public function handleRdInstallmentCreate(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(), [
                'rd_subscription_id' => ['required', 'string', 'exists:rd_subscriptions,id'],
                'method' => ['required', 'string', new Enum(PaymentMethod::class)],
                'date' => ['required', 'string'],
                'amount' => ['required', 'numeric','min:1' ,'max:10000000'],
                'remark' => ['nullable', 'string', 'min:1', 'max:500'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $rd_subscription_payment = new RdInstallment();
            $rd_subscription_payment->rd_subscription_id = $request->input('rd_subscription_id');
            $rd_subscription_payment->method = $request->input('method');
            $rd_subscription_payment->payment_status = PaymentStatus::COMPLETED;
            $rd_subscription_payment->date = $request->input('date');
            $rd_subscription_payment->amount = $request->input('amount');
            $rd_subscription_payment->remark = $request->input('remark');
            $rd_subscription_payment->save();
            
            return redirect()->route('web.view.rdsubscription.preview',['id' => $request->input('rd_subscription_id')])->with('message', [
                'status' => 'success',
                'title' => 'RD Installment Paid',
                'description' => 'The RD Installment is successfully paid.'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Download RD Subscription Recipt
     *
     * @return mixed
     */
    public function handleDownloadRecipt($id): mixed
    {
        try {

            $rd_subscription = RdSubscription::find($id);

            if (!$rd_subscription) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'RD Subscription not found',
                    'description' => 'RD Subscription not found with specified ID'
                ]);
            }
            
            $rd_plan = RdPlan::find($rd_subscription->rd_plan_id);
            $rd_installments = RdInstallment::where('rd_subscription_id', $rd_subscription->id)->get();

            $pdf = Pdf::loadView('document.rd-subscription-recipt', [
                'rd_subscription' => $rd_subscription,
                'rd_installments' => $rd_installments,
                'rd_plan' => $rd_plan
            ]);
            return $pdf->stream();

        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
