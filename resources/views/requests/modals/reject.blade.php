@extends('Labcoat::modals/standard')
@section('modal-title')
  Reject Rquest
@stop
@section('modal-body')
<div class="indent-padding width-limited-1200">

  {!! Form::open(['url' => 'reject/' . $id, 'method' => 'POST', 'action' => 'EOSRequestsController@rejected']) !!}

<div class="rows form-group">
  {!! Form::label('message', 'Email Message') !!}
  {!! Form::textarea('message',null, ['placeholder' => 'Please give a consice explanation for rejection to the user here..','class' => 'form-control']) !!}
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
  </script>
@stop
