<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Administrasi Alpha</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; }
        .title { font-size: 20px; font-weight: bold; margin-bottom: 8px; }
        .meta { color: #6b7280; font-size: 12px; margin-bottom: 18px; }
        .section { margin-top: 16px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">Administrasi Alpha</div>
    <div class="meta">Dibuat pada {{ $generated_at }}</div>

    <div class="section">
        <div class="label">Nama Pegawai</div>
        <div>{{ $user->name }}</div>
    </div>

    <div class="section">
        <div class="label">Email</div>
        <div>{{ $user->email }}</div>
    </div>

    <div class="section">
        <div class="label">Role</div>
        <div>{{ $user->role?->label() ?? ucfirst($user->role?->value ?? '') }}</div>
    </div>
</body>
</html>
