{{-- Person Schema for Homepage --}}
@php
$personSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => 'Hon. Fatimatu Abubakar',
    'jobTitle' => 'Minister',
    'url' => url('/'),
    'image' => $personImage ?? asset('images/profile.jpg'),
    'description' => $personDescription ?? 'Dedicated leader committed to community development, education, and social impact.',
    'sameAs' => [
        $facebookUrl ?? '#',
        $twitterUrl ?? '#',
        $linkedinUrl ?? '#'
    ]
];
@endphp
<script type="application/ld+json">
{!! json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
