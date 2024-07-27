<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RdPlan;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

interface RdPlanInterface
{
    public function viewRdPlanList();
    public function viewRdPlanCreate();
    public function viewRdPlanUpdate($id);
    public function handleRdPlanCreate(Request $request);
    public function handleRdPlanUpdate(Request $request, $id);
    public function handleRdPlanDelete($id);
}

class RdPlanController extends Controller implements RdPlanInterface

{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * View Rd List
     *
     * @return mixed
     */
    public function viewRdPlanList(): mixed
    {
        try {
            $rdplans = RdPlan::all();

            return view('admin.pages.rdplan.rdplan-list', [
                'rdplans' => $rdplans
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
     * View RdPlan Create
     *
     * @return mixed
     */
    public function viewRdPlanCreate(): mixed
    {
        try {
            return view('admin.pages.rdplan.rdplan-create');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View RdPlan Update
     *
     * @return mixed
     */
    public function viewRdPlanUpdate($id): mixed
    {
        try {
            $rdplan = RdPlan::find($id);

            if (!$rdplan) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'RdPlan not found',
                    'description' => 'RdPlan not found with specified ID'
                ]);
            }

            return view('admin.pages.rdplan.rdplan-update', [
                'rdplan' => $rdplan
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
     * Handle RdPlan Create
     *
     * @return mixed
     */
    public function handleRdPlanCreate(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
                'description' => ['nullable', 'string', 'min:10', 'max:10000'],
                'tenure' => ['required', 'numeric', 'min:1', 'max:500'],
                'rate_of_interest' => ['required', 'numeric', 'min:1', 'max:100'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $rdplan = new RdPlan();
            $rdplan->name = $request->input('name');
            $rdplan->summary = $request->input('summary');
            $rdplan->description = $request->input('description');
            $rdplan->tenure = $request->input('tenure');
            $rdplan->rate_of_interest = $request->input('rate_of_interest');
            $rdplan->save();

            return redirect()->route('admin.view.rdplan.list')->with('message', [
                'status' => 'success',
                'title' => 'RD Plan created',
                'description' => 'The RD Plan is successfully created.'
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
     * Handle RdPlan Update
     *
     * @return mixed
     */
    public function handleRdPlanUpdate(Request $request, $id): RedirectResponse
    {
        try {

            $rdplan = RdPlan::find($id);

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
                'description' => ['nullable', 'string', 'min:10', 'max:10000'],
                'tenure' => ['required', 'numeric', 'min:1', 'max:500'],
                'rate_of_interest' => ['required', 'numeric', 'min:1', 'max:100'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $rdplan->name = $request->input('name');
            $rdplan->summary = $request->input('summary');
            $rdplan->description = $request->input('description');
            $rdplan->tenure = $request->input('tenure');
            $rdplan->rate_of_interest = $request->input('rate_of_interest');
            $rdplan->update();

            return redirect()->route('admin.view.rdplan.list')->with('message', [
                'status' => 'success',
                'title' => 'Changes saved',
                'description' => 'The changes are successfully saved.'
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
     * Handl RdPlan Delete
     *
     * @return mixed
     */
    public function handleRdPlanDelete($id): RedirectResponse
    {
        try {
            $rdplan = RdPlan::find($id);

            if (!$rdplan) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'RD Plan not found',
                    'description' => 'RD Plan not found with specified ID'
                ]);
            }

            $rdplan->delete();

            return redirect()->route('admin.view.rdplan.list')->with('message', [
                'status' => 'success',
                'title' => 'RD Plan deleted',
                'description' => 'The RD Plan is successfully deleted.'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
