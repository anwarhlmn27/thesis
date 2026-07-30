@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-check-circle mr-2 text-white"></i>Portal BAAK</h4>
                <p class="mb-0 text-white-50">Validasi kelayakan akademik dan SKS</p>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-akademik-tab" data-bs-toggle="pill" data-bs-target="#pills-akademik" type="button" role="tab">Validasi Kelayakan Sidang Skripsi (Cek Nilai)</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-proposal-tab" data-bs-toggle="pill" data-bs-target="#pills-proposal" type="button" role="tab">Validasi Kelayakan Seminar Proposal</button>
      </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-akademik" role="tabpanel">
          <div class="card shadow-sm border-0">
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped">
                          <thead class="table-light">
                              <tr>
                                  <th>Mahasiswa</th>
                                  <th>Prodi / Smt</th>
                                  <th>Status Kelayakan Akademik (SKS)</th>
                                  <th>Aksi</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($students as $student)
                              <tr>
                                  <td>
                                      <strong>{{ $student->user->name }}</strong><br>
                                      <small class="text-muted">NIM: {{ $student->nim }}</small>
                                  </td>
                                  <td>{{ $student->prodi }} / {{ $student->semester }}</td>
                                  <td>
                                      @if($student->is_coursework_completed)
                                          <span class="badge badge-success"><i class="fa fa-check"></i> SKS Memenuhi Syarat</span>
                                      @else
                                          <span class="badge badge-danger">Belum Memenuhi Syarat</span>
                                      @endif
                                  </td>
                                  <td>
                                      @if($student->is_coursework_completed)
                                          <form action="{{ route('baak.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Batalkan status layak akademik mahasiswa ini?">
                                              @csrf
                                              <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan Status</button>
                                          </form>
                                      @else
                                          <form action="{{ route('baak.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Tandai mahasiswa ini memenuhi syarat SKS?">
                                              @csrf
                                              <input type="hidden" name="is_coursework_completed" value="1">
                                              <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Validasi SKS</button>
                                          </form>
                                      @endif
                                  </td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>

      <div class="tab-pane fade" id="pills-proposal" role="tabpanel">
          <div class="card shadow-sm border-0">
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped">
                          <thead class="table-light">
                              <tr>
                                  <th>Mahasiswa</th>
                                  <th>Judul Proposal</th>
                                  <th>Status Proposal (Akademik)</th>
                                  <th>Aksi</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($proposals as $proposal)
                              <tr>
                                  <td>
                                      <strong>{{ $proposal->thesis->student->user->name }}</strong><br>
                                      <small class="text-muted">NIM: {{ $proposal->thesis->student->nim }}</small>
                                  </td>
                                  <td>{{ $proposal->thesis->title }}</td>
                                  <td>
                                      @if($proposal->is_baak_approved)
                                          <span class="badge badge-success"><i class="fa fa-check"></i> Layak Seminar</span>
                                      @else
                                          <span class="badge badge-warning">Menunggu Validasi</span>
                                      @endif
                                  </td>
                                  <td>
                                      @if($proposal->is_baak_approved)
                                          <form action="{{ route('baak.clearance.update_proposal', $proposal->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Batalkan validasi kelayakan seminar proposal ini?">
                                              @csrf
                                              <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan Validasi</button>
                                          </form>
                                      @else
                                          <form action="{{ route('baak.clearance.update_proposal', $proposal->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Tandai proposal ini layak untuk diseminarkan?">
                                              @csrf
                                              <input type="hidden" name="is_baak_approved" value="1">
                                              <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Validasi Proposal</button>
                                          </form>
                                      @endif
                                  </td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>
@endsection
