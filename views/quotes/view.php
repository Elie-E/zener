<?php
if (!$quote) {
    header('Location: /');
    exit;
}
?>

<div class="max-w-4xl mx-auto">
    <!-- Quote Card -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <blockquote class="text-2xl font-serif mb-6 text-gray-800">
            "<?php echo htmlspecialchars($quote['quote_text']); ?>"
        </blockquote>
        <div class="flex justify-between items-center text-gray-600">
            <div>
                <p class="font-medium"><?php echo htmlspecialchars($quote['song_title']); ?></p>
                <p class="text-sm">by <?php echo htmlspecialchars($quote['artist']); ?></p>
                <p class="text-sm mt-2">Shared by <?php echo htmlspecialchars($quote['username']); ?></p>
            </div>
            <div class="flex items-center">
                <button 
                    onclick="toggleLike(<?php echo $quote['id']; ?>)"
                    class="flex items-center space-x-1 px-4 py-2 rounded-full <?php echo $quote['user_has_liked'] ? 'text-red-600' : 'text-gray-600 hover:text-red-600'; ?> transition-colors duration-200"
                    id="likeButton<?php echo $quote['id']; ?>"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    <span id="likeCount<?php echo $quote['id']; ?>"><?php echo $quote['like_count']; ?></span>
                </button>
            </div>
        </div>
        <div class="text-gray-600">
            <p class="text-sm mt-2">Posted on <?php echo date('F j, Y', strtotime($quote['created_at'])); ?></p>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-xl font-bold mb-6">Comments</h2>

        <!-- Comment Form -->
        <form action="/comment/store" method="POST" class="mb-8">
            <input type="hidden" name="quote_id" value="<?php echo htmlspecialchars($quote['id']); ?>">
            
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="mb-4">
                <label for="author_name" class="block text-gray-700 text-sm font-bold mb-2">Your Name</label>
                <input type="text" name="author_name" id="author_name" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="comment_text" class="block text-gray-700 text-sm font-bold mb-2">Your Comment</label>
                <textarea name="comment_text" id="comment_text" rows="4" required
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Post Comment
            </button>
        </form>

        <!-- Comments List -->
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <?php if (!$comment['parent_id']): // Only show top-level comments first ?>
                <div class="border-t border-gray-200 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">
                                <?php 
                                if ($comment['username']) {
                                    echo htmlspecialchars($comment['username']);
                                } elseif ($comment['author_name']) {
                                    echo htmlspecialchars($comment['author_name']);
                                } else {
                                    echo 'Anonymous';
                                }
                                ?>
                            </p>
                            <p class="text-sm text-gray-500">
                                <?php echo date('F j, Y g:i a', strtotime($comment['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <p class="mt-2 text-gray-700">
                        <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                    </p>

                    <!-- Reply Button -->
                    <button onclick="showReplyForm(<?php echo $comment['id']; ?>)"
                            class="mt-2 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">
                        Reply
                    </button>

                    <!-- Reply Form (hidden by default) -->
                    <form id="replyForm<?php echo $comment['id']; ?>" 
                          action="/comment/store" 
                          method="POST" 
                          class="mt-4 ml-8 hidden">
                        <input type="hidden" name="quote_id" value="<?php echo htmlspecialchars($quote['id']); ?>">
                        <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                        
                        <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Your Name</label>
                            <input type="text" name="author_name" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Your Reply</label>
                            <textarea name="comment_text" rows="3" required
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline">
                                Post Reply
                            </button>
                            <button type="button"
                                    onclick="hideReplyForm(<?php echo $comment['id']; ?>)"
                                    class="bg-gray-500 hover:bg-gray-700 text-white text-sm font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <!-- Replies -->
                    <?php foreach ($comments as $reply): ?>
                        <?php if ($reply['parent_id'] === $comment['id']): ?>
                        <div class="ml-8 mt-4 border-l-2 border-gray-200 pl-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold">
                                        <?php 
                                        if ($reply['username']) {
                                            echo htmlspecialchars($reply['username']);
                                        } elseif ($reply['author_name']) {
                                            echo htmlspecialchars($reply['author_name']);
                                        } else {
                                            echo 'Anonymous';
                                        }
                                        ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <?php echo date('F j, Y g:i a', strtotime($reply['created_at'])); ?>
                                    </p>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700">
                                <?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-600 italic">No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>
</div>

<script>
function showReplyForm(commentId) {
    document.getElementById('replyForm' + commentId).classList.remove('hidden');
}

function hideReplyForm(commentId) {
    document.getElementById('replyForm' + commentId).classList.add('hidden');
}

function toggleLike(quoteId) {
    if (!<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
        window.location.href = '/login';
        return;
    }

    fetch('/like/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'quote_id=' + quoteId
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        
        const likeButton = document.getElementById('likeButton' + quoteId);
        const likeCount = document.getElementById('likeCount' + quoteId);
        
        if (data.isLiked) {
            likeButton.classList.remove('text-gray-600', 'hover:text-red-600');
            likeButton.classList.add('text-red-600');
        } else {
            likeButton.classList.remove('text-red-600');
            likeButton.classList.add('text-gray-600', 'hover:text-red-600');
        }
        
        likeCount.textContent = data.likeCount;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your like');
    });
}
</script>
