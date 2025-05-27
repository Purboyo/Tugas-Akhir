@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')
<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
        <ul>
            <li>Admin</li>
            <li>Daftar Laporan</li>
        </ul>
    </div>
</section>

<section class="section main-section">
    @if(session('success'))
        <div class="notification green mb-4">
            <div class="flex justify-between items-center">
                <span><i class="mdi mdi-check-circle"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="button small textual">Close</button>
            </div>
        </div>
    @endif

    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-file-document-box"></i></span>
                Daftar Laporan Form
            </p>
        </header>

        <div class="card-content">
            <table class="table is-fullwidth is-striped">
                <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Reporter</th>
                    <th>NPM</th>
                    <th>Komputer</th>
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
                            <button onclick="openAnswerModal({{ $report->id }})" class="button small blue">Lihat</button>
                        </td>
                        <td>
                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                    Hapus
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
<div id="answerModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative animate-fade-in">
        <button onclick="closeAnswerModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">&times;</button>
        <h2 class="text-lg font-bold mb-4">Detail Jawaban</h2>
        <div id="modalContent">
            <p class="text-sm text-gray-500">Memuat...</p>
        </div>
    </div>
</div>

<script>
    function openAnswerModal(reportId) {
        fetch(`/api/report/${reportId}/answers`)
            .then(res => res.json())
            .then(data => {
                const modal = document.getElementById('answerModal');
                const content = document.getElementById('modalContent');
                content.innerHTML = data.length
                    ? data.map(ans => `
                        <div class="mb-4">
                            <p class="font-semibold">${ans.question}</p>
                            <p class="text-gray-700">${ans.answer}</p>
                        </div>
                    `).join('')
                    : '<p class="text-sm text-gray-500">Tidak ada jawaban.</p>';
                modal.classList.remove('hidden');
            });
    }

    function closeAnswerModal() {
        document.getElementById('answerModal').classList.add('hidden');
    }
</script>
@endsection
