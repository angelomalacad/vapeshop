<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Modal View')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: transparent !important;
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
        }

        /* FORCE CLICKABILITY */
        .modal-content,
        .modal-body,
        .modal-body-custom,
        button {
            pointer-events: auto !important;
            cursor: pointer !important;
            z-index: 99999 !important;
        }

        .modal-backdrop {
            display: none !important;
        }

        .modal {
            z-index: 99999 !important;
        }
    </style>
</head>

<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ ADD THIS - This is where @section('scripts') will be injected -->
        @yield('scripts')
    </body>

    </html>
