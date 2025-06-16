@extends('admin.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Reminder Management</h1>
            <small class="text-muted">Admin · Add New Reminder</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <h2 class="text-gray-800 text-l font-semibold">Add New Reminder</h2>
            </div>
        </header>
        <div class="card">
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route('admin.reminder.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
<!-- Technician select -->
<div class="form-group col-md-12">
    <label>Technician</label>
    <select name="user_id" id="technicianSelect" class="form-control" required>
        <option value="" disabled selected>-- Choose Technician --</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
</div>

<!-- Laboratory select -->
<div class="form-group col-md-12">
    <label>Laboratory</label>
    <select name="laboratory_id" id="laboratorySelect" class="form-control" required>
        <option value="" disabled selected>-- Choose Laboratory --</option>
    </select>
</div>


                            <div class="form-group col-md-12">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Description"></textarea>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Reminder Date</label>
                                <input type="text" id="reminder_date" name="reminder_date" class="form-control" value="{{ old('reminder_date') }}" placeholder="Select date" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-calendar-plus"></i> Save Reminder
                        </button>
                        <a href="{{ route('admin.reminder.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
                            
                            
@endsection
    @section('script')
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#reminder_date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: true
        });
    </script>
<script>
document.getElementById('technicianSelect').addEventListener('change', function () {
    const techId = this.value;
    const labSelect = document.getElementById('laboratorySelect');

    labSelect.innerHTML = '<option disabled selected>Loading...</option>';

    fetch(`/admin/get-laboratories/${techId}`)
        .then(res => res.json())
        .then(data => {
            labSelect.innerHTML = '<option disabled selected>-- Choose Laboratory --</option>';
            data.forEach(lab => {
                const opt = document.createElement('option');
                opt.value = lab.id;
                opt.textContent = lab.lab_name;
                labSelect.appendChild(opt);
            });
        })
        .catch(err => {
            labSelect.innerHTML = '<option disabled selected>Error loading laboratories</option>';
            console.error(err);
        });
});
</script>


    @endpush
@endsection