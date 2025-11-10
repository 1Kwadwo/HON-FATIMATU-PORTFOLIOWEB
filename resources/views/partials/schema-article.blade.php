{{-- Article Schema for News Articles --}}
@php
$articleSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $article->title,
    'description' => $article->excerpt,
    'image' => $article->featured_image_url,
    'datePublished' => $article->published_at?->toIso8601String(),
    'dateModified' => $article->updated_at->toIso8601String(),
    'author' => [
        '@type' => 'Person',
        'name' => $article->author?->name ?? 'Hon. Fatimatu Abubakar'
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => config('app.name'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png')
        ]
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => url()->current()
    ]
];
@endphp
<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
