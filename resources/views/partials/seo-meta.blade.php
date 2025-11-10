{{-- SEO Meta Tags --}}
<meta name="description" content="{{ $metaDescription ?? 'Official website of Hon. Fatimatu Abubakar - Leadership, Legacy, and Community Impact' }}" />

{{-- Open Graph Tags --}}
<meta property="og:title" content="{{ $metaTitle ?? $title ?? config('app.name') }}" />
<meta property="og:description" content="{{ $metaDescription ?? 'Official website of Hon. Fatimatu Abubakar - Leadership, Legacy, and Community Impact' }}" />
<meta property="og:type" content="{{ $ogType ?? 'website' }}" />
<meta property="og:url" content="{{ $ogUrl ?? url()->current() }}" />
@if(isset($ogImage))
<meta property="og:image" content="{{ $ogImage }}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
@endif
<meta property="og:site_name" content="{{ config('app.name') }}" />
<meta property="og:locale" content="en_US" />

{{-- Twitter Card Tags --}}
<meta name="twitter:card" content="{{ $twitterCard ?? 'summary_large_image' }}" />
<meta name="twitter:title" content="{{ $metaTitle ?? $title ?? config('app.name') }}" />
<meta name="twitter:description" content="{{ $metaDescription ?? 'Official website of Hon. Fatimatu Abubakar - Leadership, Legacy, and Community Impact' }}" />
@if(isset($ogImage))
<meta name="twitter:image" content="{{ $ogImage }}" />
@endif

{{-- Additional Article Meta Tags --}}
@if(isset($articlePublishedTime))
<meta property="article:published_time" content="{{ $articlePublishedTime }}" />
@endif
@if(isset($articleModifiedTime))
<meta property="article:modified_time" content="{{ $articleModifiedTime }}" />
@endif
@if(isset($articleAuthor))
<meta property="article:author" content="{{ $articleAuthor }}" />
@endif

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}" />
