# Laravel 5 Personality Insights

[![StyleCI](https://styleci.io/repos/59461266/shield?style=flat)](https://styleci.io/repos/59461266)
[![Build Status](https://travis-ci.org/findbrok/laravel-personality-insights.svg?branch=master)](https://travis-ci.org/findbrok/laravel-personality-insights)
[![Latest Stable Version](https://poser.pugx.org/findbrok/laravel-personality-insights/v/stable)](https://packagist.org/packages/findbrok/laravel-personality-insights)
[![Total Downloads](https://poser.pugx.org/findbrok/laravel-personality-insights/downloads)](https://packagist.org/packages/findbrok/laravel-personality-insights)
[![Latest Unstable Version](https://poser.pugx.org/findbrok/laravel-personality-insights/v/unstable)](https://packagist.org/packages/findbrok/laravel-personality-insights) 
[![License](https://poser.pugx.org/findbrok/laravel-personality-insights/license)](https://packagist.org/packages/findbrok/laravel-personality-insights)

A simple Laravel 5 wrapper around [IBM Watson Personality Insights API](http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/personality-insights.html)

## How it works

Personality Insights extracts and analyzes a spectrum of personality attributes to help discover actionable insights about people and entities, and in turn guides end users to highly personalized interactions. The service outputs personality characteristics that are divided into three dimensions: the Big 5, Values, and Needs. While some services are contextually specific depending on the domain model and content, Personality Insights only requires a minimum of 3500+ words of any text.

## Intended Use

The Personality Insights service lends itself to an almost limitless number of potential applications. Businesses can use the detailed personality portraits of individual customers for finer-grained customer segmentation and better-quality lead generation. This data enables them to design marketing, provide product recommendations, and deliver customer care that is more personal and relevant. Personality Insights can also be used to help recruiters or university admissions match candidates to companies or universities.

## Installation

Install the package through composer

```
composer require findbrok/laravel-personality-insights
```

Add the Service Provider to your providers array in ```config/app.php```, see [Registering Providers](https://laravel.com/docs/5.2/providers#registering-providers)

```php
'providers' => [
    // Other Service Providers

    FindBrok\PersonalityInsights\InsightsServiceProvider::class,
],
```

## Configuration

Once installed you can now publish your config file and set your correct configuration for using the package.

```
php artisan vendor:publish --provider="FindBrok\PersonalityInsights\InsightsServiceProvider" --tag="config"
```

This will create a file ```config/personality-insights.php``` , for information on how to set values present in this file see [Configuration Before Usage](https://github.com/findbrok/laravel-personality-insights/wiki/Configuration-Before-Usage)

## Usage

Read the [docs](https://github.com/findbrok/laravel-personality-insights/wiki)

## Credits

[![Percy Mamedy](https://img.shields.io/badge/Author-Percy%20Mamedy-orange.svg)](https://twitter.com/PercyMamedy)

Twitter: [@PercyMamedy](https://twitter.com/PercyMamedy)
<br/>
GitHub: [percymamedy](https://github.com/percymamedy)
