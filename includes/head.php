<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? SITE_NAME) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? SITE_TAGLINE) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $basePath ?? '' ?>images/icons/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $basePath ?? '' ?>images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $basePath ?? '' ?>images/icons/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $basePath ?? '' ?>images/icons/apple-touch-icon.png">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? SITE_NAME) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription ?? SITE_TAGLINE) ?>">
    <meta property="og:image" content="<?= $basePath ?? '' ?>images/icons/android-chrome-512x512.png">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_CA">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#FAA416',
                            light: '#FDB844',
                            dark: '#D88A0A',
                        },
                        dark: {
                            DEFAULT: '#0A0A0A',
                            50: '#1A1A1A',
                            100: '#141414',
                            200: '#1E1E1E',
                            300: '#2A2A2A',
                            400: '#3A3A3A',
                        },
                    },
                    fontFamily: {
                        heading: ['Syne', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        '4xl': '2rem',
                    },
                },
            },
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $basePath ?? '' ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= $basePath ?? '' ?>assets/css/animations.css">
    <link rel="stylesheet" href="<?= $basePath ?? '' ?>assets/css/components.css">
    <link rel="stylesheet" href="<?= $basePath ?? '' ?>assets/css/utilities.css">
</head>
