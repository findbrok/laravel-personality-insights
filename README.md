<p align="center">
	<img src="https://raw.githubusercontent.com/findbrok/art-work/master/packages/laravel-personality-insights/laravel-personality-insights.png">
</p>
<h2 align="center">
	Laravel Personality Insights
</h2>

<p align="center">
    <a href="https://packagist.org/packages/findbrok/laravel-personality-insights"><img src="https://poser.pugx.org/findbrok/laravel-personality-insights/v/stable" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/findbrok/laravel-personality-insights"><img src="https://poser.pugx.org/findbrok/laravel-personality-insights/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://travis-ci.org/findbrok/laravel-personality-insights"><img src="https://travis-ci.org/findbrok/laravel-personality-insights.svg?branch=1.1" alt="Build Status"></a>
    <a href="https://styleci.io/repos/59461266"><img src="https://styleci.io/repos/59461266/shield?branch=1.1" alt="StyleCI"></a>
    <a href="https://packagist.org/packages/findbrok/laravel-personality-insights"><img src="https://poser.pugx.org/findbrok/laravel-personality-insights/license" alt="License"></a>
    <a href="https://packagist.org/packages/findbrok/laravel-personality-insights"><img src="https://poser.pugx.org/findbrok/laravel-personality-insights/downloads" alt="Total Downloads"></a>
    <a href="https://insight.sensiolabs.com/projects/06dd30e0-4183-4d4f-9cf0-d5045624fccf" alt="medal"><img src="https://insight.sensiolabs.com/projects/06dd30e0-4183-4d4f-9cf0-d5045624fccf/mini.png"></a>
</p> 

## Introduction
Laravel Personality Insights, provides a simple and easy to use wrapper 
around [IBM Watson Personality Insights API](http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/personality-insights.html)

## License
Laravel Personality Insights is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### How it works
Personality Insights extracts and analyzes a spectrum of personality attributes to help discover actionable insights about 
people and entities, and in turn guides end users to highly personalized interactions. The service outputs personality 
characteristics that are divided into three dimensions: the Big 5, Values, and Needs. While some services are 
contextually specific depending on the domain model and content, Personality Insights only requires a 
minimum of 3500+ words of any text.

### Intended Use
The Personality Insights service lends itself to an almost limitless number of potential applications. Businesses 
can use the detailed personality portraits of individual customers for finer-grained customer segmentation and 
better-quality lead generation. This data enables them to design marketing, provide product recommendations, 
and deliver customer care that is more personal and relevant. Personality Insights can also be used to 
help recruiters or university admissions match candidates to companies or universities.

### Installation
Install the package through composer:

```bash
$ composer require findbrok/laravel-personality-insights
```

Add the Service Provider to your providers array in ```config/app.php```, 
see [Registering Providers](https://laravel.com/docs/master/providers#registering-providers)

```php
'providers' => [
    // Other Service Providers

    FindBrok\PersonalityInsights\InsightsServiceProvider::class,
],
```

### Configuration
Once installed you can now publish your config file and set your correct configuration for using the package.

```bash
$ php artisan vendor:publish --provider="FindBrok\PersonalityInsights\InsightsServiceProvider" --tag="config"
```

This will create a file ```config/personality-insights.php``` , for information on how to set values present in this file see [Configuration Before Usage](https://github.com/findbrok/laravel-personality-insights/wiki/Configuration-Before-Usage-(1.1))

### Credits
Big Thanks to all developers who worked hard to create something amazing!
 
### Creator
[![Percy Mamedy](https://img.shields.io/badge/Author-Percy%20Mamedy-orange.svg)](https://twitter.com/PercyMamedy)

Twitter: [@PercyMamedy](https://twitter.com/PercyMamedy)
<br/>
GitHub: [percymamedy](https://github.com/percymamedy)
