<?php

namespace App\Http\Controllers;

use App\Exceptions\BudgetRequestAlreadyDiscardedException;
use App\Exceptions\BudgetRequestCantBePublishedException;
use App\HttpStatusCode;
use App\BudgetRequestCategory;
use App\User;
use App\BudgetRequest;
use App\BudgetRequestStatus;

use App\Exceptions\BudgetRequestNotFoundException;
use App\Exceptions\CategoryNotExistsException;
use App\Exceptions\MissingNecessaryParametersException;
use App\Exceptions\BudgetRequestIsNotPendingException;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BudgetRequestController extends Controller
{

    /**
     * Display list of all resources with a pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $email = null)
    {
        $maxResults = 2;
        if (is_null($email))
            $budgetRequests = BudgetRequest::jsonPaginate($maxResults);
        else
            $budgetRequests = BudgetRequest::allOfThisUser($email)->jsonPaginate($maxResults);

        return response()->json($budgetRequests, HttpStatusCode::OK);
    }

    /**
     * Store a new Budget Request.
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
                if (is_null($category))
                    throw new CategoryNotExistsException;

                $budgetRequest->budget_request_category_id = $category->id;
            }
        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()], HttpStatusCode::BAD_REQUEST);
        }

        $user = $this->createUserIfNotExistsOrUpdateIfItDoes($request);

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
    private function createUserIfNotExistsOrUpdateIfItDoes(Request $request) : User
    {
        $user = User::where('email', $request->email)->first();
        if (is_null($user)) {
            $user = new User;
            $user->email = $request->email;
        }

        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return $user;
    }

    /**
     * Update title, description and category params of a Budget Request if it has PENDING status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $budgetRequest = BudgetRequest::find($id);
            if (is_null($budgetRequest))
                throw new BudgetRequestNotFoundException;

            if (!$budgetRequest->isPending)
                throw new BudgetRequestIsNotPendingException;

            if ($request->exists('title'))
                $budgetRequest->title = $request->title;

            if ($request->exists('description'))
                $budgetRequest->description = $request->description;

            if ($request->exists('category')) {
                $category = BudgetRequestCategory::where('category', 'like', $request->category)->first();
                if (is_null($category))
                    throw new CategoryNotExistsException;

                $budgetRequest->budget_request_category_id = $category->id;
            }

            $budgetRequest->save();

            return response()->json($budgetRequest, HttpStatusCode::OK);
        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()], HttpStatusCode::BAD_REQUEST);
        }
    }

    /**
     * Publish a budget request which complies the requirements of publishment.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish($id)
    {
        $budgetRequest = BudgetRequest::find($id);

        try {
            if (is_null($budgetRequest))
                throw new BudgetRequestNotFoundException;

            if (!$budgetRequest->canBePublished)
               throw new BudgetRequestCantBePublishedException;
        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()], HttpStatusCode::BAD_REQUEST);
        }

        $budgetRequest->budget_request_status_id = BudgetRequestStatus::PUBLISHED_ID;
        $budgetRequest->save();

        return response()->json($budgetRequest, HttpStatusCode::OK);
    }

    /**
     * Discard a budget request which is not already discarded.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function discard($id)
    {
        $budgetRequest = BudgetRequest::find($id);

        try {
            if (is_null($budgetRequest))
                throw new BudgetRequestNotFoundException;

            if ($budgetRequest->wasDiscarded)
                throw new BudgetRequestAlreadyDiscardedException;

        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()], HttpStatusCode::BAD_REQUEST);
        }

        $budgetRequest->budget_request_status_id = BudgetRequestStatus::DISCARDED_ID;
        $budgetRequest->save();

        return response()->json($budgetRequest, HttpStatusCode::OK);
    }
}
