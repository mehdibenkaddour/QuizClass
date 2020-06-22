
@extends('layouts.master')
@section('section-title')
ITerview
@endsection

@section('profile')
<div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
      <!-- Mask -->
      <span class="mask bg-gradient-default opacity-8" style="background:linear-gradient(87deg, #5e72e4 0, #d5d4e0 100%) !important"></span>
      <!-- Header container -->
      <div class="container-fluid d-flex align-items-center">
        <div class="row">
          <div class="col-lg-7 col-md-10">
            <h1 class="display-3 text-white">Hello {{$user->name}}</h1>
            <p class="text-white mt-0 mb-5">Votre page de profile.</p>
            <a href="#!" class="btn btn-neutral">Edit profile</a>
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
                  <i class="ni business_briefcase-24 mr-2"></i>Interconnexion Résseaux - professeur
                </div>
                <div>
                  <i class="ni education_hat mr-2"></i>Ecole national des sciences appliqueés Oujda
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
              <form>
                <h6 class="heading-small text-muted mb-4">User information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-username">Username</label>
                        <input type="text" id="input-username" class="form-control" placeholder="Username" value="{{$user->name}}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email address</label>
                        <input type="email" id="input-email" class="form-control" placeholder="Email" value="{{$user->email}}">
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Description -->
                <h6 class="heading-small text-muted mb-4">About me</h6>
                <div class="pl-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">About Me</label>
                    <textarea rows="4" class="form-control" placeholder="A few words about you ...">{{$user->profile->about}}</textarea>
                  </div>
                </div>
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