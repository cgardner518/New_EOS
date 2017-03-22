@extends('Labcoat::layouts/standard')
<style media="screen">
  .new{
    border: 1px solid #6da9cf;
  }
</style>
@section('page-title')
  <a href="/history" style="color:white">Past Requests</a> / Request:  {{$eos->name}}
@endsection
@section('main-content')

<div class="indent-padding width-limited-1200">
  <div class="form-section-div">
    <div class="words">
      <p>All parts must be submitted as binary STL in millimeters (0.1mm tolerance & 1 degree angular resolution). Parts in any other units will be rejected outright.</p>
      <br>
      <p><b>Status: </b>&nbsp;{{$eos->status}}</p>

    </div>
    {!! Form::open(['url' => 'requests/'.$eos->id.'/clone', 'id' => 'form']) !!}

  <div class="form-group form-row badged nameField">
    {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
    <div class="inputWrapper">
      {!! Form::text('name', $eos->name, ['class' => 'form-control', 'disabled' => 'true']) !!}
    </div>
      <span class="badge red">Required</span>
  </div>
  <br/>

  <div class="project">
    {!! Form::label('project_id', 'Project:', ['class' => 'control-label']) !!}
    <div >
      {!! Form::select('project_id', $projects, $eos->project_id, ['placeholder' => 'Choose project...', 'disabled' => 'true']) !!}
    </div>
  </div><br>

  <br>

    <div class="form-group form-row badged">
      {!! Form::label('description', 'Description') !!}
      <span class="badge red" style="top:4em;">Required</span>
      {!! Form::textarea('description', $eos->description, ['class' => 'form-control', 'disabled' => 'true']) !!}
    </div>

<input type="hidden" name="id" value="{{$eos->id}}">

@if (Auth::user()->can('eosAdmin'))
    <div class="form-row">
      {!! Form::label('admin_notes', 'Admin Notes') !!}
      {!! Form::textarea('admin_notes', $eos->admin_notes, ['class' => 'form-control', 'disabled' => 'true']) !!}
    </div><br>

@endif
    <input class="pull-right btn btn-success btn-gradient" type="submit" value="Clone">

    {!! Form::close() !!}
  </div>
</div>


@stop
