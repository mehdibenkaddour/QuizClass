@extends('layouts.master')

@section('section-title')
ITerview
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
    Voulez-vous vraiment supprimer cet élement !
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
    <h3 class="mb-0">La Liste des étudiants</h3>
  </div>
  <!-- Light table -->
  <div class="table-responsive">
    <table class="table align-items-center table-flush" id="studentsTable">
      <thead class="thead-light">
        <tr>
          <th scope="col">Le nom et prénom</th>
          <th scope="col">Email</th>
          <th scope="col">Les actions</th>
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

<script>

$(document).ready(function() {

  const table = handleStudentsLoad();

  handleStudentsDelete();

  function handleStudentsLoad() {
    // Datatables config
    const table = $('#studentsTable').DataTable({
        processing: true,
        serverSide: true,
        language: {
            "lengthMenu": "Afficher _MENU_ éléments",
            "sInfo":"Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "zeroRecords": "Aucun élement",
            "search": "Rechercher",
            "oPaginate": {
                "sNext":     "Suivant",
                "sPrevious": "Précédent"
    },
        },
        ajax: {
          url: "{{route('ajax.students')}}",
          type:'GET',
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
  
</script>

@endsection