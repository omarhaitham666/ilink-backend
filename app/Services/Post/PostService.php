<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostService
{
    /**
     * Create Post
     */
    public function create(array $data, User $user): Post
    {
        return DB::transaction(function () use ($data, $user) {

            $imagePath = null;

            if (!empty($data['image'])) {
                $imagePath = $data['image']->store('posts', 'public');
            }

            return Post::create([
                'user_id' => $user->id,
                'content' => $data['content'] ?? null,
                'image'   => $imagePath,
            ]);
        });
    }

    /**
     * Update Post (IMPORTANT FIXED)
     */
    public function update(Post $post, array $data, User $user): Post
    {
        if ($post->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return DB::transaction(function () use ($post, $data) {

            $oldImage = $post->getRawOriginal('image');
            $imagePath = $oldImage;

            /**
             * حذف الصورة بالكامل
             */
            if (!empty($data['remove_image'])) {

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                $imagePath = null;
            }

            /**
             * رفع صورة جديدة
             */
            if (!empty($data['image'])) {

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                $imagePath = $data['image']->store('posts', 'public');
            }

            /**
             * تحديث البيانات
             */
            $post->update([
                'content' => $data['content'] ?? $post->content,
                'image'   => $imagePath,
            ]);

            return $post->fresh();
        });
    }

    /**
     * Delete Post (with image cleanup)
     */
    public function delete(Post $post, User $user): bool
    {
        if ($post->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return DB::transaction(function () use ($post) {

            $oldImage = $post->getRawOriginal('image');

            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $post->delete();

            return true;
        });
    }
}