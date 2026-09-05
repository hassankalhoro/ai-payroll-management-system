<?php

namespace App\Http\Controllers\Admin;

use App\States;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatesRequest;

class StatesController extends Controller
{
    private $folder = "admin.states.";

    public function index()
    {
        $get_data = route($this->folder.'getData');
        return View($this->folder.'index',[
            'get_data' => $get_data
        ]);
    }

    public function getData()
    {
        $states = States::get();
        return View($this->folder.'content',[
            'states'=>$states,
            'add_new' => route($this->folder.'create'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
        ]);
    }

    public function create()
    {
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
            ]);
    }

    public function store(StatesRequest $request)
    {
        $states = States::create($request->all());

        return response()->json([
            'status'=>true,
            'message'=>'New State created successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function show(States $states)
    {
        abort(404);
    }

    public function edit(States $state)
    {
    	return View($this->folder.'edit',[
    		'states' => $state,
    		'form_update' => route($this->folder.'update',['state'=>$state]),
    	]);
    }

    public function update(StatesRequest $request, States $state)
    {
        $state->update($request->all());
        return response()->json([
            'status'=>true,
            'message'=>'State '.$state->title.' updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function destroy(States $state)
    {
        $state->delete();
        return response()->json([
                'status' => true,
                'message' => "Your Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }

    public function massDelete(Request $request)
    {
        $states = States::whereIn('id',$request->ids)
                        ->delete();

        return response()->json([
                'status' => true,
                'message' => "Your all Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }
}
