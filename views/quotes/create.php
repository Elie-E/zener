<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold mb-6">Share a New Quote</h2>

        <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <form action="/quote/store" method="POST">
            <div class="mb-4">
                <label for="quote_text" class="block text-gray-700 text-sm font-bold mb-2">Quote Text</label>
                <textarea name="quote_text" id="quote_text" rows="4" required
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                          placeholder="Enter the song lyrics..."><?php echo htmlspecialchars($_POST['quote_text'] ?? ''); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="artist" class="block text-gray-700 text-sm font-bold mb-2">Artist</label>
                <input type="text" name="artist" id="artist" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Artist name"
                       value="<?php echo htmlspecialchars($_POST['artist'] ?? ''); ?>">
            </div>

            <div class="mb-6">
                <label for="song_title" class="block text-gray-700 text-sm font-bold mb-2">Song Title</label>
                <input type="text" name="song_title" id="song_title" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Name of the song"
                       value="<?php echo htmlspecialchars($_POST['song_title'] ?? ''); ?>">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Share Quote
                </button>
                <a href="/" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
