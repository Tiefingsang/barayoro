@extends('layouts.master')

@section('title', 'Barayoro - Solution SaaS de gestion d\'entreprise | Par Masadigitale')
@section('description', $settings['meta_description'] ?? 'Barayoro est la solution SaaS tout-en-un pour gérer vos ventes...')

@section('content')
    @include('components.hero', [
        'settings' => $settings,
        'trustedCompanies' => $trustedCompanies,
        'totalCompaniesCount' => $totalCompaniesCount
    ])
    
    @include('components.clients', ['partnerLogos' => $partnerLogos])
    
    @include('components.features', [
        'features' => $features,
        'featuresTitle' => $featuresTitle ?? 'Tout ce dont votre entreprise a besoin',
        'featuresSubtitle' => $featuresSubtitle ?? 'Une solution complète pour gérer l\'ensemble de vos activités professionnelles'
    ])
    
    @include('components.jobs-section', [
        'jobOffers' => $jobOffers,
        'companyTypes' => $companyTypes
    ])
    
    @include('components.pricing', ['pricingPlans' => $pricingPlans])
    
    @include('components.contact-section', ['totalCompaniesCount' => $totalCompaniesCount])
@endsection