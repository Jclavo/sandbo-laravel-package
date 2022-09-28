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
            'fixed' => ['nullable', 'boolean']           
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $profile = new Profile();
        $profile->name = $request->name;
        $profile->description = $request->description;
        $profile->activated = $request->activated ?? true;
        $profile->fixed = $request->fixed ?? false;
        $profile->save();

        $response = new ProfileResource($profile);
        return response()->json($response, 200);
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
        $response = new ProfileResource($profile);

        return response()->json($response, 200);
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
            'fixed' => ['nullable', 'boolean']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $profile = Profile::findOrFail($id);
        $profile->name = $request->name;
        $profile->description = $request->description;
        $profile->activated = $request->activated ?? true;
        $profile->fixed = $request->fixed ?? false;
        $profile->save();

        return response()->json(new ProfileResource($profile), 200);
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

        return response()->json(new ProfileResource($profile), 200);
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

        $profile->changeActivatedStatus($status);

        return response()->json(new ProfileResource($profile), 200);
    }

}
