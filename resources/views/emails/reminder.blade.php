<h2 style="color: #2c3e50;">📅 Jadwal Maintenance Telah Ditetapkan</h2>

<p>Hai <strong>{{ $reminder->laboratory->technician->name ?? 'Teknisi' }}</strong>,</p>

<p>Ini adalah pemberitahuan bahwa telah dijadwalkan pemeliharaan perangkat untuk laboratorium yang kamu tangani. Harap dicatat dan lakukan pengecekan sesuai jadwal berikut:</p>

<table cellpadding="8" cellspacing="0" border="0">
    <tr>
        <td><strong>📝 Judul</strong></td>
        <td>: {{ $reminder->title }}</td>
    </tr>
    <tr>
        <td><strong>🏫 Lab</strong></td>
        <td>: {{ $reminder->laboratory->lab_name }}</td>
    </tr>
    <tr>
        <td><strong>📆 Tanggal Maintenance</strong></td>
        <td>: {{ \Carbon\Carbon::parse($reminder->reminder_date)->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td><strong>🗒️ Deskripsi</strong></td>
        <td>: {{ $reminder->description ?? '-' }}</td>
    </tr>
</table>

<br>

<p>✅ Harap persiapkan dan lakukan pengecekan sesuai jadwal.</p>

<p style="margin-top: 20px;">Terima kasih, <br><strong>Manajemen Laboratorium</strong></p>
