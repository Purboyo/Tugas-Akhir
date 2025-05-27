@extends('kepala_lab.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <ul>
      <li>Chief Laboratory</li>
      <li>Dashboard</li>
    </ul>
  </div>
</section>

{{-- Main Content --}}
  <section class="section main-section">
    <section class="section main-section">
    </section>
    <div class="grid gap-6 grid-cols-1 md:grid-cols-3 mb-6">
      <div class="card">
        <div class="card-content">
          <div class="flex items-center justify-between">
            <div class="widget-label">
              <h3>Errors</h3>
              <h1>512</h1>
            </div>
            <span class="icon widget-icon text-green-500"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
          </div>
        </div>
      </div>
      <!-- More cards here -->
    </div>
  </section>

@endsection