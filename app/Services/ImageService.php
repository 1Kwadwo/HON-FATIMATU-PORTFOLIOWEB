<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;

/**
 * ImageService - Handles image upload, optimization, and responsive image generation
 * 
 * Features:
 * - Automatic image optimization with 80% JPEG quality (configurable)
 * - Multiple size generation (thumbnail, medium, large) for responsive images
 * - Lazy loading support through proper image sizing
 * - Automatic cleanup of all generated sizes on deletion
 * - Responsive image data generation with srcset attributes
 * 
 * Usage:
 * - Upload with sizes: $imageService->upload($file, 'gallery', ['generateSizes' => true])
 * - Get responsive data: $imageService->getResponsiveImageData($path, 'gallery')
 * - Delete all sizes: $imageService->delete($path)
 */
class ImageService
{
    /**
     * Image size configurations for responsive images.
     * These sizes are optimized for different screen sizes and use cases:
     * - thumbnail: 300x300 - For grid previews and thumbnails
     * - medium: 800x600 - For tablet and small desktop screens
     * - large: 1200x900 - For large desktop screens and high-DPI displays
     */
    protected array $imageSizes = [
        'thumbnail' => ['width' => 300, 'height' => 300],
        'medium' => ['width' => 800, 'height' => 600],
        'large' => ['width' => 1200, 'height' => 900],
    ];

    /**
     * Upload an image file to storage with validation and unique filename generation.
     * Optionally generates multiple sizes for responsive images.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The storage directory (e.g., 'gallery', 'news')
     * @param array $options Optional settings (quality, format, generateSizes, etc.)
     * @return string The stored file path
     */
    public function upload(UploadedFile $file, string $directory, array $options = []): string
    {
        // Validate file
        $this->validateImage($file);

        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);

        // Determine subdirectory structure
        $subdirectory = $options['subdirectory'] ?? 'original';
        $fullPath = "{$directory}/{$subdirectory}";

        // Process and optimize image
        $image = Image::read($file->getRealPath());

        // Apply quality settings (default 80% for optimal balance)
        $quality = $options['quality'] ?? 80;

        // Encode with specified quality
        $encoded = $image->encode(new AutoEncoder(quality: $quality));

        // Store the original file
        $storagePath = "{$fullPath}/{$filename}";
        Storage::disk('public')->put($storagePath, (string) $encoded);

        // Generate multiple sizes if requested
        if ($options['generateSizes'] ?? false) {
            $this->generateMultipleSizes($storagePath, $directory, $filename, $quality);
        }

        return $storagePath;
    }

    /**
     * Generate multiple image sizes (thumbnail, medium, large) for responsive images.
     *
     * @param string $originalPath The original image path
     * @param string $directory The base directory
     * @param string $filename The filename
     * @param int $quality JPEG quality (default: 80)
     * @return array Array of generated image paths
     */
    public function generateMultipleSizes(string $originalPath, string $directory, string $filename, int $quality = 80): array
    {
        $generatedPaths = [];

        foreach ($this->imageSizes as $sizeName => $dimensions) {
            try {
                $sizePath = $this->generateSize(
                    $originalPath,
                    $directory,
                    $filename,
                    $sizeName,
                    $dimensions['width'],
                    $dimensions['height'],
                    $quality
                );
                $generatedPaths[$sizeName] = $sizePath;
            } catch (\Exception $e) {
                // Log error but continue with other sizes
                \Log::warning("Failed to generate {$sizeName} size for {$originalPath}: " . $e->getMessage());
            }
        }

        return $generatedPaths;
    }

    /**
     * Generate a specific image size.
     *
     * @param string $originalPath The original image path
     * @param string $directory The base directory
     * @param string $filename The filename
     * @param string $sizeName The size name (thumbnail, medium, large)
     * @param int $width Target width
     * @param int $height Target height
     * @param int $quality JPEG quality
     * @return string The path to the generated image
     */
    protected function generateSize(
        string $originalPath,
        string $directory,
        string $filename,
        string $sizeName,
        int $width,
        int $height,
        int $quality = 80
    ): string {
        if (!Storage::disk('public')->exists($originalPath)) {
            throw new \Exception("Original image not found: {$originalPath}");
        }

        // Read the image
        $fullPath = Storage::disk('public')->path($originalPath);
        $image = Image::read($fullPath);

        // Resize maintaining aspect ratio
        $image->scale(width: $width, height: $height);

        // Determine size-specific path
        $sizePath = "{$directory}/{$sizeName}/{$filename}";

        // Encode and save
        $encoded = $image->encode(new AutoEncoder(quality: $quality));
        Storage::disk('public')->put($sizePath, (string) $encoded);

        return $sizePath;
    }

    /**
     * Get responsive image data with srcset for different screen sizes.
     *
     * @param string $imagePath The original or base image path
     * @param string $directory The base directory
     * @return array Array with 'src', 'srcset', and 'sizes' attributes
     */
    public function getResponsiveImageData(string $imagePath, string $directory): array
    {
        $pathInfo = pathinfo($imagePath);
        $filename = $pathInfo['basename'];

        // Build srcset with different sizes
        $srcset = [];
        
        // Check if sized versions exist, otherwise use original
        foreach ($this->imageSizes as $sizeName => $dimensions) {
            $sizePath = "{$directory}/{$sizeName}/{$filename}";
            if (Storage::disk('public')->exists($sizePath)) {
                $url = Storage::disk('public')->url($sizePath);
                $srcset[] = "{$url} {$dimensions['width']}w";
            }
        }

        // Fallback to original if no sizes exist
        $src = Storage::disk('public')->url($imagePath);

        return [
            'src' => $src,
            'srcset' => !empty($srcset) ? implode(', ', $srcset) : null,
            'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw',
        ];
    }

    /**
     * Delete an image file from storage along with all generated sizes.
     *
     * @param string $path The file path relative to storage/app/public
     * @return bool True if deleted successfully, false otherwise
     */
    public function delete(string $path): bool
    {
        $deleted = false;

        // Delete the original file
        if (Storage::disk('public')->exists($path)) {
            $deleted = Storage::disk('public')->delete($path);
        }

        // Delete all generated sizes
        $pathInfo = pathinfo($path);
        $filename = $pathInfo['basename'];
        $directory = dirname(dirname($path)); // Go up two levels from original/filename

        foreach (array_keys($this->imageSizes) as $sizeName) {
            $sizePath = "{$directory}/{$sizeName}/{$filename}";
            if (Storage::disk('public')->exists($sizePath)) {
                Storage::disk('public')->delete($sizePath);
            }
        }

        return $deleted;
    }

    /**
     * Resize an image to specified dimensions.
     *
     * @param string $path The file path relative to storage/app/public
     * @param int $width Target width
     * @param int $height Target height
     * @param array $options Optional settings (maintain aspect ratio, etc.)
     * @return string The path to the resized image
     */
    public function resize(string $path, int $width, int $height, array $options = []): string
    {
        if (!Storage::disk('public')->exists($path)) {
            throw new \Exception("Image not found: {$path}");
        }

        // Read the image
        $fullPath = Storage::disk('public')->path($path);
        $image = Image::read($fullPath);

        // Resize with aspect ratio preservation by default
        $maintainAspectRatio = $options['maintain_aspect_ratio'] ?? true;

        if ($maintainAspectRatio) {
            $image->scale(width: $width, height: $height);
        } else {
            $image->resize($width, $height);
        }

        // Generate new filename for resized version
        $pathInfo = pathinfo($path);
        $resizedFilename = $pathInfo['filename'] . "_{$width}x{$height}." . $pathInfo['extension'];
        $resizedPath = $pathInfo['dirname'] . '/' . $resizedFilename;

        // Encode and save
        $quality = $options['quality'] ?? 80;
        $encoded = $image->encode(new AutoEncoder(quality: $quality));
        Storage::disk('public')->put($resizedPath, (string) $encoded);

        return $resizedPath;
    }

    /**
     * Generate a thumbnail for gallery previews.
     *
     * @param string $path The original image path
     * @param int $width Thumbnail width (default: 300)
     * @param int $height Thumbnail height (default: 300)
     * @return string The path to the thumbnail
     */
    public function generateThumbnail(string $path, int $width = 300, int $height = 300): string
    {
        if (!Storage::disk('public')->exists($path)) {
            throw new \Exception("Image not found: {$path}");
        }

        // Read the image
        $fullPath = Storage::disk('public')->path($path);
        $image = Image::read($fullPath);

        // Create thumbnail with cover (crop to fit)
        $image->cover($width, $height);

        // Determine thumbnail path
        $pathInfo = pathinfo($path);
        $directory = dirname($path);
        
        // Replace 'original' with 'thumbnails' in path
        $thumbnailDirectory = str_replace('/original', '/thumbnails', $directory);
        $thumbnailPath = $thumbnailDirectory . '/' . $pathInfo['basename'];

        // Encode and save
        $encoded = $image->encode(new AutoEncoder(quality: 80));
        Storage::disk('public')->put($thumbnailPath, (string) $encoded);

        return $thumbnailPath;
    }

    /**
     * Validate uploaded image file.
     * Security: Whitelist MIME types and enforce 5MB max size.
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    protected function validateImage(UploadedFile $file): void
    {
        // Check file size (5MB max) - Security requirement
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($file->getSize() > $maxSize) {
            throw new \Exception('Image file size exceeds 5MB limit.');
        }

        // Whitelist allowed MIME types - Security requirement
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $fileMimeType = $file->getMimeType();
        
        if (!in_array($fileMimeType, $allowedMimes, true)) {
            throw new \Exception('Invalid image type. Allowed types: JPEG, PNG, WebP.');
        }
        
        // Additional security: Check file extension matches MIME type
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $fileExtension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($fileExtension, $allowedExtensions, true)) {
            throw new \Exception('Invalid file extension. Allowed extensions: jpg, jpeg, png, webp.');
        }
        
        // Verify the file is actually an image by checking if we can read it
        try {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo === false) {
                throw new \Exception('File is not a valid image.');
            }
        } catch (\Exception $e) {
            throw new \Exception('File validation failed: Unable to verify image integrity.');
        }
    }

    /**
     * Generate a unique filename with timestamp.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = substr(md5(uniqid()), 0, 8);
        
        return "{$timestamp}_{$random}.{$extension}";
    }
}
