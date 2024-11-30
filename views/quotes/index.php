<?php if (isset($_SESSION['user_id'])): ?>
<div class="mb-8">
    <a href="/quote/create" 
       class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Share a New Quote
    </a>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($quotes as $quote): ?>
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <a href="/quote/view?id=<?php echo htmlspecialchars($quote['id']); ?>">
            <blockquote class="text-lg mb-4">
                "<?php echo htmlspecialchars($quote['quote_text']); ?>"
            </blockquote>
            <div class="text-gray-600">
                <p class="font-semibold"><?php echo htmlspecialchars($quote['artist']); ?></p>
                <p class="text-sm"><?php echo htmlspecialchars($quote['song_title']); ?></p>
                <p class="text-xs mt-2 text-gray-500">
                    Posted <?php echo date('F j, Y', strtotime($quote['created_at'])); ?>
                    <?php if (isset($quote['username'])): ?>
                        by <?php echo htmlspecialchars($quote['username']); ?>
                    <?php endif; ?>
                </p>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
