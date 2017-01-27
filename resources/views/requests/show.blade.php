@extends('Labcoat::layouts/standard')
@section('page-title')
  <a href="/requests" style="color:white">EOS Requests</a> / {{$eos->name}}
@endsection
@section('main-content')
{{-- <div class="links content">
  <a href="/">Home</a>
  <a href="/requests">Back</a>
</div> --}}
<div class="indent-padding width-limited-1200">
    <table class="showTable">
      <tbody>
        <tr>
          <td>
            <label>Name:</label>
          </td>
            <td>{{$eos->name}}</td>
        </tr>
        <tr>
          <td>
            <label>Description:</label>
          </td>
            <td>{{$eos->description}}</td>
        </tr>
        <tr>
          <td>
            <label>File:</label>
          </td>
          <td>{{$eos->stl}}</td>
        </tr>
        <tr>
          <td>
            <label>Dimensions (X,Y,Z):</label>
          </td>
          <td>{{$eos->dimX}}, {{$eos->dimY}}, {{$eos->dimZ}}</td>
        </tr>
        <tr>
          <td>
            <label>Cost:</label>
          </td>
          <td>$ {{$eos->cost}}</td>
        </tr>
        <tr>
          <td>
            <label>Volume:</label>
          </td>
          <td>{{$eos->volume}}</td>
        </tr>
        <tr>
          <td>
            <label>Should item be cleaned?:</label>
          </td>
          @if($eos->clean)
            <td>YES</td>
          @else
            <td>NO</td>
          @endif
        </tr>
        <tr>
          <td>
            <label>Does item have hinges?:</label>
          </td>
          @if($eos->hinges)
            <td>YES</td>
          @else
            <td>NO</td>
          @endif
        </tr>
        <tr>
          <td>
            <label>Does item have threads?:</label>
          </td>
          @if($eos->threads)
            <td>YES</td>
          @else
            <td>NO</td>
          @endif
        </tr>
        <tr>
          <td>
            <label>Needed by:</label>
          </td>
          <td>{{$eos->needed_by}}</td>
        </tr>
        <tr>
          <td>
            <label>Number of parts:</label>
          </td>
          <td>{{$eos->number_of_parts}}</td>
        </tr>
        <tr>
          <td>
            <label>Status:</label>
          </td>
          @if($eos->status === 0)
            <td>Pending</td>
          @endif
          @if($eos->status === 1)
            <td>In Progress</td>
          @endif
          @if($eos->status === 2)
            <td>Complete</td>
          @endif
          @if($eos->status === 3)
            <td>Rejected</td>
          @endif
        </tr>
        <tr>
          <td>
            <label>Admin Notes:</label>
          </td>
          <td>{{$eos->admin_notes}}</td>
        </tr>
   </tbody>
 </table>
</div>
@stop
