@extends('Labcoat::layouts/standard')
@section('page-title')
  <a href="/requests" style="color:white">EOS Requests</a> / Edit Request
@endsection
@section('main-content')

  {{
    Form::macro('dateField', function($by){
      return '<span class="inline-left">
                  <div class="input-group narrow left">
                    <input class="form-control" value="'.$by.'"  name="date" type="text" id="date-input">
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
      {{-- <br>
      <em>We are now accepting items into the queue.</em>
      <br>
      <br>
      <p>The EOS p396 is a selective laser sintering system for plastics. While there is a selection of materials available for the machine, most based on Nylon, we are currently using the Nylon 2200 material. Because of the complexity of the machine and the need for thorough cleaning after each use, we have a limited number of people allowed to operate the machine.</p> --}}
    </div>
    {!! Form::open(['url' => 'requests/'. $eos->id , 'method' => 'PATCH', 'files' => true]) !!}
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
      {!! Form::label('description', 'Description') !!}<span class="badge red">Required</span>
      {!! Form::textarea('description', $eos->description, ['class' => 'form-control']) !!}
    </div>

    {!! Form::label('', 'Approximate Dimensions in Millimeters') !!}
    <div class="form-row dims">
      <div class="form-row badged dimensions">
        {!! Form::label('dimX', 'X: ', ['class' => ' control-label col-sm-1']) !!}
        {!! Form::text('dimX', $eos->dimX, ['class' => 'form-control']) !!}
        <span class="badge red">Required</span>
      </div>
      <div class="form-row badged dimensions">
        {!! Form::label('dimY', 'Y: ', ['class' => ' control-label col-sm-1']) !!}
        {!! Form::text('dimY', $eos->dimY, ['class' => 'form-control']) !!}
        <span class="badge red">Required</span>
      </div>
      <div class="form-row badged dimensions">
        {!! Form::label('dimZ', 'Z: ', ['class' => ' control-label col-sm-1']) !!}
        {!! Form::text('dimZ', $eos->dimZ, ['class' => 'form-control']) !!}
        <span class="badge red">Required</span>
      </div>
    </div><br>

    <div class="form-group">
      {!! Form::label('', 'Additional Information') !!}
      <div class="chckbx">
          <div>
            {!! Form::hidden('clean', 0) !!}
            {!! Form::checkbox('clean', 1, $eos->clean) !!} Perfom post building cleaning.
          </div>
          <div>
            {!! Form::hidden('hinges', 0) !!}
            {!! Form::checkbox('hinges', 1, $eos->hinges) !!} Has hinges or other moving parts.
          </div>
          <div>
            {!! Form::hidden('threads', 0) !!}
            {!! Form::checkbox('threads', 1, $eos->threads)!!} Has threads
          </div>
        </div>
    </div><br>

    <div class="form-row dayPicker">
      {!! Form::label('needed_by', 'Date Needed By:') !!}
      {!! Form::dateField($eos->needed_by) !!}
      <p>Note: It is preferable that you do not give a deadline. Because parts are built as space is available, a deadline that is very soon will not be achievable in many cases. Please allow time to get into the queue.</p>
    </div><br>

    <div class="form-row stlFile">
      {!! Form::label('stl', 'File') !!}
      {{$eos->stl}}
    </div><br>

    <div class="form-row badged parts">
      {!! Form::label('number_of_parts', 'Number of Parts: ') !!}
      {!! Form::text('number_of_parts', $eos->number_of_parts, ['class' => 'form-control'])!!}
      <span class="badge red">Required</span>
    </div><br>
        <p class="hackFix">Submitted STL files must contain only a single part. Please limit zip files to less than 16 parts (STL files).</p>

    @can('eosAdmin')
      <div class="form-row price">
        {!! Form::label('cost', 'Cost:') !!}
        {!! Form::text('cost', $eos->cost, ['class' => 'form-control']) !!}
      </div><br>

      {!! Form::label('status', 'Status', ['class' => 'form-row']) !!}
      {{-- <span class="badge red">Required</span> --}}
        <div class="form-row">
          <div class="col-md-4">
            {!! Form::label('statusa', 'Pending') !!}
            {!! Form::radio( 'status', '0', $eos->status == 0 ? true : '', ['id' => 'statusa']) !!}
          </div>

          <div class="col-md-4">
            {!! Form::label('statusb', 'In Process') !!}
            {!! Form::radio( 'status', '1', $eos->status == 1 ? true : '', ['id' => 'statusb'] ) !!}
          </div>

          <div class="col-md-4">
            {!! Form::label('statusc', 'Completed') !!}
            {!! Form::radio('status', '2', $eos->status == 2 ? true : '', ['id' => 'statusc']) !!}
          </div>
        </div>
        <br>
    @endcan

    <div class="form-row">
      {!! Form::label('admin_notes', 'Admin Notes') !!}
      {!! Form::textarea('admin_notes', $eos->admin_notes, ['class' => 'form-control']) !!}
    </div><br>

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
