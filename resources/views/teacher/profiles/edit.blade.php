@extends('layouts.master')
@section('section-title')
ITerview
@endsection

@section('profile')
<div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">
                Profile
              </h6>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
            <img src="{{ asset('img/background.jpg') }}" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <img src="/uploads/profiles/{{ $user->profile->image }}" class="rounded-circle">
                  </a>
                </div>
              </div>
            </div>
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col">
                  <div class="card-profile-stats d-flex justify-content-center">
                    <div>
                      <span class="heading">{{$user->topics()->count()}}</span>
                      <span class="description">Modules</span>
                    </div>
                    <div>
                      <span class="heading">{{$sections_count}}</span>
                      <span class="description">Sections</span>
                    </div>
                    <div>
                      <span class="heading">{{$students_count}}</span>
                      <span class="description">Etudiants</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h5 class="h3">
                  {{$user->name}}
                </h5>
                <div class="h5 font-weight-300">
                  <i class="ni location_pin mr-2"></i>Oujda, Morroco
                </div>
                <div class="h5 mt-4">
                  <i class="ni business_briefcase-24 mr-2"></i>{{ $user->profile->speciality }} - professeur
                </div>
                <div>
                  <i class="ni education_hat mr-2"></i>{{ $user->profile->university }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8 order-xl-1">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Edit profile </h3>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form method="POST" action="{{url("/teacher/profile")}}/{{$user->profile->id}}" enctype="multipart/form-data">
              <input type="hidden" name="_method" value="PUT">
              @csrf
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="name">Nom et prénom</label>
                        <input name="name" type="text" id="input-username" class="form-control" placeholder="Username" value="{{$user->name}}" required>
                        @error('name')
                                    <span>
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="email">Email</label>
                        <input name="email" type="email" id="input-email" class="form-control" placeholder="Email" value="{{$user->email}}" required>
                        @error('email')
                                    <span >
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="specialite">Spécialite</label>
                        <input name="speciality" type="text" id="input-specialite" class="form-control" placeholder="Specialite" value="{{$user->profile->speciality}}" required>
                        @error('speciality')
                                    <span>
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="university">Ecole</label>
                        <input name="university" type="text" id="input-ecole" class="form-control" placeholder="Ecole" value="{{$user->profile->university}}" required>
                        @error('university')
                                    <span>
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="university">L'image</label>
                        <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="add-image">
                        <label class="custom-file-label" for="image">Choisissez l'image</label>
                        @error('image')
                            <span>
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                      </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Description -->
                <div class="pl-lg-4">
                  <div class="form-group">
                    <label class="form-control-label" for="about">About Me</label>
                    <textarea name="about" rows="4" class="form-control" placeholder="A few words about you ...">{{$user->profile->about}}</textarea>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary float-right">Edit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('scripts')

{{-- import iterview utilities --}}
<script src="{{ asset('js/iterview.js') }}"></script>

<script>

</script>

@endsection