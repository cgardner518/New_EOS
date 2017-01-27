@extends('Labcoat::layouts/standard')
@section('page-title')
  EOS Requests
@endsection
@section('main-content')

  <div class="content">
    <div>
      <div class="indent-padding width-limited-1200">
        <a class="pull-right btn btn-primary btn-gradient" href="/requests/create">New Request</a><br><br>
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
              Name
            </th>
            <th>
              Submitted
            </th>
            {{-- <th>
              Status
            </th> --}}
            <th>
              Part Name
            </th>
            {{-- <th>
              Project
            </th> --}}
            {{-- <th>
               STL File
            </th> --}}
            <th>
               Volume
            </th>
            <th>
               Cost
            </th>
            <th>
              P
            </th>
            <th>
              C
            </th>
            <th>
              T
            </th>
            <th>
              M
            </th>
            <th>
              #
            </th>
              <th>
                Change
              </th>
              <th>
                Reject
              </th>
              <th>
                Delete
              </th>
            </tr>
          </thead>
            @foreach($eosrequests->reverse() as $eos)
          <tr class="topRow">
              <td class="id-td" rowspan="2">
                {{ $eos->id }}
              </td>
              <td class="nameDiv">
                <span data-toggle="tooltip" title="{{ $eos->users->name }}">
                {{ str_limit($eos->users->name, 7) }}
                </span>
              </td>
              <td>{{ $eos->created_at}}</td>
              {{-- <td>
                <span data-toggle="tooltip" title="Current Status">
                  <div class="status-td">
                    @if($eos->status === 0)
                      Pending
                    @endif
                    @if($eos->status === 1)
                      In Process
                    @endif
                    @if($eos->status === 2)
                      Complete
                    @endif
                    @if($eos->status === 3)
                      Rejected
                    @endif
                  </div>
              </span>
              </td> --}}
              <td>
                @if($eos->status === 0 || $eos->status === 1 || $eos->status === 3)
                  <a href="requests/{{ $eos->id }}/edit" data-toggle="tooltip" title="Edit this request">
                    {{ $eos->name }}
                  </a>
                @else
                  <a href="requests/{{ $eos->id }}" data-toggle="tooltip" title="View this request">
                    {{ $eos->name }}
                  </a>
                @endif
              </td>
              {{-- <td>
                <span data-toggle="tooltip" title="{{$eos->stl}}">
                  {{ str_limit($eos->stl, 7) }}
                </span>
              </td> --}}
              <td>
                <span data-toggle="tooltip" title="{{$eos->dimX}} x {{$eos->dimY}} x {{$eos->dimZ}}">
                  {{ $eos->volume}}
                </span>
              </td>
              <td>
                <span data-toggle="tooltip" title="Cost for print">
                  @if($eos->cost > 0)
                  {{ $eos->cost }}
                @endif
                </span>
              </td>
              <td>
                @if($eos->project_id == 0)
                <span data-toggle="tooltip" title="No project">
                  N
                </span>
                @else
                <span data-toggle="tooltip" title="{{$projects[$eos->project_id]}}">
                  Y
                </span>
                @endif
              </td>
              <td>
                @if( $eos->clean === 1 )
                  <span data-toggle="tooltip" title="Perfom post building cleaning">
                    Y
                  </span>
                @else
                  <span data-toggle="tooltip" title="NO post build cleaning">
                    N
                  </span>
                @endif
              </td>
              <td>
                @if($eos->threads === 1)
                  <span data-toggle="tooltip" title="Has threads">
                    Y
                  </span>
                @else
                  <span data-toggle="tooltip" title="NO threads">
                    N
                  </span>
                @endif
              </td>
              <td>
                @if($eos->hinges === 1)
                  <span data-toggle="tooltip" title="Has hinges or other moving parts">
                    Y
                  </span>
                @else
                  <span data-toggle="tooltip" title="NO hinges or other moving parts">
                    N
                  </span>
                @endif
              </td>
              <td>
                <span data-toggle="tooltip" title="{{ $eos->number_of_parts }} part(s)">
                  {{ $eos->number_of_parts }}
                </span>
              </td>
                <td>
                  @if($eos->status === 0)
                    {!! Form::open(['method' => 'POST', 'url' => 'change/' . $eos->id ]) !!}
                    <button type="submit" class="changer btn btn-default btn-gradient" >
                      In Process
                    </button>
                    {!! Form::close() !!}
                  @elseif ($eos->status === 1)
                    {!! Form::open(['method' => 'POST', 'url' => 'change/' . $eos->id ]) !!}
                    <button type="submit" class=" changer btn btn-default btn-gradient" >
                      Complete
                    </button>
                    {!! Form::close() !!}
                  @elseif( $eos->status === 2)
                    <span>
                      Complete
                    </span>
                  @elseif( $eos->status === 3)
                    <span>
                      Rejected
                    </span>
                  @endif
                </td>
                <td>
                  @if($eos->status == 0)
                  {{-- {!! Form::open(['method' => 'POST', 'url' => 'reject/' . $eos->id ]) !!}
                  <button type="submit" class="btn btn-danger btn-gradient" name="reject">
                    Reject
                  </button>
                  {!! Form::close() !!} --}}
                  <button type="submit" data-modal-url="{{ URL::route('request.reject', ['id' => $eos->id]) }}" class="btn btn-danger btn-gradient rejecter" data-modal-id="reject-{{ $eos->id }}" >Reject</button>
                  @endif
                </td>
                  <td align='center'>
                  @if($eos->status === 0 || $eos->status === 3)
                    <a href="javascript:undefined;" class="fa fa-fw fa-trash" style="text-decoration: none;" data-delete-url="{{ URL::route('request.destroy', $eos['id']) }}"></a>
                  @endif
                  </td>
            </tr>
            <tr class="hackAttack">
              <td class="fileLink" colspan="2"><a download data-toggle="tooltip" title="Download .STL file" href="{{$eos->filePath}}">{{ $eos->stl}}</a></td>
              <td colspan="13"><span data-toggle="tooltip" title="{{$eos->description}}">{{ str_limit($eos->description, 95) }}</span></td>
            </tr>
        @endforeach
     </table>
   </div>
   </div>

 </div>
 <script>
 $('.changer').click(function(e){
   e.preventDefault();

  //  $status = $(this).parent().parent().parent().find('.status-td');
   $button = $(this);
   $reject = $(this).parent().parent().parent().find('.rejecter');
   $td = $(this).parent().parent();
   $token = $(this).parent().find('input').attr('value');
   $id = parseInt($(this).parent().parent().parent().find('.id-td').text());
   $data = {'_token': $token}

   $.ajax({
     url: 'http://chris.zurka.com/change/'+$id,
     method: 'POST',
     data: $data
    }).then(function(res){
    //  $status.text(res);
     if(res == 'In Process'){
       $button.text('Complete');
     }else if(res == 'Complete') {
       $button.hide()
       $reject.hide()
       $td.text(res)
     }
   })
 })
 </script>
@stop
