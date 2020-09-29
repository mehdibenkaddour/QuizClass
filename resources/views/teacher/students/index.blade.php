@extends('layouts.master')

@section('section-title')
Gestion des étudiants
@endsection

@section('content')
@component('teacher.helpers.modal')
    @slot('title')
        Supprimer un élement
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
    Voulez-vous vraiment supprimer l'etudiant !
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

<div class="card">
  <!-- Card header -->
  <div class="card-header border-0">
    <h3 class="mb-0">Liste des étudiants</h3>
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
    <table class="table align-items-center table-flush" id="studentsTable">
      <thead class="thead-light">
        <tr>
          <th scope="col">Nom et prénom</th>
          <th scope="col">Email</th>
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
/* Show the modal */
$(document).ready(function() {
  let topicIdParam = $("#moduleSelect").val()

  const table = handleStudentLoad()

  if("{{ $topic_id }}") {
    $("#moduleSelect").hide()

  } else {
    // ddslick plugin
    $("#moduleSelect").ddslick({
      onSelected: function(data) {
        topicIdParam = data.selectedData.value;
        const url = "{{route('ajax.students')}}" + '?topic_id=' + topicIdParam
        table.ajax.url(url)
        table.ajax.reload();
        console.log(table.ajax.url())
      }
    });
  }

  handleStudentsDelete();

  function handleStudentLoad() {
    const topicIdGET = "{{ $topic_id }}"

    let url = undefined

    if(topicIdGET) {
      url = "{{route('ajax.students')}}" + '?topic_id=' + "{{ $topic_id }}"
    } else if(topicIdParam) {
      url = "{{route('ajax.students')}}" + '?topic_id=' + topicIdParam
    } else {
      url = "{{route('ajax.students')}}"
    }

    // Datatables config
    const table = $('#studentsTable').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "lengthMenu": "Afficher _MENU_ éléments",
            "sInfo":"Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "zeroRecords": "Aucun étudiants",
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
            { data: 'name', name: 'users.name' },
            { data: 'email', name: 'users.email' },
            { data: 'actions', name: 'actions' }
        ]
    });

    return table;
  }
  function handleStudentsDelete() {
    // DELETE A Topic
    $('#studentsTable tbody').on('click', 'button.delete', function() {
      // get topic id
      const enrollId = $(this).data('id');

      // set action
      $('#delete-form').attr('action', '{{url("/students")}}'+"/" + enrollId)

      // show the modal
      $('#delete-modal').modal('show');
    });
  }

});

function get(name){
   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}

</script>

@endsection
