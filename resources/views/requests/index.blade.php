@extends('Labcoat::layouts/standard')
<style media="screen">
  .grid-holder{
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

  </script>

@endsection
@section('page-title')
  EOS Requests
@endsection
@section('tab-menu')
    @include('Labcoat::partials/tabs')
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
        <div class="grid-holder">
          <div class="container-fluid">
            {{-- {!! Form::label('', 'Batch Update: ', ['class' =>'']) !!} --}}
            {!! Form::select('status', [0 => 'Pending', 1 => 'In Process', 2 => 'Complete'], '', ['class' => 'batchChange pull-right', 'placeholder' => 'Update Multiple']) !!}
          </div>
          @include('Labcoat::grid.multi', ['grid_id' => "filestable"])
        </div>
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
                        @if ($eos->status <= 1)<a href="requests/{{ $eos->id }}/edit" data-toggle="tooltip" title="Edit this request">@endif
                            {{$eos->name}}
                        </a>
                    </td>
                    <td align="center">
                      {{ $eos->stl_files->sum('quantity')}}
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
var chex = '{!! Form::checkbox('', '', false) !!}'
 $('.show-selector').change(function(){

   if ($(this).val() == 'Parts') {
     $('.grid-holder').fadeIn(400)
     $('.eos, .indexTable').hide()
     $('tr td:nth-child(14)').each(function(i,v){
      //  $(v).
      var $curr = $(v).text().split('-');
       $(v).html($(inputz).attr('id', $curr[0] ).val($curr[1]))
      // console.log($(v).html());
     })
     $('tr td:nth-child(15)').each(function(i,v){
       var $chektd =  $(v).text();
       $(v).html($(chex).attr('name', $chektd))
       $(v).parent().attr('align', 'center')
     })
   }else {
     $('.eos, .indexTable').fadeIn(400)
     $('.grid-holder').hide()
   }

 })

$('.batchChange').change(function(){
  // console.log($(this).val());
  var $change2 = $(this).val();
  $token = $('input[name="_token"]').attr('value');

  if ($( "input[type=checkbox]:checked" ).length>0) {
    $( "input[type=checkbox]:checked" ).each(function(i,v){
      var $id = $(v).attr('name');
      $data = {
        '_token': $token,
        'stl': $id,
        'status': $change2
      }
      $.ajax({
        url: 'http://chris.zurka.com/change/'+$id,
        method: 'POST',
        data: $data
      })
    })
    window.location.assign('http://chris.zurka.com/requests')
  }else {
    $.gritter.add({
      title: 'Oops!',
      text: 'No parts are selected',
      time: 3000
    })
    $('.batchChange').val('')
  }
})

 $('body').on('change', '.statusChange', function(e){
   e.preventDefault();
  //  console.log($(this).attr('id'));
   $token = $('input[name="_token"]').attr('value');
   $id = $(this).attr('id');
   $status = $(this).val();

   if ($status == 3) {

     $url = 'http://chris.zurka.com/part-reject';
     $data = {
       'modalId': 'part-reject-'+$id,
       'id': $id
     };

     $.ajax({
 			type: "GET",
 			url: $url,
 			data: $data,
 			error: function(data) {
 				var errors = data.responseJSON;
 				var messages = [];
 				for(bundle in errors) {
 					for(key in errors[bundle]) {
 						messages.push(errors[bundle][key]);
 					}
 				}
 				displayError($('#errorForPage'), messages);
 			},
 			success: function(data) {
 				// add the modal to the DOM and show it
 				$('#main-content').append(data);
 			}
 		});
     return ;
   }


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

 });

 </script>
@stop
