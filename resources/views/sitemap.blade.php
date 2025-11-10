{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Static Pages --}}
    @foreach($pages as $page)
    <url>
        <loc>{{ url($page['url']) }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach

    {{-- News Articles --}}
    @foreach($articles as $article)
    <url>
        <loc>{{ route('news.show', $article->slug) }}</loc>
        <lastmod>{{ $article->updated_at->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Initiatives --}}
    @foreach($initiatives as $initiative)
    <url>
        <loc>{{ route('initiatives.show', $initiative->slug) }}</loc>
        <lastmod>{{ $initiative->updated_at->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
