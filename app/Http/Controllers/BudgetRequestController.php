<?php

namespace App\Http\Controllers;

use App\HttpStatusCode;
use App\BudgetRequestCategory;
use App\Exceptions\CategoryNotExistsException;
use App\Exceptions\MissingNecessaryParametersException;
use App\User;
use App\BudgetRequest;
use App\BudgetRequestStatus;
use App\Http\Requests\BudgetRequestStoreRequest;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Bool_;

class BudgetRequestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(BudgetRequest::all(), HttpStatusCode::OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $budgetRequest = new BudgetRequest;

        try {
            // Throw exception if some parameter is missing
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'description' => 'required',
                'email' => 'required|email',
                'address' => 'required'
            ]);
            if ($validator->fails())
                throw new MissingNecessaryParametersException;

            // Throw exception if there aren't a category with that name
            if ($request->exists('category')) {
                $category = BudgetRequestCategory::where('category', 'like', $request->category)->first();
                if ($category == null)
                    throw new CategoryNotExistsException;

                $budgetRequest->budget_request_category_id = $category->id;
            }
        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()], HttpStatusCode::BAD_REQUEST);
        }

        $user = $this->createUserIfNotExistsOrUpdateIfDoes($request);

        $budgetRequest->user_id = $user->id;
        $budgetRequest->budget_request_status_id = BudgetRequestStatus::PENDING_ID;
        $budgetRequest->description = $request->description;
        if ($request->exists('title')) {
            $budgetRequest->title = $request->title;
        }
        $budgetRequest->save();

        return response()->json($budgetRequest, HttpStatusCode::CREATED);
    }

    /**
     * Create User if not exists or update phone and address if it does.
     * @param Request $request
     * @return User
     */
    private function createUserIfNotExistsOrUpdateIfDoes(Request $request) : User
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            $user = new User;
            $user->email = $request->email;
        }

        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Evaluate Request Parameters (Throw exception if needed)

        // Select BudgetRequest by ID (Throw exception if not exists on DB)

        // Check if it has a pending status (Throw exception if it hasn't)

        // Update parameters
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
