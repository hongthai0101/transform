<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Transform;
use App\Services\TransformService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Xml;

class TransformController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDatatable();
        }
        return view('transform.index');
    }

    public function create()
    {
        $providers = Provider::all();
        return view('transform.create', compact('providers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'transform_type' => 'required',
            'to_url' => 'required',
            'to_method' => 'required',
            'code' => 'required|string|unique:transforms,code,NULL,id,provider_id,' . $request->provider_id,
        ]);
        Transform::create($request->only([
            'provider_id', 'name', 'description', 'transform_type', 'code', 'to_url', 'to_method'
        ]));
        return redirect()->route('transforms.index')->with('success', 'Transform created successfully.');
    }

    public function edit($id)
    {
        $transform = Transform::find($id);
        $providers = Provider::all();
        return view('transform.edit', compact('transform', 'providers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'provider_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'transform_type' => 'required',
            'to_url' => 'required',
            'to_method' => 'required',
            'code' => ['required','string', Rule::unique('transforms')->where(function ($query) use ($request, $id) {
                return $query->where('provider_id', $request->provider_id)->where('id', '!=', $id)->where('code', $request->code);
            })],
        ]);
        $transform = Transform::find($id);
        $transform->update($request->only([
            'provider_id', 'name', 'description', 'transform_type', 'code', 'to_url', 'to_method'
        ]));
        return redirect()->route('transforms.index')->with('success', 'Transform updated successfully.');
    }

    public function destroy($id)
    {
        Transform::find($id)->delete();
        return response()->json(['success' => 'Transform deleted successfully.']);
    }

    public function transform(int $id, string $type)
    {
        $transform = Transform::find($id);
        return view('transform.config', compact('transform', 'type'));
    }

    private function getDatatable()
    {
        $data = Transform::with('provider')->get();
        return DataTables::of($data)
            ->addColumn('transform', function($row){
                $transform = "<a class='btn btn-xs btn-primary' href='" . route('transforms.config', ['id' => $row->id, 'type' => 'request']) . "'>Request</a> &nbsp; &nbsp;";
                $transform .= "<a class='btn btn-xs btn-success' href='" . route('transforms.config', ['id' => $row->id, 'type' => 'response']) . "'>Response</a>";
                return $transform;
            })
            ->addColumn('action', function($row){
                $action = "<a class='btn btn-xs btn-warning' id='btnEdit' href='" . route('transforms.edit', $row->id) . "'><i class='fas fa-edit'></i></a>";
                $action .=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                return $action;
            })
            ->addColumn('provider', function($row){
                return $row->provider->name;
            })
            ->rawColumns(['action', 'provider', 'transform'])->make('true');;
    }
}
