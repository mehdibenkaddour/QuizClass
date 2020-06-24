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

})
</script>
@endsection