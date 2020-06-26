@extends('layouts.master')

@section('section-title')
Résultats
@endsection

@section('content')
<div class="modal fade" id="modChart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="body_chart">
          <canvas id="chart" width="500" height="300"></canvas>
      </div>
    </div>
  </div>
</div>
<div class="card">
  <div class="card-header border-0">
    <h3 class="mb-0">Résultats</h3>
  </div>
  
  <div class="card-body">
    {{-- Module select --}}
    <select id="moduleSelect">
      @foreach ($topics as $topic)
      <option 
        data-imagesrc={{'/uploads/topics/' . $topic->image}}
        value="{{ $topic->id }}">
        {{ $topic->label }}
      </option>
      @endforeach
    </select>

    {{-- Quiz select --}}
    <div id="quizSelect"></div>
  </div>

    {{-- Table result --}}
    <div class="table-responsive">
      <table class="table align-items-center table-flush" id="resultsTable">
        <thead class="thead-light">
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Score</th>
          </tr>
        </thead>
        <tbody class="list">
          {{-- Magic happens here ssi l7aj ! no data !! but there is ! thanks to ajax ;-) --}}
        </tbody>
      </table>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/ddslick.min.js') }}"></script>

<script>
$(document).ready(function() {

  let topicIdParam = $("#moduleSelect").val()

  let sectionIdParam = -1


  const table = handleResultLoad();

  // ddslick modules
  $("#moduleSelect").ddslick({
    onSelected: function(data) {
      topicIdParam = data.selectedData.value;

      // get section data
      const ddData = fetchSections(topicIdParam)

      // destroy last select and init a new one with the data
      populateDataQuizSelect(ddData)

      /*
      ===== selectedData.value -> topic_id
      =====
      */
      topicIdParam = data.selectedData.value


      // const url = "{{route('ajax.sections')}}" + '?topic_id=' + topicIdParam
      // table.ajax.url(url)
      // table.ajax.reload();
      // console.log(table.ajax.url())
    }
  });

  function populateDataQuizSelect(ddData) {
    $('#quizSelect').ddslick('destroy')

    // ddslick quizzes
    $('#quizSelect').ddslick({
      data: ddData,
      width:350,
      selectText: "Pas de quiz",
      imagePosition:"left",
      onSelected: function(data){
          /*
          ===== data.selectedData.value -> section_id
          =====
          */

          sectionIdParam = data.selectedData.value
          console.log('topic_id: ', topicIdParam)
          console.log('section_id: ', sectionIdParam)
  
          const url = "{{route('ajax.results')}}" + '?topic_id=' + topicIdParam + '&section_id=' + sectionIdParam
          table.ajax.url(url)
          table.ajax.reload();
          console.log(table.ajax.url())
      }   
    });
  }

  function fetchSections(id) {
    let data = [];

    $.ajax({    
      url: "{{ url('/ajax/sections/ddslick/') }}" + '/' + id,
      method: 'GET',
      dataType: 'json',
      async: false,

      success: function(response){
        data = response;
      } 
    });

    data.length ? data[0].selected = true : undefined;

    if(data.length === 0) {
      return [{
        value: -1,
        text: 'Pas de quiz',
        imageSrc: 'https://cdn4.iconfinder.com/data/icons/pretticons-1/64/not-found-512.png',
        selected: true,
      }];
    }

    return data;
  }
  function handleResultLoad() {
      const topicIdGET = "{{ $topic_id }}"
      const sectionIdGET = "{{ $section_id }}"

      let url = undefined

      if(topicIdGET && sectionIdGET) {
        url = "{{route('ajax.results')}}" + '?topic_id=' + topicIdGET + '&section_id=' + sectionIdGET
      } else {
        url = "{{route('ajax.results')}}"
      }

      const table = $('#resultsTable').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "lengthMenu": "Afficher _MENU_ éléments",
            "sInfo":"Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "zeroRecords": "Aucune resultas",
            "search": "Rechercher",
            "oPaginate": {
                "sNext":     "Suivant",
                "sPrevious": "Précédent"
    },
        },
        ajax: {
          url: url,
          type:'GET'

        },
        columns: [
            { data: 'name', name: 'name'},
            { data: 'score', name: 'score'}
        ]
    });

    return table

  }

});
progressUser();
function get(name){
   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}
function progressUser(){
    $('#modChart').on('shown.bs.modal',function(event){
    var link = $(event.relatedTarget);
    // get data source
    var user_id = link.attr('data-user');
    var section_id=link.attr('data-section');
    // get title
    var title = link.html();
    // get data
    let dataResult = undefined;

    $.ajax({    
      url: "{{ route('progresses') }}" + '?user_id=' + user_id + '&section_id=' +section_id,
      method: 'GET',
      dataType: 'json',
      async: false,

      success: function(response){
        dataResult = response;
      } 
    });

    console.log(dataResult)
    
    var modal = $(this);
    var test=[]
    for(var i=0;i < dataResult.result.length;i++){
      test[i]=i+1;
    }
    var canvas = modal.find('.modal-body canvas');
    modal.find('.modal-title').html(title);
    var ctx = document.getElementById("chart");
    var chart = new Chart(ctx,{ 
        type:'line',
        data:{
          labels: test,
          datasets: [{
            label: "Score",
            lineTension: 0.3,
						backgroundColor: "rgba(2,117,216,0.2)",
						borderColor: "rgba(2,117,216,1)",
						pointRadius: 8,
						pointBackgroundColor: "rgba(2,117,216,1)",
						pointBorderColor: "rgba(255,255,255,0.8)",
						pointHoverRadius: 5,
						pointHoverBackgroundColor: "rgba(2,117,216,1)",
						pointHitRadius: 20,
						pointBorderWidth: 2,
            data: dataResult.result
        }],
          },
        options:{
    scales: {
        yAxes: [{
            ticks: {
                max: dataResult.questionsCount,
                min: 0,
                stepSize: 5
            }
        }]
    }
}
    });
  }).on('hidden.bs.modal',function(event){
    // reset canvas size
    var modal = $(this);
    var canvas = modal.find('.modal-body canvas');
    // destroy modal
    $('#chart').remove(); // this is my <canvas> element
    $('#body_chart').append('<canvas id="chart"><canvas>');
    $(this).data('bs.modal', null);
  });
  }
</script>
@endsection