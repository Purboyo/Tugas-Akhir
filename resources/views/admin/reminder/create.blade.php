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
            <div class="card-body text-dark">
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

                            <!-- Title -->
                            <div class="form-group col-md-12">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" required>
                            </div>

                            <!-- Description -->
                            <div class="form-group col-md-12">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Description"></textarea>
                            </div>

                            <!-- Reminder Date -->
                            <div class="form-group col-md-12">
                                <p class="mb-1">Reminder Date</p>
                                <input name="reminder_date" class="datepicker-default form-control" id="datepicker" placeholder="Select date" required>
                            </div>
                        </div>

                        </div>
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="mdi mdi-calendar-plus"></i> Save Reminder
                        </button>
                        <a href="{{ route('admin.reminder.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
<script>
document.addEventListener("DOMContentLoaded", function () {
    const technicianSelect = document.getElementById('technicianSelect');
    const labSelect = document.getElementById('laboratorySelect');

    if (!technicianSelect || !labSelect) {
        console.log("❌ Select element not found!");
        return;
    }

    console.log("✅ Elements ditemukan");

    technicianSelect.addEventListener('change', function () {
        const techId = this.value;
        console.log("👤 Technician ID selected:", techId);

        if (!techId) return;

        labSelect.innerHTML = '<option disabled selected>Loading...</option>';

        fetch(`/admin/get-laboratories/${techId}`)
            .then(response => {
                if (!response.ok) throw new Error("Fetch failed");
                return response.json();
            })
            .then(data => {
                console.log("📦 Data labs:", data);

                labSelect.innerHTML = '';

                const chooseOption = document.createElement('option');
                chooseOption.disabled = true;
                chooseOption.selected = true;
                chooseOption.textContent = "-- Choose Laboratory --";
                labSelect.appendChild(chooseOption);

                data.forEach(lab => {
                    const option = document.createElement('option');
                    option.value = lab.id;
                    option.textContent = lab.lab_name;
                    labSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('🚨 Error fetching laboratories:', error);
                labSelect.innerHTML = '<option disabled selected>Error loading laboratories</option>';
            });
    });
});
</script>




