<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Jclavo\Profiles\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::all();

        $response = ProfileResource::collection($profiles);

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:45'],
            'description' => ['nullable', 'max:200'],
            'activated' => ['nullable', 'boolean'],
            'fixed' => ['nullable', 'boolean'],
            'system_id' => ['required', 
                            Rule::exists('systems', 'id')
                        ]             
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $profile = new Profile();
        $profile->name = $request->name;
        $profile->description = $request->description;
        $profile->activated = $request->activated ?? true;
        $profile->fixed = $request->fixed ?? false;
        $profile->system_id = $request->system_id;
        $profile->save();

        return $this->sendResponse(
            new ProfileResource($profile),
            "crud.create"
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $profile = Profile::findOrFail($id);
                
        return $this->sendResponse(
            new ProfileResource($profile),
            'crud.read'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:45'],
            'description' => ['nullable', 'max:200'],
            'activated' => ['nullable', 'boolean'],
            'fixed' => ['nullable', 'boolean'],
            'system_id' => ['required', 
                            Rule::exists('systems', 'id')
                        ]
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $profile = Profile::findOrFail($id);
        $profile->name = $request->name;
        $profile->description = $request->description;
        $profile->activated = $request->activated ?? true;
        $profile->fixed = $request->fixed ?? false;
        $profile->system_id = $request->system_id;
        $profile->save();

        return $this->sendResponse(
            new ProfileResource($profile),
            "crud.update"
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $profile = Profile::findOrFail($id);

        $profile->delete();

        return $this->sendResponse(
            new ProfileResource($profile),
            'crud.delete'
        );
    }

    /**
     * 
     */
    public function activate(int $id)
    {
        return self::changeActivatedStatus($id, true);
    }

    /**
     * 
     */
    public function desactivate(int $id)
    {
        return self::changeActivatedStatus($id, false);
    }

    /**
     * 
     */
    private function changeActivatedStatus(int $id, bool $status)
    {
        $profile = Profile::findOrFail($id);

        $profile->activated = $status;
        $profile->save();

        return $this->sendResponse(
            new ProfileResource($profile),
            'change.activated.status'
        );
    }

}
