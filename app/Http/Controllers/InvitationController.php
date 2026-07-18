<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Http\Requests\InviteUserRequest;

class InvitationController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(int $companyId, InviteUserRequest $request)
    {   try{
        $this->companyService->sendInvitations($companyId,$request->validated());
        
        return response()->json([
                    'success' => true,
                    'message' => 'Invitation sent successfully.',
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                ], 500);
            }
    }

    public function acceptInvitation(String $token)
    {   try{
         
        $this->companyService->acceptInvitation($token);

        return redirect()
            ->route('login')
            ->with('success', 'Invitation accepted successfully. Please log in.');

            } catch (Exception $e) {
               \Log::error($e);

                abort(500,'Something went wrong');
            }
    }
}
