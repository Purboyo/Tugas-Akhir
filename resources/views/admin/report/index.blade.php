@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Laporan Form</h1>
            <small class="text-muted">{{ ucfirst(auth()->user()->role) }}. Manajemen Laporan</small>
        </div>
    </div>
</section>

<section class="section main-section">
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "timeOut": "3000"
                };
                toastr.success("{{ session('success') }}");
            });
        </script>
    @endif

    <div class="card has-table">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon h2"><i class="mdi mdi-file-document-box"> Daftar Laporan</i></span>
            </div>
            <div class="card-header-actions">
                <form method="GET" action="{{ route($role . '.report.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Cari nama/NPM/PC..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                </form>
            </div>
        </header>

        <div class="card-content px-4 py-2">
            <table class="table is-fullwidth is-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NPM</th>
                        <th>PC</th>
                        <th>Form</th>
                        <th>Tanggal</th>
                        <th>Jawaban</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->reporter->name }}</td>
                            <td>{{ $report->reporter->npm }}</td>
                            <td>
                                {{ $report->pc->pc_name ?? 'PC-' . $report->pc_id }},
                                {{ $report->pc->lab->lab_name ?? 'Lab-' . optional($report->pc)->lab_id }}
                            </td>
                            <td>{{ $report->form->title }}</td>
                            <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <button onclick="openAnswerModal({{ $report->id }})" class="btn btn-sm btn-info text-white">
                                    <i class="mdi mdi-eye"></i> Lihat
                                </button>
                            </td>
                            <td>
                                <form action="{{ route($role . '.reportsdestroy', $report->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus laporan ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger text-white">
                                        <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Jawaban -->
<div id="answerModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="mdi mdi-comment-text-outline"></i> Jawaban Laporan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modalAnswersContent">
          <p>Loading jawaban...</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    function openAnswerModal(reportId) {
        fetch(`/reports/${reportId}/answers`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalAnswersContent').innerHTML = html;
                new bootstrap.Modal(document.getElementById('answerModal')).show();
            })
            .catch(error => {
                document.getElementById('modalAnswersContent').innerHTML = "<p class='text-danger'>Gagal memuat jawaban.</p>";
                console.error(error);
            });
    }
</script>
@endpush
