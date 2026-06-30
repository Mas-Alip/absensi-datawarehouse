<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Administrasi Alpha</title>
    <style>
        @page { size: A4; margin: 16mm; }
        body { font-family: Arial, sans-serif; color: #111827; font-size: 12px; line-height: 1.5; }
        .title { font-size: 20px; font-weight: bold; margin-bottom: 6px; }
        .meta { color: #6b7280; font-size: 11px; margin-bottom: 16px; }
        .section { margin-top: 12px; }
        .label { font-weight: bold; margin-bottom: 2px; }
        .box { border: 1px solid #d1d5db; padding: 8px; border-radius: 4px; background: #fcfcfd; }
        .signature-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .signature-table td { width: 50%; padding: 8px; vertical-align: top; }
        .signature-box { border: 1px solid #d1d5db; height: 90px; margin-top: 8px; }
        .footer { margin-top: 36px; font-size: 9px; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="title">Administrasi Alpha</div>
    <div class="meta">Dibuat pada {{ $generated_at }}</div>

    <div class="section">
        <div class="label">Nama Pegawai</div>
        <div class="box">{{ $user->name }}</div>
    </div>

    <div class="section">
        <div class="label">Email</div>
        <div class="box">{{ $user->email }}</div>
    </div>

    <div class="section">
        <div class="label">Role</div>
        <div class="box">{{ $user->role?->label() ?? ucfirst($user->role?->value ?? '') }}</div>
    </div>

    <div class="section">
        <div class="label">Keterangan</div>
        <div class="box" style="white-space: pre-wrap;">{{ $keterangan }}</div>
    </div>

    <div class="section" style="margin-top: 20px;">{{ $printed_date }}</div>

    <table class="signature-table">
        <tr>
            <td>
                <strong>Pegawai</strong>
                <div class="signature-box"></div>
                <div style="margin-top: 6px;">Nama Pegawai</div>
            </td>
            <td>
                <strong>Manager</strong>
                <div class="signature-box"></div>
                <div style="margin-top: 6px;">Manager</div>
            </td>
        </tr>
    </table>

    <div class="footer">Dokumen ini dihasilkan oleh Sistem HRIS Data Warehouse Absensi Pegawai dan digunakan sebagai administrasi internal perusahaan.</div>
</body>
</html>
