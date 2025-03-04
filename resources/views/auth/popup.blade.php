<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación</title>
    <script>
        window.onload = function() {
            if (window.opener) {

                @isset($errorMessage)
                    window.opener.postMessage({ error: "{{ $errorMessage }}", success: false }, "http://localhost:5175");
                    window.opener.postMessage({ error: "{{ $errorMessage }}", success: false }, "http://localhost:5174");
                    window.opener.postMessage({ error: "{{ $errorMessage }}", success: false }, "http://localhost:5173");
                    window.opener.postMessage({ error: "{{ $errorMessage }}", success: false }, "http://conectasalud.batoi.es");
                    window.close();
                @endisset
                @isset($token)
                    window.opener.postMessage({ token: "{{ $token }}", user: @json($user), success: true }, "http://localhost:5175");
                    window.opener.postMessage({ token: "{{ $token }}", user: @json($user), success: true }, "http://localhost:5174");
                    window.opener.postMessage({ token: "{{ $token }}", user: @json($user), success: true }, "http://localhost:5173");
                    window.opener.postMessage({ token: "{{ $token }}", user: @json($user), success: true }, "http://conectasalud.batoi.es");
                    window.close();
                @endisset
            }
        };
    </script>
</head>
<body>
    <p>Autenticando...</p>
</body>
</html>
