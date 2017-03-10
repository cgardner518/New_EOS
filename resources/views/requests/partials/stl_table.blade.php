@php
  $tobe = ['No', 'Yes'];
  $status = ['Pending', 'In Process', 'Complete', 'Rejected'];
@endphp
<table>
  <thead>
    <th style="width:30em;">STL File</th>
    <th>X</th>
    <th>Y</th>
    <th>Z</th>
    <th>Cleaning</th>
    <th>Moving Parts</th>
    <th>Threads</th>
      <th>Status</th>
    <th>Delete</th>
  </thead>
  <tbody>
    @if (count($eos->stl_files)>0)
      @foreach ($eos->stl_files->reverse() as $stl)
        <tr class="{{$stl->id}}">
          <td>{{ $stl->file_name }}</td>
          <td>{{ $stl->dimX }}</td>
          <td>{{ $stl->dimY }}</td>
          <td>{{ $stl->dimZ }}</td>
          <td align="center">{{ $tobe[$stl->clean] }}</td>
          <td align="center">{{ $tobe[$stl->hinges] }}</td>
          <td align="center">{{ $tobe[$stl->threads] }}</td>
          @if (Auth::user()->can('eosAdmin'))
            <td>{!! Form::select('status', [0 => 'Pending', 1 => 'In Process', 3 => 'Rejected', 2 => 'Complete'], $stl->status, ['class' => 'statusChange']) !!}</td>
          @else
            <td>{{$status[$stl->status]}}</td>
          @endif
          <td align="center"><a href="javascript:undefined;" class="fa fa-fw fa-trash" data-delete-url="{{ URL::route('stl.destroy', $stl->id) }}" style="text-decoration: none;"></a></td>
        </tr>
      @endforeach
    @else
      <tr class="no_parts">
        @if ($eos->users->can('eosAdmin'))
          <td colspan="9" class="text-center">No parts have been uploaded yet.</td>
        @endif
      </tr>
    @endif
  </tbody>
</table>
<script type="text/javascript">

$('body').on('change', '.statusChange', function(e){
  e.preventDefault();

  $token = $('input[name="_token"]').attr('value');
  $id = $(this).parent().parent().attr('class');
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
    console.log(res);
  })

})
</script>
