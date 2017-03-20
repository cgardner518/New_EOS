@extends('Labcoat::modals/standard')
@section('modal-title')
  Send Email
@stop
@section('modal-body')
<div class="indent-padding width-limited-1200">

  {!! Form::open(['url' => '/emailer' , 'method' => 'POST']) !!}
<input type="hidden" name="id" value="{{$id}}">
<div class="rows form-group">
  {!! Form::label('carb_cop', 'CC') !!}
  {!! Form::text('carb_cop','', ['class' => 'form-control']) !!}
</div>
<div class="rows form-group">
  {!! Form::label('subject', 'Subject') !!}
  {!! Form::text('subject','A message from LASR pertaining to your EOS request "'.$eos->name.'"' , ['class' => 'form-control']) !!}
</div>
<div class="rows form-group">
  {!! Form::label('message', 'Body') !!}
  {!! Form::textarea('message',null, ['placeholder' => 'Type your message to the user here..','class' => 'form-control']) !!}
</div>

  {!! Form::close() !!}
</div>
@stop

@section('success-button-label')
  Submit
@stop

@section('modal-js')
  <script>
    modalAjaxSetup('{{ $modalId }}');
    // modalAjaxSetup({ modalId: '{{ $modalId }}' , success: console.log});
  //   modalAjaxSetup({ modalId: '{{ $modalId }}' , success: function(){
  //     location.replace('http://chris.zurka.com/requests')
  //   }
  // });
  </script>
@stop
