<?php

namespace App\Http\Controllers\Admin;

use App\Exporters\ResourceExporter;
use App\Member;
use App\Permissions\UserPermissions;
use App\Settings\UserSettings;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $redirect;
    public $rules;
    public $perPage;
    public $orderByFields;
    public $orderCriteria;
    protected $settingsKey;
    protected $policies;
    protected $policyOwnerClass;
    protected $permissionsKey;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->redirect = route('admin.home');
        $this->rules = User::$rules;
        $this->perPage = 25;
        $this->orderByFields = ['first_name', 'last_name', 'username', 'email', 'active', 'created_at', 'updated_at'];
        $this->orderCriteria = ['asc', 'desc'];
        $this->settingsKey = 'users';
        $this->policies = UserPermissions::getPolicies();
        $this->policyOwnerClass = User::class;
        $this->permissionsKey = UserPermissions::getModelShortName($this->policyOwnerClass);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        if ( $request->user()->can('read', $this->policyOwnerClass) ) {
            $user = $request->user();

            $orderBy = in_array(strtolower($request->orderBy), $this->orderByFields) ? strtolower($request->orderBy) : $this->orderByFields[0];
            $order = in_array(strtolower($request->order), $this->orderCriteria) ? strtolower($request->order) : $this->orderCriteria[0];
            $perPage = (int) $request->perPage ?: $this->perPage;

            if ( ! $request->ajax() ) {
                return view('admin.users')->with(['settingsKey' => $this->settingsKey, 'permissionsKey' => $this->permissionsKey]);
            }
            else {
                $settings = UserSettings::getSettings($user->id, $this->settingsKey, $orderBy, $order, $perPage, true);
                $search = strtolower($request->search);

                $resources = $search
                    ? User::getSearchResults($search, $settings["{$this->settingsKey}_per_page"])
                    : User::getResources($settings["{$this->settingsKey}_order_by"], $settings["{$this->settingsKey}_order"], $settings["{$this->settingsKey}_per_page"]);

                if ( $resources->count() )
                    return response()->json(compact('resources'));
                else
                    return response()->json(['error' => 'No records found'], 404);
            }
        }
        else {
            if ( $request->ajax() )
                return response()->json(['error' => 'You are not authorised to view this page.'], 403);
            else
                return redirect($this->redirect);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return User|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ( $request->user()->can('create', $this->policyOwnerClass) ) {
            $this->validate($request, $this->rules);

            $meta = json_encode(['role' => User::getDefaultRole()]);

            $user = new User();
            $user->first_name = trim($request->first_name);
            $user->last_name = trim($request->last_name);
            $user->username = trim($request->username);
            $user->email = strtolower(trim($request->email));
            $user->password = bcrypt($request->password_confirmation);
            $user->active = $request->active ? 1 : 0;
            $user->meta = $meta;
            $user->save();

            return $user;
        }
        else
            return response()->json(['error' => 'You are not authorised to perform this action.'], 403);
    }

    /**
     * Show resource for editing
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request)
    {
        $resource = User::findResource( (int) $id);
        $currentUser = $request->user();

        if ( $resource ) {
            if ( $resource->id === $currentUser->id )
                return response()->json(['error' => 'Cheeky, can\'t do that here. Please go to your profile.'], 403);

            if ( ! $currentUser->can('update', $this->policyOwnerClass) || ( $resource->is_super_admin && ! $currentUser->is_super_admin ) )
                return response()->json(['error' => 'You are not authorised to perform this action.'], 403);

            if ( $request->has('permissions') && $request->permissions )
                return $this->editUserPermissions($resource, $currentUser);

            return response()->json(compact('resource'));
        }

        return response()->json(['error' => 'User does not exist'], 404);
    }

    /**
     * Show resource permissions for editing
     * @param User $resource
     * @param User $currentUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUserPermissions(User $resource, User $currentUser)
    {
        $registeredPolicies = $this->policies;
        $policies = [];

        if ( count($registeredPolicies ) ) {
            foreach( $registeredPolicies as $policyOwnerClass => $policy ) {

                $permissionsKey = UserPermissions::getModelShortName($policyOwnerClass);

                $policies[$permissionsKey] = [
                    'create' => [
                        'user' => $currentUser->can('create', $policyOwnerClass),
                        'resource' => $resource->can('create', $policyOwnerClass),
                    ],
                    'read' => [
                        'user' => $currentUser->can('read', $policyOwnerClass),
                        'resource' => $resource->can('read', $policyOwnerClass),
                    ],
                    'update' => [
                        'user' => $currentUser->can('update', $policyOwnerClass),
                        'resource' => $resource->can('update', $policyOwnerClass),
                    ],
                    'delete' => [
                        'user' => $currentUser->can('delete', $policyOwnerClass),
                        'resource' => $resource->can('delete', $policyOwnerClass),
                    ],
                ];
            }
        }

        return response()->json(compact('resource', 'policies'));
    }

    /**
     * Update the specified resource
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $resource = User::findResource( (int) $id);
        $currentUser = $request->user();

        if ( $resource ) {
            if ( ! $currentUser->can('update', $this->policyOwnerClass) || ( $resource->is_super_admin && ! $currentUser->is_super_admin ) )
                return response()->json(['error' => 'You are not authorised to perform this action.'], 403);

            if ( $request->has('permissions') && $request->permissions ) {
                $superAdmin = $request->get('is_super_admin');
                $newPolicies = $request->get('newPolicies');
                $this->updatePermissions($resource, $currentUser, $superAdmin, $newPolicies);
            }
            else {
                $rules = $this->rules;

                if ( strtolower($resource->email) == strtolower(trim($request->email)) )
                    $rules['email'] = str_replace("|unique:users|unique:members", '', $rules['email'] );
                if ( strtolower($resource->username) == strtolower(trim($request->username)))
                    $rules['username'] = str_replace("|unique:users|unique:members", '', $rules['username']);
                if ( ! $request->has('password') && ! $request->has('password_confirmation') )
                    unset($rules['password']);

                $this->validate($request, $rules);

                $resource->first_name = trim($request->first_name);
                $resource->last_name = trim($request->last_name);
                $resource->username = trim($request->username);
                $resource->email = strtolower(trim($request->email));
                $resource->active = $request->active ? 1 : 0;
                if ( $request->has('password_confirmation') )
                    $resource->password = bcrypt($request->password_confirmation);
                $resource->save();
            }

            return response()->json(compact('resource'));
        }

        return response()->json(['error' => 'User does not exist'], 404);
    }

    /**
     * Update resource permissions
     * @param User $resource
     * @param User $currentUser
     * @param $superAdmin
     * @param $newPolicies
     */
    protected function updatePermissions(User $resource, User $currentUser, $superAdmin, $newPolicies)
    {
        $currentPermissions = json_decode($resource->meta);

        if( $superAdmin && $currentUser->is_super_admin )
            $currentPermissions = ['role' => 'Super Administrator'];
        elseif( count($newPolicies) ) {

            foreach( $newPolicies as $policyKey => $newPolicy ) {
                if( $model = UserPermissions::getModelFromShortName($policyKey) ) {
                    foreach($newPolicy as $verb => $permission) {
                        if ( $currentUser->can($verb, $model) ) {
                            $prop = "{$verb}_{$policyKey}";

                            if ( property_exists($currentPermissions, $prop) && ! $permission['resource'] ) //Permission revoked
                                unset($currentPermissions->{$prop});
                            elseif ( $permission['resource'] ) //Permission added
                                $currentPermissions->{$prop} = true;
                        }
                    }
                }
            }
        }

        if ( ! $superAdmin )
            $currentPermissions->role = 'User';

        $resource->meta = json_encode($currentPermissions);
        $resource->save();
    }

    /**
     * Destroy the specified resource
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $resource = User::findResource( (int) $id);
        $currentUser = $request->user();

        if ( $currentUser->can('delete', $this->policyOwnerClass) ) {
            if ( ! $resource )
                return response()->json(['error' => 'User does not exist'], 404);

            if ( $resource->is_super_admin && ! $currentUser->is_super_admin )
                return response()->json(['error' => 'You are not authorised to perform this action.'], 403);
            else
                $resource->delete();

            return response()->json(['success' => 'User permanently deleted']);
        }

        return response()->json(['error' => 'You are not authorised to perform this action.'], 403);
    }

    /**
     * Quickly update users
     * @param Request $request
     * @param $update
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickUpdate(Request $request, $update)
    {
        $currentUser = $request->user();
        $resources = $request->resources;

        if ( $currentUser->can('update', $this->policyOwnerClass) ) {
            $selectedNum = count($resources);

            if ( $selectedNum ) {
                try {
                    $users = User::getResourcesByIds($resources);
                    $successNum = 0;

                    if ( $users ) {
                        foreach ( $users as $user ) {
                            if ( $user->is_super_admin && ! $currentUser->is_super_admin )
                                continue;
                            else if ( $update == 'delete' && $currentUser->can('delete', $this->policyOwnerClass) ) {
                                $user->delete();
                                $successNum++;
                            }
                            else if ( $update == 'activate' ) {
                                $user->active = 1;
                                $user->save();
                                $successNum++;
                            }
                            else if ( $update == 'deactivate' ) {
                                $user->active = 0;
                                $user->save();
                                $successNum++;
                            }
                        }

                        $append = ( $selectedNum == $successNum ) ? '' : "Please note you do not have sufficient permissions to $update some resources.";
                        $string = str_plural('resource', $successNum);
                        $successNum = $successNum ?: 'No';

                        return response()->json(['success' => "$successNum $string $update" . "d. $append"]);
                    }
                    else
                        return response()->json(['error' => 'Users do not exist'], 404);
                }
                catch (\Exception $e) {
                    return response()->json(['error' => 'A server error occurred.'], 500);
                }
            }
            else
                return response()->json(['error' => 'No users received'], 500);
        }
        else
            return response()->json(['error' => 'You are not authorised to perform this action.'], 403);
    }

    /**
     * Export users to Excel
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function export(Request $request)
    {
        if ( $request->user()->can('read', $this->policyOwnerClass) ) {
            $resourceIds = (array)$request->resourceIds;
            $fileName = '';

            $resources = count($resourceIds) ? User::getResourcesByIds($resourceIds) : User::getResourcesNoPagination();
            $fileName .= count($resourceIds) ? 'Specified-Resources-' : 'All-Resources-';
            $fileName .= Carbon::now()->toDateString();

            $exporter = new ResourceExporter($resources, $fileName);
            return $exporter->generateExcelExport('users');
        }
        else
            return redirect()->back();
    }
}
