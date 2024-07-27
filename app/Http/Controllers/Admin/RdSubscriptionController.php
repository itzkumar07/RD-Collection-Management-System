<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RdInstallment;
use App\Models\RdPlan;
use App\Models\RdSubscription;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;

interface RdSubscriptionInterface
{
    public function viewRdSubscriptionList();
    public function viewRdSubscriptionPreview($id);
    public function handleDownloadRecipt($id);
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
        $this->middleware('auth:admin')->except('logout');
    }

    /**
     * View RD Subscription List
     *
     * @return mixed
     */
    public function viewRdSubscriptionList(): mixed
    {
        try {

            $rd_subscriptions = RdSubscription::all();

            return view('admin.pages.rd-subscription.rd-subscription-list', [
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

            return view('admin.pages.rd-subscription.rd-subscription-preview', [
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
