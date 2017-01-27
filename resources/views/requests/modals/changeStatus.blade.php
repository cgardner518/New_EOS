@extends('Labcoat::modals/standard')
@section('modal-title')
  Change Status
@stop
@section('modal-body')
<div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">

  {!! Form::open(['url' => 'requests/' . $id, 'method' => 'PATCH', 'action' => 'EOSRequestsController@update']) !!}

  {!! Form::radio('status', 'pending') !!}
  {!! Form::label('status', 'Pending') !!}
<br>
  {!! Form::radio('status', 'in-process') !!}
  {!! Form::label('status', 'In Process') !!}
<br>
  {!! Form::radio('status', 'complete') !!}
  {!! Form::label('status', 'Complete') !!}
{{-- <br>
  {!! Form::radio('status', 'rejected') !!}
  {!! Form::label('status', 'Rejected') !!} --}}

  {!! Form::close() !!}
</div>

@stop

@section('success-button-label')
  Change
@stop

@section('modal-js')
  <script>
    modalAjaxSetup('{{ $modalId }}');
    // modalAjaxSetup({ modalId: '{{ $modalId }}' , success: console.log});
  </script>
@stop
