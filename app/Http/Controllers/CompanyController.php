<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Http\Requests\{AddCompanyRequest,GenerateShortUrlRequest};

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $companies = $this->companyService->getCompanies();
        return Inertia::render('Dashboard', [
            'companies' => $companies,
        ]);
    }

    public function store(AddCompanyRequest $request)
        {
            try {
                $company = $this->companyService->addCompany($request->validated());
                return response()->json([
                    'success' => true,
                    'message' => 'Company added successfully.',
                    'data' => $company,
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                ], 500);
            }
        }

    public function generateShortUrl(int $companyId, GenerateShortUrlRequest $request)
    {
        try {
            if (auth()->user()->role === 'SuperAdmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to create short URLs.',
                ], 403);
            }
            
            $generateUrl = $this->companyService->generateShortUrl($companyId,$request->validated());
            
            if($generateUrl){
                 return response()->json([
                'success' => true,
                'message' => 'Generate Url SuccessFully.',
            ], 201);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong.',
            ], 500);
         
        } catch (Exception $e) {
            return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
            ], 500);
        }

    }

    public function openShortUrl($shortUrl)
    {
       $url = $this->companyService->getOrignalUrl($shortUrl);
       if($url){
            return redirect()->away($url);
       }
    }

    public function getShortUrl()
    {
        $shortUrls = $this->companyService->getShortUrls();
        return Inertia::render('ShortUrl', [
            'shortUrls' => $shortUrls,
        ]);
    }
}
