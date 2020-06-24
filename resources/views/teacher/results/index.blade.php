@extends('layouts.master')

@section('section-title')
Résultats
@endsection

@section('content')
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

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/ddslick.min.js') }}"></script>

<script>
$(document).ready(function() {

  let topicIdParam = $("#moduleSelect").val()

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
      console.log('topic_id', data.selectedData.value)


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
          console.log('section_id', data.selectedData.value)
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
  handleQuestionsLoad();

  function handleQuestionsLoad() {
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
          url: "{{route('ajax.results')}}",
          type:'GET',
          data: function (d) {
          d.search=(".dataTables_filter input[type=search]").value;
          d.topic_id = get('topic_id');
          d.section_id = get('section_id');
          }

        },
        columns: [
            { data: 'name', name: 'name'},
            { data: 'score', name: 'score'}
        ]
    });

    return table

  }

})
function get(name){
   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}
</script>
@endsection