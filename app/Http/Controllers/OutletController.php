<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\Outlet;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:outlets',
            'latitude' => 'required|numeric|max:90|min:-90',
            'longitude' => 'required|numeric|max:180|min:-180',
            'image' => 'file|mimes:jpg,jpeg,png,gif|max:1024',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $outlet = new Outlet;
        $outlet->name = $request->name;
        $outlet->phone = $request->phone;
        $outlet->latitude = $request->latitude;
        $outlet->longitude = $request->longitude;
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $full_name = time() . '.' . $img->getClientOriginalExtension();
            $path = base_path()."/public/outletImg/";
            $img->move($path, $full_name);
            $outlet->image = $full_name;
        }

        if($outlet->save()){
            return response()->json(['data' => $outlet,'message' => 'Outlet Created Successfully'],200);
        }else{
            return response()->json(['message' => 'Something went wrong'],401); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Outlet::find($id);
        return response()->json(['data' => $data],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:outlets,phone,'.$id,
            'latitude' => 'required|numeric|max:90|min:-90',
            'longitude' => 'required|numeric|max:180|min:-180',
            'image' => 'file|mimes:jpg,jpeg,png,gif|max:1024',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $outlet = Outlet::find($id);
        if(!empty($outlet)){
        $outlet->name = $request->name;
        $outlet->phone = $request->phone;
        $outlet->latitude = $request->latitude;
        $outlet->longitude = $request->longitude;
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $full_name = time() . '.' . $img->getClientOriginalExtension();
            $path = base_path()."/public/outletImg/";
            if (!empty($outlet->image)) {
                unlink(base_path()."/public/outletImg/".$outlet->image);
            }
            $img->move($path, $full_name);
            $outlet->image = $full_name;
        }

        if($outlet->save()){
            return response()->json(['data' => $outlet,'message' => 'Outlet Updated Successfully'],200);
        }else{
            return response()->json(['message' => 'Something went wrong'],401); 
        }
    }else{
        return response()->json(['message' => 'Outlet not found'],401); 
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $outlet = Outlet::find($id);
        if(!empty($outlet)){
            $outlet->delete();
            return response()->json(['message' => 'Outlet Deleted Successfully'],200); 
        }else{
            return response()->json(['message' => 'Outlet not found'],401); 
        }
    }
}
