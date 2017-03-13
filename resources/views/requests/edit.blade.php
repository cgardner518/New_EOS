@extends('Labcoat::layouts/standard')
<style media="screen">
  .new{
    border: 1px solid #6da9cf;
  }
</style>
@section('page-title')
  {{-- <a href="/requests" style="color:white">EOS Requests</a> / Edit  --}}
  Edit Request
@endsection
@section('main-content')
  {{
    Form::macro('dateField', function(){
      return '<span class="inline-left">
                  <div class="input-group narrow left">
                    <input class="form-control" name="date" type="text" id="date-input">
                    <span class="input-group-addon clickable">
                      <i class="fa fa-calendar" data-calid="date-input"></i>
                    </span>
                  </div>
							</span>';
    })
}}

<div class="indent-padding width-limited-1200">
  <div class="form-section-div">
    <div class="words">
      <p>All parts must be submitted as binary STL in millimeters (0.1mm tolerance & 1 degree angular resolution). Parts in any other units will be rejected outright.</p>
      <br>
    </div>
    {!! Form::open(['url' => 'requests/'.$eos->id, 'method' => 'PATCH', 'id' => 'form']) !!}

  <div class="form-group form-row badged nameField">
    {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
    <div class="inputWrapper">
      {!! Form::text('name', $eos->name, ['class' => 'form-control']) !!}
    </div>
      <span class="badge red">Required</span>
  </div><br>

  <div class="project">
    {!! Form::label('project_id', 'Project:', ['class' => 'control-label']) !!}
    <div >
      {!! Form::select('project_id', $projects, $eos->project_id) !!}
    </div>
  </div><br>

    <div class="form-group form-row badged">
      {!! Form::label('description', 'Description') !!}
      <span class="badge red">Required</span>
      {!! Form::textarea('description', $eos->description, ['class' => 'form-control']) !!}
    </div>

    <div class="form-row dayPicker">
      {!! Form::label('needed_by', 'Date Needed By:') !!}
      {!! Form::dateField() !!}
      <p>Note: It is preferable that you do not give a deadline. Because parts are built as space is available, a deadline that is very soon will not be achievable in many cases. Please allow time to get into the queue.</p>
      <br>
    </div>

    <div class="form-row form-group">
      {!! Form::label('parts', 'Parts') !!}
      <p>Submitted STL files must only contain a single part.</p>
      <div class="stl_table">
        @include('requests.partials.stl_table', $eos)
      </div>
      <div class="col-md-12 text-center">
        <button type="button" class="btn btn-primary btn-gradient" data-modal-url="{{URL::route('modalRoute', ['id' => $eos->id])}}" data-modal-id="add_stls_{{$eos->id}}">Add Part</button>
      </div>
    </div>

    <div class="form-row">
      {!! Form::label('admin_notes', 'Admin Notes') !!}
      {!! Form::textarea('admin_notes', $eos->admin_notes, ['class' => 'form-control']) !!}
    </div><br>

    <a href="javascript:undefined;" data-modal-url="{{ URL::route('request.reject', ['id' => $eos->id]) }}" class="btn btn-danger btn-gradient rejecter pull-left" data-modal-id="reject-{{ $eos->id }}" >Reject</a>

    <input class="pull-right btn btn-success btn-gradient" type="submit">

    {!! Form::close() !!}
  </div>
</div>

      <script>
    			// Date Picker
    			$("#date-input").datepicker({ dateFormat: 'yy-mm-dd' });
    			$('form i.fa-calendar').on('click', function(){
    				var target = this;
    				var calID = '#' + $(target).data('calid');
    				$(calID).focus();
    			});
      </script>
@stop
