<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ProviderController extends Controller
{
    /**
     * Show the application dashboard.
     *
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getProvider();
        }
        $item = null;
        $id = $request->get('id');
        if($id) {
            $item = Provider::find($id);
        }
        return view('provider.index')->with(['item' => $item]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'path' => 'required|string|unique:providers,path',
            'is_active' => 'nullable',
            'is_authenticate' => 'nullable',
        ]);
        $input = $request->only(['name', 'description', 'path', 'is_active', 'is_authenticate']);
        $input['is_active'] = $request->has('is_active');
        $input['is_authenticate'] = $request->has('is_authenticate');
        $item = Provider::create($input);
        if($item)
        {
            toast('Created Successfully.','success');
            return Redirect::to('providers');
        }
        toast('Error Creating','error');
        return back()->withInput();
    }

    public function update(int $id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'path' => ['required','string', Rule::unique('providers')->where(function ($query) use ($id, $request) {
                $query->where('path', $request->path)->where('id', '!=', $id);
            })],
            'is_active' => 'nullable',
            'is_authenticate' => 'nullable',
        ]);
        $item = Provider::find($id);
        $input = $request->only(['name', 'description', 'path', 'is_active', 'is_authenticate']);
        $input['is_active'] = $request->has('is_active');
        $input['is_authenticate'] = $request->has('is_authenticate');
        $item->update($input);
        if($item)
        {
            toast('User Updated Successfully.','success');
            return Redirect::to('providers');
        }
        toast('Error in User Update','error');
        return back()->withInput();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, Request $request)
    {
        $item = Provider::find($id);
        if($request->ajax() && $item->find($id)->delete())
        {
            return response(["message" => "Deleted Successfully"], 200);
        }
        return response(["message" => "Delete Error! Please Try again"], 400);
    }

    private function getProvider()
    {
        $data = Provider::get();
        return DataTables::of($data)
            ->addColumn('action', function($row){
                $action = "<button type='button' class='btn btn-xs btn-info btn-secret' data-id='" . $row->id . "' data-toggle='modal' data-target='#modalMin'>
         Secret </button> &nbsp; &nbsp;";
                $action .= "<a class='btn btn-xs btn-warning' id='btnEdit' href='" . route('providers.index', ['id' => $row->id]) . "'><i class='fas fa-edit'></i></a>";
                $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                return $action;
            })
            ->rawColumns(['action'])->make('true');
    }

    public function secret(int $id, Request $request)
    {
        $item = Provider::find($id);
        if($request->ajax() && $item)
        {
            return response(["message" => $item->secret], 200);
        }
        return response(["message" => "Error! Please Try again"], 400);
    }

    public function generateSecret(int $id, Request $request)
    {
        $item = Provider::find($id);
        if($request->ajax() && $item)
        {
            $item->secret = Provider::generateSecret();
            $item->save();
            return response(["message" => $item->secret], 200);
        }
        return response(["message" => "Error! Please Try again"], 400);
    }
}
