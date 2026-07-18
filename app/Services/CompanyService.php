<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\{InviteUserMail,UserCredentialsMail};
use App\Models\{Company, User,companyUser, Invitation,ShortUrl};


class CompanyService
{

    public function getCompanies($perPage = 10)
    {   

        $companies = Company::select('companies.id', 'name', 'is_owned');
        
        if (in_array(auth()->user()->role, ['Admin', 'Member'])) {
            $companies->addSelect('company_users.role')
                ->join('company_users', 'company_users.company_id', '=', 'companies.id')
                ->where('company_users.user_id', auth()?->user()?->id);
        }
        return $companies = $companies->latest('companies.id')
            ->paginate($perPage);
    
    }

    public function addCompany($request): Company
    {
        // Get Or create user If not exists
        $user = $this->getOrCreateUser($request);

        $company = Company::create([
            'name' => $request['name'],
            'is_owned' => true,
        ]);

        $this->companyUserCreate($company,$user,'Admin');
        return $company;
    }


    private function companyUserCreate($company,$user,$role='Admin'){
        
        return CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => $role,
        ]);
    }


    public function getOrCreateUser($request): User
    {
        // Check if user already exists
        $user  = User::where('email', $request['email'])->first();

        // create new user if not exists
        if (!$user) {
            $password =  Str::random(8); // default password created
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($password),
                'is_super_admin' => false,
                'role' => 'Admin'
            ]);
        }

        return $user;
    }

    public function sendInvitations(int $companyId, $request)
    {
        $company = Company::select('id','name')->where('id',$companyId)->first();
        if($company){
             $invitatation = Invitation::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'company_id' => $companyId,
            'role' => $request['role'],
            'invited_by' => auth()->user()->id,
            'token' => Str::uuid().Str::random(32),
        ]);

            if($invitatation){
                $url = route('invite.accept', ['token' => $invitatation->token]);
            return Mail::to($request['email'])->send(new InviteUserMail($request['name'], $company->name, auth()->user()->name, $url));
            }   
        }
       
        return false;            

    }        

    public function acceptInvitation($token){
        $invitation = Invitation::select('id','name','email','role','company_id')->whereNull('accepted_at')->where('token',$token)->with('company')->first();

        if (!$invitation) {
            return false;
        }

        $user =  User::where('email', $invitation->email)->first();
            if(!$user){
                $password =  Str::random(8); // default password created
                $user = User::create([
                    'name' => $invitation->name,
                    'email' => $invitation->email,
                    'password' => Hash::make($password),
                    'is_super_admin' => false,
                    'role' => $invitation->role
                ]);

                // company user create 

                $this->companyUserCreate($invitation->company,$user,$invitation->role);

                 // accepet the invitation
                $invitation->accepted_at = now();
                $invitation->save();

                Mail::to($user->email)->send(
                    new UserCredentialsMail(
                        $user->name,
                        $user->email,
                        $password,
                        $invitation->company->name
                    )
                );
               
            }
            return true;
    }

    public function generateShortUrl($companyId, $request){
        $company = Company::select('id','name')->where('id',$companyId)->first();
        if($company){
            return ShortUrl::create([
                'original_url' => $request['longUrl'],
                'user_id' => auth()?->user()?->id,
                'company_id' => $companyId,
                'short_url' => Str::uuid().Str::random(32)
            ]);

        }
        return false;
    }

    public function getOrignalUrl($shortUrl){
        $orignalUrl = ShortUrl::select('id','original_url')->where('short_url',$shortUrl)->first();
        if($orignalUrl){
            return $orignalUrl->original_url;
        }
        return false;
    }

    // get Short url
    public function getShortUrls($perPage = 10)
    {   

        $shortUrls = ShortUrl::select('short_urls.id','original_url',"short_url")
        ->join('companies', 'companies.id', '=', 'short_urls.company_id');

        $role = auth()->user()->role;
        if($role == 'SuperAdmin'){
            $shortUrls = $shortUrls
                    ->addSelect('users.name','companies.name as company_name')
                    ->join('users', 'users.id', '=', 'short_urls.user_id')
                    ->where('user_id',auth()->user()->id);
        } else if($role == 'Admin'){
                $shortUrls = $shortUrls
                    ->addSelect('users.name','companies.name as company_name')
                    ->join('users', 'users.id', '=', 'short_urls.user_id')
                    ->join('company_users', 'company_users.user_id', '=', 'users.id')
                    ->where('company_users.user_id',auth()->user()->id);
        } else {
            $shortUrls = $shortUrls->where('user_id',auth()->user()->id);
        }
    
        return $shortUrls = $shortUrls->latest('short_urls.id')
            ->paginate($perPage);
    
    }
}