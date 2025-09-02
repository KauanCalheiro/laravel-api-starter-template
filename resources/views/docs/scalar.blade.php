<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>API Docs - Scalar</title>
    <script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
    <!-- <script src="https://unpkg.com/@scalar/api-reference@1.34.6/dist/browser/standalone.min.js"></script> -->
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
    </style>
</head>

<body>
    <div id="scalar"></div>
    <script>
        Scalar.createApiReference('#scalar', {
            url: '/docs/scalar/spec'
        });
    </script>
</body>

</html>