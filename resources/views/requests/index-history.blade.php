@extends('Labcoat::layouts/standard')
<style media="screen">
  .labcoat-grid{
    display: none;
  }
  .mauto{
    margin: auto;
  }
</style>
@section('page-title')
  Past EOS Requests
@endsection
@section('tab-menu')
    @include('Labcoat::partials/tabs')
@endsection
@section('main-content')


  <div class="content">
    <div>
      <div class="indent-padding width-limited-1600">
        {{-- @if ($user->can('eosAdmin'))
          <div class="col-md-6" style="padding-left:0px;">
            {!! Form::label('', 'Show: ') !!}
            {!! Form::select('table', ['Requests' => 'Requests', 'Parts' => 'Parts'], '', ['class' => 'show-selector']) !!}
          </div>
        @endif --}}
        {{-- <a class="pull-right btn btn-primary btn-gradient" href="/requests/create">New Request</a><br><br> --}}
        <div class="table-header eos">
          <p class="table-title">EOS Requests</p>
          <p class="table-sub-title">The list of current EOS requests.</p>
        </div>
      <table class="indexTable">
          <thead>
            <tr>
            <th>
              ID
            </th>
            <th>
              Uploaded By
            </th>
            <th>
              Submitted
            </th>
            <th>
              Request Name
            </th>
            <th>
               Number of Parts
            </th>
            <th>
               Cost
            </th>
            <th>
              Project
            </th>
              <th>
                Status
              </th>
              {{-- <th>
                Reject
              </th>
              <th>
                Delete
              </th> --}}
            </tr>
          </thead>
            @foreach($eoses->reverse() as $eos)
              <tbody>
                  <tr class="topRow">
                    <td class="id-td" rowspan="2">
                      <a href="requests/{{ $eos->id }}/edit" data-toggle="tooltip" title="Edit this request">
                        {{ $eos->id }}
                      </a>
                    </td>
                    <td class="nameDiv">
                      <span data-toggle="tooltip" title="{{ $eos->users->name }}">
                        {{ str_limit($eos->users->name, 9) }}
                      </span>
                    </td>
                    <td>{{ $eos->created_at->format('n/j/Y g:iA')}}</td>
                    <td>
                      {{-- @if($eos->status === 0 || $eos->status === 1 || $eos->status === 3) --}}
                      <a href="requests/{{ $eos->id }}" data-toggle="tooltip" title="Edit this request">
                            {{$eos->name}}
                        </a>
                      {{-- @else
                        <a href="requests/{{ $eos->id }}" data-toggle="tooltip" title="View this request">
                          {{ $eos->name }}
                        </a>
                      @endif --}}
                    </td>
                    <td align="center">
                      {{ $eos->stl_files->count()}}
                    </td>
                    <td align="center">
                      <span data-toggle="tooltip" title="Cost for print">
                        @if($eos->cost > 0)
                          {{ $eos->cost }}
                        @else
                          No Estimate
                        @endif
                      </span>
                    </td>
                    <td align="center">
                      @if($eos->project_id == 0)
                        <span>
                          Not a LASR Project
                        </span>
                      @else
                        <span>
                          {{$projects[$eos->project_id]}}
                        </span>
                      @endif
                    </td>
                    <td rowspan="2" align="center">
                      @if($eos->status == 0)
                          Pending
                      @elseif ($eos->status == 1)
                          In Process
                      @elseif( $eos->status == 2)
                          Complete
                      @elseif( $eos->status == 3)
                        <span>
                          Rejected
                        </span>
                      @endif
                    </td>
                    {{-- <td>
                      @if($eos->status == 0)
                      <button type="submit" data-modal-url="{{ URL::route('request.reject', ['id' => $eos->id]) }}" class="btn btn-danger btn-gradient rejecter" data-modal-id="reject-{{ $eos->id }}" >Reject</button>
                    @endif
                    </td>
                    <td align='center'>
                      @if($eos->status === 0 || $eos->status === 3)
                        <a href="javascript:undefined;" class="fa fa-fw fa-trash" style="text-decoration: none;" data-delete-element="tbody" data-delete-url="{{ URL::route('request.destroy', $eos['id']) }}"></a>
                      @endif
                    </td> --}}
                    </tr>
                    <tr class="hackAttack">
                      <td colspan="6"><span data-toggle="tooltip" title="{{$eos->description}}">{{ str_limit($eos->description, 95) }}</span></td>
                    </tr>
                  </tbody>
              @endforeach
            </table>
          </div>
        </div>
{!! Form::open() !!}
{!! Form::close() !!}
  </div>

@stop
