<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'ChurrasTop') ?></title>
    <link rel="icon" type="image/png" href="/assets/img/churrastop-logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #fff8ef; }
        .hero { background: linear-gradient(120deg, #7b1e13, #d35400); }
        .brand { color: #ffb000; }
        .card { border: 0; box-shadow: 0 0.25rem 1rem #5b24131a; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/"><img class="brand-logo" src="/assets/img/churrastop-logo.png" alt=""> Churras<span class="brand">Top</span></a>
        <span class="navbar-text">Organize. Divirta-se. Compartilhe.</span>
    </div>
</nav>
<main class="container py-4">
