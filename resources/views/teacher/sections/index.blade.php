@extends('layouts.master')

@section('section-title')
Gestion des sujets
@endsection

@section('content')
@component('teacher.helpers.modal')
    @slot('title')
        Supprimer un quiz
    @endslot
    
    @slot('modalId')
        delete-modal
    @endslot

    @slot('formId')
        delete-form
    @endslot

    @slot('method')
        DELETE
    @endslot

    @slot('content')
    Voulez-vous vraiment supprimer ce quiz !
    @endslot

    @slot('cancel')
    Annuler
    @endslot

    @slot('confirm')
    Oui, supprimer
    @endslot

    @slot('submitId')
      deleteBtn
    @endslot
@endcomponent


<!-- Edit Modal Component -->

@component('teacher.helpers.modal')
    @slot('title')
    Modifier un quiz
    @endslot

    @slot('modalId')
    edit-modal
    @endslot

    @slot('formId')
    edit-form
    @endslot

    @slot('method')
    PUT
    @endslot

    @slot('content')
      <div class="form-group">
        <label>Titre</label>
        <input type="text" name="label" value="" class="form-control" id="edit-label">
        <span class="text-danger">
            <strong id="edit-label-error"></strong>
        </span>
      </div>
      <div class="form-group">
        <label>Module</label>
        <select class="browser-default custom-select" name="edit-topic" id="edit-topic">
        @foreach($topics as $topic )
        <option value="{{$topic->id}}">{{$topic->label}}</option>
        @endforeach
        </select>
      </div>
      <div class="form-group">
       <label>Image</label>
       <input type="file" name="image" class="section-img" id="edit-image">

        <span class="text-danger">
              <strong id="edit-image-error"></strong>
        </span>
      </div>
    @endslot

    @slot('cancel')
        Annuler
    @endslot

    @slot('confirm')
        Modifier
    @endslot

    @slot('submitId')
      editBtn
    @endslot

@endcomponent

<!-- ADD Modal Component -->

@component('teacher.helpers.modal')
    @slot('title')
    Ajouter un quiz
    @endslot

    @slot('modalId')
    add-modal
    @endslot

    @slot('formId')
    add-form
    @endslot

    @slot('method')
    POST
    @endslot

    @slot('content')
      <div class="form-group">
        <label>Titre</label>
        <input type="text" name="add-label" value="" class="form-control" id="add-label">
        <span class="text-danger">
            <strong id="add-label-error"></strong>
        </span>
      </div>
      <div class="form-group">
       <label>Module</label>
       <select class="browser-default custom-select" name="add-topic" id="add-topic">
        <option selected disabled>Choisissez un module</option>
        @foreach($topics as $topic )
        <option value="{{$topic->id}}">{{$topic->label}}</option>
        @endforeach
      </select>
      <span class="text-danger">
            <strong id="add-topic-error"></strong>
      </span>
      </div>
      <div class="form-group">
       <label>L'image</label>
       <input type="file" name="image" class="section-img" id="add-image">
        <span class="text-danger">
              <strong id="add-image-error"></strong>
        </span>
      </div>
    @endslot

    @slot('cancel')
        Annuler
    @endslot

    @slot('confirm')
        Ajouter
    @endslot

    @slot('submitId')
      addBtn
    @endslot

@endcomponent

<div class="card">
  <!-- Card header -->
  <div class="card-header border-0">
    <h3 class="mb-0">Liste des quizzes</h3>
    <button class="btn btn-primary float-right add">
      Ajouter un quiz
      <i class="ni ni-fat-add"></i>
    </button>
  </div>
  <div class="card-body">
  @if (!$topic_id && count($topics) > 0)
    <select id="moduleSelect">
      @foreach ($topics as $topic)
      <option 
        data-imagesrc={{'/uploads/topics/' . $topic->image}}
        value="{{ $topic->id }}">
        {{ $topic->label }}
      </option>
      @endforeach
    </select>
  @endif
  </div>
  <!-- Light table -->
  <div class="table-responsive">
    <table class="table align-items-center table-flush" id="sectionsTable">
      <thead class="thead-light">
        <tr>
          <th scope="col">Quiz</th>
          <th scope="col">Module</th>
          <th scope="col">Actions</th>
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

{{-- import iterview utilities --}}
<script src="{{ asset('js/iterview.js') }}"></script>
<script src="{{ asset('js/ddslick.min.js') }}"></script>

<script>

$(document).ready(function() {
  // init dropify
  $('.section-img').dropify();

  let topicIdParam = $("#moduleSelect").val()

  const table = handleSectionsLoad();


  if("{{ $topic_id }}") {
    $("#moduleSelect").hide()

  } else {
    // ddslick plugin
    $("#moduleSelect").ddslick({
      onSelected: function(data) {
        topicIdParam = data.selectedData.value;
        const url = "{{route('ajax.sections')}}" + '?topic_id=' + topicIdParam
        table.ajax.url(url)
        table.ajax.reload();
        console.log(table.ajax.url())
      }
    });
  }

  handleSectionsDelete();

  handleSectionsEdit();

  handleSectionsAdd();

  function handleSectionsLoad() {
    const topicIdGET = "{{ $topic_id }}"

    let url = undefined
    
    if(topicIdGET) {
      url = "{{route('ajax.sections')}}" + '?topic_id=' + "{{ $topic_id }}"
    } else if(topicIdParam) {
      url = "{{route('ajax.sections')}}" + '?topic_id=' + topicIdParam
    } else {
      url = "{{route('ajax.sections')}}"
    }

    // Datatables config
    const table = $('#sectionsTable').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "lengthMenu": "Afficher _MENU_ sujets",
            "sInfo":"Affichage du sujet _START_ à _END_ sur _TOTAL_ sujets",
            "zeroRecords": "Aucun sujet",
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
            { data: 'section', name: 'section' },
            { data: 'topic', name: 'topic'},
            { data: 'actions', name: 'actions' }
        ]
    });

    return table;
  }

  function handleSectionsDelete() {
    // DELETE A Topic
    $('#sectionsTable tbody').on('click', 'button.delete', function() {
      // get topic id
      const sectionId = $(this).data('id');

      // set action
      $('#delete-form').attr('action', '{{url("/sections")}}'+"/" + sectionId)

      // show the modal
      $('#delete-modal').modal('show');
    });
  }

  function handleSectionsEdit() {
    // EDIT A topic
    $('#sectionsTable tbody').on('click', 'button.edit', function() {
      // get topic id
      const sectioncId = $(this).data('id');

      // set action
      $('#edit-form').attr('action', '{{url("/sections")}}'+"/"+ sectioncId);

      // reset selected option for each clicon edit
      $('#edit-topic > option').each(function(){
        $(this).attr('selected', false);
      });

      // fill inputs with data
      const label = $(this).parents().eq(2).siblings('td').first()[0].innerText;
      const topic = $(this).parents().eq(2).siblings('td')[1].innerText;

      $('#edit-label').val(label);
      $( '#edit-label-error' ).html( "" );
      $( '#edit-image-error' ).html( "" );

      // selected option for topic
      $('#edit-topic > option').each(function(){
        if($(this).text()==topic){
          $(this).attr('selected', true);
        }
      });

      // show the modal
      $('#edit-modal').modal('show');

      $('#edit-form').unbind('submit').submit(function(e){
        // turn button into loading state
        iterview.handleButtonLoading(true, '#editBtn');

        const urlForm= $(this).attr('action');
        e.preventDefault();
        $( '#edit-label-error' ).html( "" );
        $( '#edit-image-error' ).html( "" );

        var label= $('#edit-label').val();
        var image=$('#edit-image')[0].files[0];
        var topic=$('#edit-topic').val();
        var form = new FormData();

        form.append('label', label);
        form.append('image', image);
        form.append('topic', topic);
        form.append('_method','PUT');
        $.ajax({    
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: urlForm,
          method: 'POST',
          data: form,
          contentType: false,
          processData: false,

          success: function(result){
            // turn button into default state
            iterview.handleButtonLoading(false, '#editBtn');

            if(result.errors)
            {
              $('#edit-form').find('#edit-label-error').html(result.errors.label[0]);
            }
            else
            {
              iterview.handleSuccessResponse(table, result, '#edit-modal');
            }
          }});

      });

    });
  }

  function handleSectionsAdd() {
    // ADD TOPIC
    $('.add').on('click', function() {
      // set action
      $('#add-form').attr('action','{{url("/sections")}}');

      $( '#add-label-error' ).html( "" );

      // clear inputs
      $('#add-label').val('');
      $('#add-topic').get(0).selectedIndex = 0;

      // show the modal
      $('#add-modal').modal('show');

      $('#add-form').unbind('submit').submit(function(e){
        // turn button into loading state
        iterview.handleButtonLoading(true, '#addBtn');

        const urlForm= $(this).attr('action');
        e.preventDefault();
        $( '#add-label-error' ).html( "" );
        $('#add-image-error').html('');

        var label = $('#add-label').val();
        var image = $('#add-image')[0].files[0];
        var topic = $('#add-topic').val();
        var form = new FormData();
        form.append('label', label);
        form.append('image', image);
        form.append('topic', topic);
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: urlForm,
          method: 'POST',
          data: form,
          contentType: false,
          processData: false,

          success: function(result){
            // turn button into default state
            iterview.handleButtonLoading(false, '#addBtn');
            if(result.errors)
            {
              if(result.errors.label && result.errors.topic && result.errors.image){
                $('#add-form').find('#add-label-error').html(result.errors.label[0]);
              }else if(result.errors.topic){
                $('#add-form').find('#add-topic-error').html(result.errors.topic[0]);
              }else if(result.errors.image){
                $('#add-form').find('#add-image-error').html(result.errors.image[0]);
              }else{
                $('#add-form').find('#add-label-error').html(result.errors.label[0]);
              }
            }
            else
            {
              iterview.handleSuccessResponse(table, result, '#add-modal');
            }
          }});

      });

    });
  }
    
});
function get(name){
   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}
</script>

@endsection