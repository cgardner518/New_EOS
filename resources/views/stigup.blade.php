@extends("Labcoat::layouts/standard")
<script src="{{ URL::asset('build/js/vue.min.js') }}"></script>
<script src="{{ URL::asset('build/js/processing.js') }}" ></script>
@section('custom-css')
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <style>
         #main-content{
            font-family: 'Helvetica';
            display: flex;
            justify-content: center;
            flex-direction: column;
          }
          .container{
            width: 100%;
            min-height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
          }
          .content{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;

          }
          .stigdiv{
            display: flex;
            align-items: center;
            flex-direction: column;
            padding: 2em;
            margin-bottom: 2em;
            border: 4px inset white;
            border-radius: 10px;
            width: 50vw;
          }
          .high{
            box-shadow: 1px 5px 18px maroon inset;
          }
/*rgba(239, 255, 0, 0.34)*/
          .medium{
            box-shadow: 1px 5px 18px #BA834A inset;
          }
          .low{
            box-shadow: 1px 5px 18px grey inset;
          }
          p{
            font-size: 1em;
          }
          canvas{
            position: absolute;
            top: 0;
            left:  0;
            z-index: 9999999999;
          }
    </style>
@endsection

@section('title')
    DEV STIG VIEWER
@endsection

@section('page-title')
    DEV STIG VIEWER
@endsection

@section('main-content')
    <div class="container">
      <div class="col-md-10 pull-left">
        <button type="button" name="button" class="btn btn-info pull-left">Drawing Time?</button>
      </div>
        <div class="content">
          <h3></h3>
          <div class="col-md-12">
            <div class="col-md-2 pull-right">
              <input  v-model="search" class="form-control" placeholder="Filter by severity...">
            </div>
          </div>
          <br>
              <div v-for="finding in findings | filterBy search in 'severity'" class="stigdiv @{{finding.severity}}" >
                <div class="col-md-3">
                  <b>Severity: </b>@{{finding.severity}}
                </div>
                <div class="col-md-12">
                  <b>Title</b>:
                  <p>
                    @{{finding.title}}
                  </p>
                </div>
                <div class="col-md-12">
                  <b>Description</b>:
                  <p>
                    @{{finding.description}}
                  </p>
                </div>
                <div class="col-md-12">
                  <b>Check</b>:
                  <p>
                    @{{finding.checktext}}
                  </p>
                </div>
              </div>


        </div>
    </div>
@endsection

@section('custom-js')
<script type="text/javascript">
var relevant = [];
  $.ajax({
    url: '/stigz',
    method: 'GET',
    success: function(res){
      $('.content h3').text(res.stig.title+' (Designer)');
      $('.content').append()
      $.each(res.stig.findings, function(index, finding){
        if (finding.title.indexOf('The designer') >= 0) {
          relevant.push(finding)
        }
      })
      // console.log(relevant);
    }

  })

  new Vue({
    el: '.content',

    data: {
      findings: relevant
    }
  })

  // setTimeout(function(){
  $('button').click(function(){
    $('body').append('<canvas id="canvas" ></canvas>')
    var canvas = document.getElementById("canvas");
    var count = 0;
    function testerino (processing){
      processing.size(window.innerWidth - 10, window.innerHeight- 15);
      processing.background(0,0,0)

      var count = 80;
      processing.mouseMoved = function() {
        if (count > 250) {
          count = 75;
        }
        count ++;
        var mouse  = new PVector(processing.mouseX, processing.mouseY);
        var center = new PVector(processing.width/2, processing.height/2);
        mouse.sub(center);

        processing.resetMatrix();
        processing.translate(processing.width/2, processing.height/2);
        processing.strokeWeight(2);

        processing.stroke(0, count, 150);
        processing.fill(count, 0, mouse.y);
        processing.ellipse(mouse.x,mouse.y, 30,30);

        processing.stroke(0, count, 150);
        processing.fill(0, 0, mouse.x*count);
        processing.ellipse(mouse.y,mouse.x, 30,30);

        processing.stroke(0, count, 150);
        processing.fill(mouse.x,0, count);
        processing.ellipse(mouse.x*-1,mouse.y*-1, 30,30);

        processing.stroke(0, count, 150);
        processing.fill(0, count*mouse.y, 0);
        processing.ellipse(mouse.y*-1,mouse.x*-1, 30,30);
      };

      processing.touchMove = function() {
        if (count > 250) {
          count = 75;
        }
        count ++;
        var mouse  = new PVector(processing.mouseX, processing.mouseY);
        var center = new PVector(processing.width/2, processing.height/2);
        mouse.sub(center);

        processing.resetMatrix();
        processing.translate(processing.width/2, processing.height/2);
        processing.strokeWeight(2);

        processing.stroke(0, count, 150);
        processing.fill(count, 0, mouse.y);
        processing.ellipse(mouse.x,mouse.y, 30,30);

        processing.stroke(0, count, 150);
        processing.fill(0, 0, mouse.x*count);
        processing.ellipse(mouse.y,mouse.x, 30,30);

        processing.stroke(0, count, 150);
        processing.fill(mouse.x,0, count);
        processing.ellipse(mouse.x*-1,mouse.y*-1, 30,30);

        processing.stroke(0, count, 150);
        processing.fill(0, count*mouse.y, 0);
        processing.ellipse(mouse.y*-1,mouse.x*-1, 30,30);
      };
    };
    var processingInstance = new Processing(canvas, testerino);
  })
  // }, 1000)
</script>
@stop
