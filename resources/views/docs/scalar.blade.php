<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>API Docs - Scalar</title>
    <script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
    <style>
        body {
            margin: 0;
            height: 100vh;
            width: 100%;
        }

        #scalar {
            height: 100%;
            width: 100%;
        }

        :root {
            --scalar-radius: 0.5rem;
            --scalar-radius-lg: 0.75rem;
            --scalar-radius-xl: 1rem;
        }

        .sidebar-group-item {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div id="scalar"></div>
    <script>
        Scalar.createApiReference('#scalar', {
            url: '/docs/api/spec',
            layout: "classic",
            theme: "fastify",
            slug: "api-1",
            showToolbar: "never"
        });
    </script>
</body>

</html>