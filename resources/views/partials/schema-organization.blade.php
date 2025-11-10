{{-- Organization Schema for Footer --}}
@php
$orgSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => config('app.name'),
    'url' => url('/'),
    'logo' => asset('images/logo.png'),
    'description' => 'Official website of Hon. Fatimatu Abubakar',
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'contactType' => 'General Inquiries',
        'url' => route('contact')
    ],
    'sameAs' => [
        $facebookUrl ?? '#',
        $twitterUrl ?? '#',
        $linkedinUrl ?? '#'
    ]
];
@endphp
<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
