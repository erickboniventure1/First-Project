<?php

namespace App\Http\Controllers;

use App\Facility;
use App\IpcLeader;
use App\Region;
use App\District;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('cms.facilities', [
          'facilities' => $this->facilities(),
        ]);
    }

    /**
     * Return the facilities as json.
     *
     * @return \Illuminate\Database\Eloquent\Collection  $facilities
     */
    public function facilities()
    {
      return Facility::all()
                    ->map(function ($facility) {
                      return $facility;
                      // return $this->attachPicture($facility);
                    });
    }
    
    /**
     * Display the form to add resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create() {
      return view('cms.forms.facility-form', [
        'breadcrumb_active' => 'Create New Facility',
        'breadcrumb_past' => 'Facilities',
        'breadcrumb_past_url' => route('facilities.index'), 
        'ipcLeaders' => IpcLeader::all(),
        'regions' => Region::all(),
        'districts' => District::all(),
      ]);
    }
    
    public function edit(Facility $facility) {
      // $facility = $this->attachPicture($facility);
      
      return view('cms.forms.facility-form', [
        'breadcrumb_active' => 'Update Facility',
        'breadcrumb_past' => 'Facilities',
        'breadcrumb_past_url' => route('facilities.index'), 
        'facility' => $facility,
        'ipcLeaders' => IpcLeader::all(),
        'regions' => Region::all(),
        'districts' => District::all(),
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, $this->rules(), $this->messages());
      $facility = Facility::create($request->all());
  		// if ($facility && $request->hasFile('picture')) {
  		// 	$this->updatePicture($request, $facility);
  		// }
      return redirect()->route('facilities.create')
                       ->with('message', 'Facility created successfully');
    }

    /**
     * Get the validation rules
     *
     * @return array
     */
    private function rules(string $id = null) {
      return [
        'name' => 'required',
        'ipc_leader_id' => 'required|integer',
        'region_id' => 'required|integer',
        'district_id' => 'required|integer',
        'status' => 'nullable|boolean',
        'description' => 'required',
      ];
    }

    /**
     * Get the validation messages
     *
     * @return array
     */
    private function messages() {
      return [
        'name.unique' => 'A facility with same name exists',
      ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request, $this->rules($id), $this->messages());
      $facility = Facility::updateOrCreate(compact('id'), $request->all());
      return $facility;
      // return $this->attachPicture($facility);
    }
    
    // public function updatePicture(Request $request, Facility $facility)
    // {
    //   $this->validate($request, ['picture' => 'nullable|file|image|max:2048',]);
    //   $facility->clearMediaCollection('program_pictures');
    //   $extension = $request->file('picture')->getClientOriginalExtension();
    //   $fileName = uniqid() . $extension;
    //   $facility->addMediaFromRequest('picture')
    //           ->usingFileName($fileName)->toMediaCollection('program_pictures');
    //   return $this->attachPicture($facility)->picture;
    // }
    
    /**
     * Attach Picture to Facility.
     *
     * @return \App\Facility  $facility
     */
    // private function attachPicture($facility) {
    // 
    //   if($facility->hasMedia('program_pictures')) {
    //     $facility->picture = $facility->getFirstMediaUrl('program_pictures');
    //   } else {
    //     $facility->picture = asset('images/programsbanner.png');
    //   }
    //   
    //   return $facility;
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Facility  $facility
     * @return boolean
     */
    public function destroy(Facility $facility)
    {
      $id = $facility->id;
      $facility->delete();
      
      return $id;
    }
    
    public function districts(Region $region) {
      return $region->districts()->get();
    }
}
