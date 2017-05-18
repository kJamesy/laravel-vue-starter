<?php

namespace App\Http\Controllers\Admin;

use App\Exporters\ResourceExporter;
use App\Member;
use App\Permissions\UserPermissions;
use App\Settings\UserSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
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
        $this->rules = Member::$rules;
        $this->perPage = 25;
        $this->orderByFields = ['first_name', 'last_name', 'email', 'username', 'active', 'created_at', 'updated_at'];
        $this->orderCriteria = ['asc', 'desc'];
        $this->settingsKey = 'members';
        $this->policies = UserPermissions::getPolicies();
        $this->policyOwnerClass = Member::class;
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
                return view('admin.members')->with(['settingsKey' => $this->settingsKey, 'permissionsKey' => $this->permissionsKey]);
            }
            else {
                $settings = UserSettings::getSettings($user->id, $this->settingsKey, $orderBy, $order, $perPage, true);
                $search = strtolower($request->search);

                $resources = $search
                    ? Member::getSearchResults($search, $settings["{$this->settingsKey}_per_page"])
                    : Member::getResources($settings["{$this->settingsKey}_order_by"], $settings["{$this->settingsKey}_order"], $settings["{$this->settingsKey}_per_page"]);

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
     * @return Member|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ( $request->user()->can('create', $this->policyOwnerClass) ) {
            $this->validate($request, $this->rules);

            $resource = new Member();
            $resource->first_name = trim($request->first_name);
            $resource->last_name = trim($request->last_name);
            $resource->phone = $request->phone ? trim($request->phone) : null;
            $resource->email = strtolower(trim($request->email));
            $resource->username = trim($request->username);
            $resource->password = bcrypt($request->password_confirmation);
            $resource->active = $request->active ? 1 : 0;
            $resource->save();

            return $resource;
        }

        return response()->json(['error' => 'You are not authorised to perform this action.'], 403);
    }

    /**
     * Show specified resource
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        $resource = Member::findResource( (int) $id);
        $currentUser = $request->user();

        if ( $resource ) {
            if ( ! $currentUser->can('read', $this->policyOwnerClass) )
                return response()->json(['error' => 'You are not authorised to perform this action.'], 403);

            return response()->json(compact('resource'));
        }

        return response()->json(['error' => 'Member does not exist'], 404);
    }

    /**
     * Show resource for editing
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request)
    {
        $resource = Member::findResource( (int) $id);
        $currentUser = $request->user();

        if ( $resource ) {
            if ( ! $currentUser->can('update', $this->policyOwnerClass) )
                return response()->json(['error' => 'You are not authorised to perform this action.'], 403);

            return response()->json(compact('resource'));
        }

        return response()->json(['error' => 'Member does not exist'], 404);
    }

    /**
     * Update the specified resource
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $resource = Member::findResource( (int) $id);
        $currentUser = $request->user();

        if ( $resource ) {
            if ( ! $currentUser->can('update', $this->policyOwnerClass) )
                return response()->json(['error' => 'You are not authorised to perform this action.'], 403);

            $rules = $this->rules;

            if ( $resource->phone == trim($request->phone) && ( strlen(trim($resource->phone)) == strlen(trim($request->phone)) ) )
                unset($rules['phone']);
            if ( strtolower($resource->email) == strtolower(trim($request->email)) )
                $rules['email'] = str_replace("|unique:users|unique:members", '', $rules['email'] );
            if ( strtolower($resource->username) == strtolower(trim($request->username)))
                $rules['username'] = str_replace("|unique:users|unique:members", '', $rules['username']);
            if ( ! $request->has('password') && ! $request->has('password_confirmation') )
                unset($rules['password']);

            $this->validate($request, $rules);

            $resource->first_name = trim($request->first_name);
            $resource->last_name = trim($request->last_name);
            $resource->phone = $request->phone ? trim($request->phone) : null;
            $resource->email = strtolower(trim($request->email));
            $resource->username = trim($request->username);
            $resource->active = $request->active ? 1 : 0;
            if ( $request->has('password_confirmation') )
                $resource->password = bcrypt($request->password_confirmation);
            $resource->save();


            return response()->json(compact('resource'));
        }

        return response()->json(['error' => 'Member does not exist'], 404);
    }

    /**
     * Destroy the specified resource
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $resource = Member::findResource( (int) $id);
        $currentUser = $request->user();

        if ( $currentUser->can('delete', $this->policyOwnerClass) ) {
            if ( ! $resource )
                return response()->json(['error' => 'Member does not exist'], 404);

            $resource->delete();

            return response()->json(['success' => 'Member permanently deleted']);
        }

        return response()->json(['error' => 'You are not authorised to perform this action.'], 403);
    }

    /**
     * Quickly update resources
     * @param Request $request
     * @param $update
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickUpdate(Request $request, $update)
    {
        $currentUser = $request->user();
        $resourceIds = $request->resources;

        if ( $currentUser->can('update', $this->policyOwnerClass) ) {
            $selectedNum = count($resourceIds);

            if ( $selectedNum ) {
                try {
                    $resources = Member::getResourcesByIds($resourceIds);
                    $successNum = 0;

                    if ( $resources ) {
                        foreach ( $resources as $resource ) {
                            if ( $update == 'delete' && $currentUser->can('delete', $this->policyOwnerClass) ) {
                                $resource->delete();
                                $successNum++;
                            }
                            else if ( $update == 'activate' ) {
                                $resource->active = 1;
                                $resource->save();
                                $successNum++;
                            }
                            else if ( $update == 'deactivate' ) {
                                $resource->active = 0;
                                $resource->save();
                                $successNum++;
                            }
                        }

                        $append = ( $selectedNum == $successNum ) ? '' : "Please note you do not have sufficient permissions to $update some resources.";
                        $string = str_plural('resource', $successNum);
                        $successNum = $successNum ?: 'No';

                        return response()->json(['success' => "$successNum $string $update" . "d. $append"]);
                    }
                    else
                        return response()->json(['error' => 'Members do not exist'], 404);
                }
                catch (\Exception $e) {
                    return response()->json(['error' => 'A server error occurred.'], 500);
                }
            }
            else
                return response()->json(['error' => 'No members received'], 500);
        }

        return response()->json(['error' => 'You are not authorised to perform this action.'], 403);
    }

    /**
     * Export resources to Excel
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function export(Request $request)
    {
        if ( $request->user()->can('read', $this->policyOwnerClass) ) {
            $resourceIds = (array) $request->resourceIds;
            $fileName = '';

            $resources = count($resourceIds) ? Member::getResourcesByIds($resourceIds) : Member::getResourcesNoPagination();
            $fileName .= count($resourceIds) ? 'Specified-Resources-' : 'All-Resources-';
            $fileName .= Carbon::now()->toDateString();

            $exporter = new ResourceExporter($resources, $fileName);
            return $exporter->generateExcelExport('members');
        }
        else
            return redirect()->back();
    }

}
