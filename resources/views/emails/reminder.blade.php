<h2>Reminder Maintenance Hari Ini</h2>
<p>Hai {{ $reminder->user->name }},</p>

<p>Ini adalah pengingat bahwa kamu memiliki jadwal maintenance hari ini:</p>

<ul>
    <li><strong>Judul:</strong> {{ $reminder->title }}</li>
    <li><strong>Lab:</strong> {{ $reminder->laboratory->lab_name }}</li>
    <li><strong>Tanggal:</strong> {{ $reminder->reminder_date }}</li>
    <li><strong>Deskripsi:</strong> {{ $reminder->description ?? '-' }}</li>
</ul>

<p>Segera lakukan pengecekan ya!</p>
