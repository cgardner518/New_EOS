@extends('Labcoat::modals/standard')
@section('modal-title')
  Add Part to the Request
@stop
@section('modal-body')
<div class="indent-padding width-limited-1200">
  {!! Form::open(['url' => 'stls', 'files' => true]) !!}
  <p class="hackFix">Submitted STL files must contain only a single part.</p>
  <div class="form-row badged stlFile col-md-12">
    <div class="col-md-1">
      {!! Form::label('stl', 'File: ') !!}
    </div>
    <div class="col-md-9">
      {!! Form::file('stl') !!}
    </div>
    <div class="col-md-2">
      <span class="badge red">Required</span>
    </div>
  </div><br>

  {!! Form::label('', 'Approximate Dimensions in Millimeters') !!}

    <div class="form-row badged">
      <div class="col-md-12">
        <div class="dimensions col-md-10">
          <div class="col-md-1">
            {!! Form::label('dimX', 'X:', ['class' => '']) !!}
          </div>
          <div class="col-md-2">
            {!! Form::text('dimX', '', ['class' => 'form-control']) !!}
          </div>
          <span class="badge red">Required</span>
        </div>
      </div>
    </div>

    <div class="form-row badged">
      <div class="col-md-12">
        <div class="dimensions col-md-10">
          <div class="col-md-1">
            {!! Form::label('dimY', 'Y:', ['class' => '']) !!}
          </div>
          <div class="col-md-2">
            {!! Form::text('dimY', '', ['class' => 'form-control']) !!}
          </div>
          <span class="badge red">Required</span>
        </div>
      </div>
    </div>

    <div class="form-row badged">
      <div class="col-md-12">
        <div class="dimensions col-md-10">
          <div class="col-md-1">
            {!! Form::label('dimZ', 'Z:', ['class' => '']) !!}
          </div>
          <div class="col-md-2">
            {!! Form::text('dimZ', '', ['class' => 'form-control']) !!}
          </div>
          <span class="badge red">Required</span>
        </div>
      </div>
    </div>

<br>

    {!! Form::label('', 'Additional Information', ['class' => '']) !!}
  <div class="form-group">
    <div class="input-group">
        <div class="col-md-1"></div>
        <div class="col-md-11">{!! Form::checkbox('clean', 1, false) !!} Perfom post building cleaning.</div>
        <div class="col-md-1"></div>
        <div class="col-md-11">{!! Form::checkbox('hinges', 1, false) !!} Has hinges or other moving parts.</div>
        <div class="col-md-1"></div>
        <div class="col-md-11">{!! Form::checkbox('threads', 1, false)!!} Has threads</div>
    </div>
  </div>

<input type="hidden" name="eos_id" value="{{$id}}">
  {!! Form::close() !!}
</div>

@stop

@section('success-button-label')
  Save
@stop

@section('modal-js')
  <script>

    // modalAjaxSetup('{{ $modalId }}');
    // modalAjaxSetup({
    //   modalId: '{{ $modalId }}',
    //   success: console.log
    // });

    modalAjaxSetup({
      modalId: '{{ $modalId }}' ,
      success: function(data){
        $('#{{$modalId}}').modal('hide');
        $('.no_parts').hide();
        $('.stl_table').empty();
        $('.stl_table').append(data)
        $('.stl_table table tbody tr').first().hide().fadeIn(700);
        $('#{{$modalId}}').remove();
        $('.modal-backdrop, .modal').remove();
        $('body').removeClass('modal-open');
        deleteSetup();
      }
    });
  </script>
@stop
