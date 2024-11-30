<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lyric Quotes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="/" class="flex items-center py-4">
                            <span class="font-semibold text-gray-500 text-lg">Lyric Quotes</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="/logout" class="py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="py-2 px-4 text-gray-500 hover:text-gray-700">Login</a>
                        <a href="/register" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <?php echo $content ?? ''; ?>
    </main>

    <footer class="bg-white shadow-lg mt-8">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            &copy; <?php echo date('Y'); ?> Lyric Quotes. All rights reserved.
        </div>
    </footer>
</body>
</html>
