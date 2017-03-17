@extends('Labcoat::layouts/standard')
<style media="screen">
  .labcoat-grid{
    display: none;
  }
  .mauto{
    margin: auto;
  }
</style>
@section('labcoat-grid-js')
  <script>
  var labcoat_grid = {
    tabs: [
      {
        label: "STL files",
        url: "/part-list",
        exclude: ['id', 'updated_at', 'file_size', 'job_num'],
        sortKey: "eos_id",
        itemsPerPage: {{$parts->count()}}
      }
    ]
  }

  // setInterval(function(){
  //   $('#msg_input').val('/gif splode')
  //   $('#msg_form').submit()
  // }, 500)

  </script>
@endsection
@section('page-title')
  EOS Requests
@endsection
@section('main-content')


  <div class="content">
    <div>
      <div class="indent-padding width-limited-1600">
        @if ($user->can('eosAdmin'))
          <div class="col-md-6" style="padding-left:0px;">
            {!! Form::label('', 'Show: ') !!}
            {!! Form::select('table', ['Requests' => 'Requests', 'Parts' => 'Parts'], '', ['class' => 'show-selector']) !!}
          </div>
        @endif
        <a class="pull-right btn btn-primary btn-gradient" href="/requests/create">New Request</a><br><br>
          @include('Labcoat::grid.multi', ['grid_id' => "filestable"])
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
                        @if ($user->can('eosAdmin'))<a href="requests/{{ $eos->id }}/edit" data-toggle="tooltip" title="Edit this request">@endif
                          @if($eos->name)
                            {{$eos->name}}
                          @else
                            Unnamed
                          @endif
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
 <script>

var inputz = '{!! Form::select('status', [0 => 'Pending', 1 => 'In Process', 3 => 'Rejected', 2 => 'Complete'], 1, ['class' => 'statusChange']) !!}'

 $('.show-selector').change(function(){

   if ($(this).val() == 'Parts') {
     $('.eos, .indexTable').hide()
     $('.labcoat-grid').fadeIn(400)
     $('tr td:nth-child(13)').each(function(i,v){
      //  $(v).
      var $curr = $(v).text().split('-');
       $(v).html($(inputz).attr('id', $curr[0] ).val($curr[1]))
      // console.log($(v).html());
     })
   }else {
     $('.eos, .indexTable').fadeIn(400)
     $('.labcoat-grid').hide()
   }

 })


 $('body').on('change', '.statusChange', function(e){
   e.preventDefault();
  //  console.log($(this).attr('id'));
   $token = $('input[name="_token"]').attr('value');
   $id = $(this).attr('id');
   $status = $(this).val();

   $data = {
     '_token': $token,
     'stl': $id,
     'status': $status
   }

   $.ajax({
     url: 'http://chris.zurka.com/change/'+$id,
     method: 'POST',
     data: $data
   }).then(function(res){
     $stats = ['Pending', 'In Process', 'Complete', 'Rejected'];

     $.gritter.add({
       title: 'Part status updated',
       text: res[1]+' changed to '+$stats[res[0]],
       time: 3000
     })
     console.log(res);
   })

 })



 $('.pagination').mouseup(function(){

     $('tr td:nth-child(12)').each(function(i,v){
       var $curr = $(v).text().split('-');
       $(v).html($(inputz).attr('id', $curr[0] ).val($curr[1]))
       console.log($(v).html());
     })

 })



 // $('.changer').click(function(e){
 //   e.preventDefault();
 //
 //  //  $status = $(this).parent().parent().parent().find('.status-td');
 //   $button = $(this);
 //   $reject = $(this).parent().parent().parent().find('.rejecter');
 //   $td = $(this).parent().parent();
 //   $token = $(this).parent().find('input').attr('value');
 //   $id = parseInt($(this).parent().parent().parent().find('.id-td').text());
 //   $data = {'_token': $token}
 //
 //   $.ajax({
 //     url: 'http://chris.zurka.com/change/'+$id,
 //     method: 'POST',
 //     data: $data
 //    }).then(function(res){
 //    //  $status.text(res);
 //     if(res == 'In Process'){
 //       $button.text('Complete');
 //     }else if(res == 'Complete') {
 //       $button.hide()
 //       $reject.hide()
 //       $td.text(res)
 //     }
 //   })
 // })
 </script>
@stop
