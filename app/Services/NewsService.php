<?php

namespace App\Services;

use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class NewsService
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Create a new news article with slug generation.
     *
     * @param array $data Article data
     * @param User $author The author creating the article
     * @return NewsArticle
     */
    public function createArticle(array $data, User $author): NewsArticle
    {
        // Generate slug from title if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        } else {
            // Ensure provided slug is unique
            $data['slug'] = $this->generateUniqueSlug($data['slug']);
        }

        // Set author
        $data['author_id'] = $author->id;

        // Handle featured image upload if provided
        if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
            $data['featured_image_path'] = $this->imageService->upload(
                $data['featured_image'],
                'news',
                [
                    'subdirectory' => 'original',
                    'generateSizes' => true,
                    'quality' => 80
                ]
            );
            unset($data['featured_image']);
        }

        // Set published_at if status is published and not already set
        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Create the article
        $article = NewsArticle::create($data);

        return $article;
    }

    /**
     * Update an existing news article with featured image handling.
     *
     * @param NewsArticle $article The article to update
     * @param array $data Updated article data
     * @return NewsArticle
     */
    public function updateArticle(NewsArticle $article, array $data): NewsArticle
    {
        // Update slug if title changed and slug not explicitly provided
        if (isset($data['title']) && $data['title'] !== $article->title && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $article->id);
        } elseif (isset($data['slug']) && $data['slug'] !== $article->slug) {
            // Ensure new slug is unique
            $data['slug'] = $this->generateUniqueSlug($data['slug'], $article->id);
        }

        // Handle featured image upload if provided
        if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
            // Delete old featured image if exists
            if ($article->featured_image_path) {
                $this->imageService->delete($article->featured_image_path);
            }

            // Upload new featured image
            $data['featured_image_path'] = $this->imageService->upload(
                $data['featured_image'],
                'news',
                [
                    'subdirectory' => 'original',
                    'generateSizes' => true,
                    'quality' => 80
                ]
            );
            unset($data['featured_image']);
        }

        // Handle status change to published
        if (isset($data['status']) && $data['status'] === 'published' && $article->status !== 'published') {
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        }

        // Update the article
        $article->update($data);

        return $article->fresh();
    }

    /**
     * Delete an article and remove associated files.
     *
     * @param NewsArticle $article The article to delete
     * @return bool
     */
    public function deleteArticle(NewsArticle $article): bool
    {
        // Delete featured image if exists
        if ($article->featured_image_path) {
            $this->imageService->delete($article->featured_image_path);
        }

        // Delete the article
        return $article->delete();
    }

    /**
     * Remove the featured image from an article.
     *
     * @param NewsArticle $article The article to remove the image from
     * @return NewsArticle
     */
    public function removeFeaturedImage(NewsArticle $article): NewsArticle
    {
        if ($article->featured_image_path) {
            // Delete the image file
            $this->imageService->delete($article->featured_image_path);

            // Update the article
            $article->update(['featured_image_path' => null]);
        }

        return $article->fresh();
    }

    /**
     * Publish an article by setting status and published_at timestamp.
     *
     * @param NewsArticle $article The article to publish
     * @return NewsArticle
     */
    public function publishArticle(NewsArticle $article): NewsArticle
    {
        $article->status = 'published';
        
        // Set published_at to now if not already set
        if (!$article->published_at) {
            $article->published_at = now();
        }

        $article->save();

        return $article->fresh();
    }

    /**
     * Generate a unique slug from a string.
     *
     * @param string $string The string to slugify
     * @param int|null $ignoreId Article ID to ignore when checking uniqueness
     * @return string
     */
    protected function generateUniqueSlug(string $string, ?int $ignoreId = null): string
    {
        $slug = Str::slug($string);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists and append counter if needed
        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists.
     *
     * @param string $slug The slug to check
     * @param int|null $ignoreId Article ID to ignore
     * @return bool
     */
    protected function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $query = NewsArticle::where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
