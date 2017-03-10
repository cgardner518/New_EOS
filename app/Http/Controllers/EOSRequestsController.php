<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Gate;
use File;
use Storage;
use App\User;
use App\Email;
use App\Project;
use App\StlFile;
use App\EOSRequest;
use Illuminate\Http\Request;
use App\Notifications\NewEosRequest;
use App\Http\Requests\EditEosRequest;
use App\Http\Requests\CreateEosRequest;
use App\Notifications\EOSRequestRejected;
use App\Notifications\EOSRequestCompleted;

class EOSRequestsController extends Controller
{
    public function index()
    {
        $eosrequests = EOSRequest::with('users')->get();

        $user = Auth::user();

        $projects = Project::allProjects();

      // if(Gate::allows('eosAdmin')){
      //   return view('requests.limited.index', compact('eosrequests', 'user', 'projects'));
      // }
      return view('requests.index', compact('eosrequests', 'user', 'projects'));
    }

    public function parts()
    {
      $parts = StlFile::all();

      $parts = $parts->map(function($part){

        $uploader = User::find($part->uploaded_by);
        $eos = EOSRequest::find($part->eos_id);
        $size = ($part->dimX*$part->dimY)*$part->dimZ;
        $check = ['Yes', 'No'];
        $stat = ['Pending' , 'In Process', 'Complete', 'Rejected'];

        $things = [
          'part_a' => [
            'href' => 'download/'.$part->id,
            'textContent' => $part->file_name
          ],
          'request_a' => [
            'href'=> "requests/{$eos->id}/edit",
            'textContent' => !!$eos->name ? "({$eos->id}) {$eos->name}" :"({$eos->id}) Unnamed"
          ],
          'requester' => $uploader->name,
          'X' => $part->dimX,
          'Y' => $part->dimY,
          'Z' => $part->dimZ,
          'volume' => $size,
          'clean' => $check[$part->clean],
          'hinges' => $check[$part->hinges],
          'threads' => $check[$part->threads],
          'upload Date' => $part->created_at->format('n/j/Y g:iA'),
          'status' => $stat[$part->status]
        ];
        return $things;
      });
      return $parts;
    }

    public function show($id)
    {
      $eos = EOSRequest::find($id);

      return view('requests.show', compact('eos'));
    }

    public function reject(Request $request){
      // dd($request->all());

      $id = $request->id;
      $modalId = $request->modalId;

      return view('requests.modals.reject', compact('modalId', 'id'));
    }

    public function rejected(Request $request)
    {
      // dd($request->all());
      $eos = EOSRequest::find($request->id);

      $email = new Email;
      $email->user_id = $eos->users->id;
      $email->email_message = $request->message;
      $email->eos = $id;
      $email->save();

      $eos->status = 3;
      $eos->save();

      $eos->stl_files->each(function($part){
        $stl = StlFile::find($part->id);
        $stl->update(['status' => 3]);
      });

      // $eos->users->notify(new EOSRequestRejected($eos));
      dd('FUCK DUDE!');
      return $this->edit($eos->id);
    }

    public function change(Request $id)
    {
      $stl = StlFile::find($id->stl);
      $stl->status = $id->status;
      $stl->save();
      $eos = EOSRequest::find($stl->eos_id);
      // dd($eos->stl_files->count());
      $complete = 0;
      $rejected = 0;
      $pending = 0;

      foreach ($eos->stl_files as $file) {

        if ($file->status === 1) {
          $eos->update(['status' =>1]);
          return $eos;
        }

        if ($file->status == 2) {
          $complete++;
        }elseif ($file->status == 3) {
          $rejected++;
          foreach ($eos->stl_files as $val) {
            if ($val->status == 1) {
              $eos->update(['status' =>1]);
              return $eos;
            }
          }
        }elseif ($file->status == 0) {
          $pending++;
        }


        if ($eos->stl_files->count() === $rejected || $eos->stl_files->count() === $pending || $eos->stl_files->count() === $complete) {
          $eos->update(['status' =>$file->status]);
          return $eos;
        }
      }

      // dd($stl);
      // $eos = EOSRequest::find($id);
      // $eos->status ++;
      // $eos->save();
      // Auth::user()->notify(new \FlashWarning("The status has been changed for ".$eos->name));
      // $res = ['Pending', 'In Process', 'Complete', 'Rejected'];

      // if($res[$eos->status] == 'Complete'){
      //   $eos->users->notify(new EOSRequestCompleted($eos));
      // }
      //
      return $eos;
    }

    public function create(Request $request)
    {
      $eos = new EOSRequest;
      $eos->user_id = Auth::user()->id;
      $eos->save();

      return redirect()->action('EOSRequestsController@edit', ['id' => $eos->id]);
    }

    public function new_eos($id)
    {
      $eos = EOSRequest::find($id);
      $projects = Project::projectsForUser();
      $projects[0] = 'Not a project';
      ksort($projects);

      return view('requests.create', compact('eos', 'projects'));
    }

    public function store(CreateEosRequest $request)
    {

      $thisRequest = $request->all();

      // Save the EOS Request, save the world
      $eos = EOSRequest::create($thisRequest);
      $eos->user_id = Auth::user()->id;
      $eos->save();

      // Send notification to eosOp
      $ops = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
      // dd($ops);
      foreach ($ops as $key => $val) {
        $eosOps = User::find($val);
        $eosOps->notify(new NewEosRequest($eos));
      }

      return redirect('/requests');
    }

    public function add_stl(Request $request)
    {
      // dd($request->all());
      $id = $request->id;
      $modalId = $request->modalId;
      return view('requests.modals.addParts', compact('id', 'modalId'));
    }
    public function store_stl(Request $request)
    {
      // dd($request->all());

      $eos = EOSRequest::find($request->eos_id);
      $fileName = head($request->file())->getClientOriginalName();
      $stl = new StlFile;
      $stl->fill($request->only(['dimX', 'dimY', 'dimZ', 'eos_id']));
      $stl->clean = !!($request->clean);
      $stl->hinges = !!($request->hinges);
      $stl->threads = !!($request->threads);
      $stl->file_name = $fileName;
      $stl->uploaded_by =  Auth::user()->id;
      $stl->save();
      head($request->file())->storeAs('stlFiles/'.$request->eos_id.'/'.$request->id, $fileName);

      return view('requests.partials.stl_table', compact('eos'));
    }

    public function download($id)
    {
      // dd($id);
      // $file = '../storage/app/stlFiles/'.$id.'/'. $file_name;
      // if (file_exists($file)) {
      //   header('Content-Description: File Transfer');
      //   header('Content-Type: application/octet-stream');
      //   header('Content-Disposition: attachment; filename="'.basename($file).'"');
      //   header('Expires: 0');
      //   header('Cache-Control: must-revalidate');
      //   header('Pragma: public');
      //   header('Content-Length: ' . filesize($file));
      //   readfile($file);
      //   exit;
      // }
      $stl = StlFile::find($id);
      return response()->download(storage_path('app/stlFiles/'.$stl->eos_id.'/'.$stl->file_name));
    }

    public function edit($id)
    {

      $eos = EOSRequest::find($id);

      $projects = Project::projectsForUser($eos->user_id);
      $projects[0] = 'Not A Project';
      ksort($projects);

      return view('requests.edit', compact('eos', 'projects'));
    }

    public function update(EditEosRequest $request, $id)
    {
      // dd($request->all());
      // Auth::user()->notify(new \FlashSuccess($request->name ." has been updated."));
      $eos = EOSRequest::find($id);


      // I set the status in the switch stateent so I'll leave it out in the update method
      $collection = $request->except('stl');
      $eos->update($collection);

      Auth::user()->notify(new \FlashSuccess("Your request {$eos->name} has been submitted."));

       return redirect('/requests');
    }

    public function destroy($id)
    {
      Auth::user()->notify(new \FlashError("Your request has been submitted."));
      $eos = EOSRequest::find($id);
      $eos->stl_files()->delete();
      $eos->delete();

      return redirect('/requests');
    }

    public function file_delete($id)
    {
      $stl = StlFile::find($id);
      Storage::delete('stlFiles/'.$stl->eos_id.'/'.$stl->file_name);
      $stl->delete();

      return ;
    }

    public function loggery()
    {
      // Auth::loginUsingId('855bf786-c83c-11e5-a306-08002777c33d');  // Donny Developer
  	  //  Auth::loginUsingId('c5ad9b2d-b59e-11e6-8fb9-0aad45e20ffe');  // Sampson
      	Auth::loginUsingId('5f23d3c6-b1b3-11e6-8fb9-0aad45e20ffe'); // CTG
        // Auth::loginUsingId('48356e60-b576-11e6-8fb9-0aad45e20ffe');  // Michael Jackson

      return redirect('/requests');
    }

    public function solo(){
       Auth::loginUsingId('9a2fe30b-bbc7-11e6-8fb9-0aad45e20ffe');  // Ben Solo
      return $this->index();
    }

}
