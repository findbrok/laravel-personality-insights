<?php

use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use FindBrok\PersonalityInsights\PersonalityInsights;
use FindBrok\PersonalityInsights\Facades\PersonalityInsightsFacade;

class TestInsights extends TestCase
{
    /**
     * Watson Bridge.
     *
     * @var \FindBrok\WatsonBridge\Bridge
     */
    public $bridge;

    /**
     * Content Items.
     *
     * @var array
     */
    public $contentItems = [];

    /**
     * Json Response mock.
     *
     * @var string
     */
    public $jsonResponse;

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['FindBrok\PersonalityInsights\InsightsServiceProvider'];
    }

    /**
     * Setup Test.
     */
    public function setUp()
    {
        parent::setUp();
        // Mock Watson Bridge
        $this->bridge = $this->getMockBuilder('FindBrok\WatsonBridge\Bridge')
                             ->disableOriginalConstructor()
                             ->setMethods(['post'])
                             ->getMock();
        // Mock Contents
        $this->contentItems = json_decode(file_get_contents(__DIR__.'/Mocks/content-items.json'), true)['contentItems'];
        // Mock Response Body
        $this->jsonResponse = file_get_contents(__DIR__.'/Mocks/profile-response.json');

        // Set return value of post method
        $this->bridge->method('post')->withAnyParameters()->willReturn(new Response(200, [], $this->jsonResponse));
        // Override Bridge in IOC
        $this->app->instance('PIBridge', $this->bridge);
    }

    /**
     * Test to see if we have Intellect insight in the Profile.
     *
     * @return void
     */
    public function testCheckIfIntellectInsightsWithTrueAsAnswer()
    {
        // Get Insights
        /** @var PersonalityInsights $insights */
        $insights = $this->app->make(PersonalityInsights::SERVICE_ID)->addContentItems($this->contentItems);

        // Get Profile.
        $profile = $insights->getFullProfile();

        // We have intellect so its true
        $this->assertTrue($profile->hasFacet('Intellect'));
        $this->assertTrue($profile->hasFacet('facet_intellect'));
    }

    /**
     * Test to see if we have Curiosity need insight is in the Profile.
     *
     * @return void
     */
    public function testCheckIfCuriosityNeedExistsWithTrueAsAnswer()
    {
        // Get Insights
        /** @var PersonalityInsights $insights */
        $insights = $this->app->make(PersonalityInsights::SERVICE_ID)->addContentItems($this->contentItems);

        // Get Profile.
        $profile = $insights->getFullProfile();

        // We have Curiosity so its true
        $this->assertTrue($profile->hasNeed('Curiosity'));
        $this->assertTrue($profile->hasNeed('need_curiosity'));
    }

    /**
     * Test to check if Conservation Value exists on the Profile.
     *
     * @return void
     */
    public function testCheckIfConservationValueExistsWithTrueAsAnswer()
    {
        // Get Insights
        /** @var PersonalityInsights $insights */
        $insights = $this->app->make(PersonalityInsights::SERVICE_ID)->addContentItems($this->contentItems);

        // Get Profile.
        $profile = $insights->getFullProfile();

        // We have Conservation so its true
        $this->assertTrue($profile->hasValue('Conservation'));
        $this->assertTrue($profile->hasValue('value_conservation'));
    }

    /**
     * Test that we can retrieve Behaviors correctly.
     *
     * @return void
     */
    public function testBehaviorFunctionsOfTheProfile()
    {
        // Get Insights
        /** @var PersonalityInsights $insights */
        $insights = $this->app->make(PersonalityInsights::SERVICE_ID)->addContentItems($this->contentItems);

        // Get Profile.
        $profile = $insights->getFullProfile();

        $this->assertEquals('behavior_2200', $profile->findBehaviorsFor(['10:00 pm'])->trait_id);
        $this->assertCount(2, $profile->findBehaviorsFor(['10:00 pm', 'Monday']));
    }

    /**
     * Test our consumption preferences methods works.
     *
     * @return void
     */
    public function testConsumptionPreferencesMethodsOnProfile()
    {
        // Get Insights
        /** @var PersonalityInsights $insights */
        $insights = $this->app->make(PersonalityInsights::SERVICE_ID)->addContentItems($this->contentItems);

        // Get Profile.
        $profile = $insights->getFullProfile();

        // We have Purchasing Preferences category so its true.
        $this->assertTrue($profile->hasConsumptionPreferenceCategory('Purchasing Preferences'));
        $this->assertTrue($profile->hasConsumptionPreferenceCategory('consumption_preferences_shopping'));

        // We have Consumption Preferences so its true.
        $this->assertTrue($profile->hasConsumptionPreference('consumption_preferences_automobile_safety'));
        $this->assertTrue($profile->hasConsumptionPreference('consumption_preferences_clothes_comfort'));
    }

    /**
     * Test to confirm that analysis is strong.
     *
     * @return void
     */
    public function testConfirmThatAnalysisIsVeryStrong()
    {
        // Get Insights
        /** @var PersonalityInsights $insights */
        $insights = $this->app->make(PersonalityInsights::SERVICE_ID)->addContentItems($this->contentItems);

        // Analysis is strong
        $this->assertTrue($insights->isAnalysisStrong());
    }

    /**
     * Test the Get Profile Function.
     *
     * @return void
     */
    public function testGetFullProfileWithExpectedProfile()
    {
        // Get Full Profile
        $profile = $this->app->make(PersonalityInsights::SERVICE_ID)
                             ->addContentItems($this->contentItems)
                             ->getFullProfile();
        // See Full Profile
        $this->assertEquals((new JsonMapper)->map(json_decode($this->jsonResponse),
                                                  new \FindBrok\PersonalityInsights\Models\Profile()), $profile);
    }

    /**
     * Test that we get the expected percentage for Intellect.
     *
     * @return void
     */
    public function testGetIntellectInsightsWithExpectedPercentage()
    {
        // Get Profile
        /** @var \FindBrok\PersonalityInsights\Models\Profile $profile */
        $profile = $this->app->make(PersonalityInsights::SERVICE_ID)
                             ->addContentItems($this->contentItems)
                             ->getFullProfile();

        $intellect = $profile->findFacetByName('Intellect');

        // We see the expected percentage
        $this->assertEquals(87.2, $intellect->calculatePercentage());
    }

    /**
     * Test that we get the expected percentage for Vulnerability.
     *
     * @return void
     */
    public function testGetVulnerabilityInsightsWithExpectedPercentage()
    {
        // Get Profile
        /** @var \FindBrok\PersonalityInsights\Models\Profile $profile */
        $profile = $this->app->make(PersonalityInsights::SERVICE_ID)
                             ->addContentItems($this->contentItems)
                             ->getFullProfile();

        $vulnerability = $profile->findFacetByName('Susceptible to stress');

        // We see the expected percentage
        $this->assertEquals(39.0, $vulnerability->calculatePercentage());
    }

    /**
     * Test that When we do not pass content item param we get an exception.
     *
     * @expectedException \FindBrok\PersonalityInsights\Exceptions\MissingParameterContentItemException
     *
     * @return void
     */
    public function testNoContentPassedToContentItemObjectMissingParameterExceptionThrown()
    {
        // Add content and exception is thrown here
        $this->app->make(PersonalityInsights::SERVICE_ID)->addContentItems(['id' => 'foo', 'source' => 'bar']);
    }

    /**
     * Test that we are able to Create the Personality Insights object
     * from IOC.
     *
     * @return void
     */
    public function testPersonalityInsightsCanBeConstructed()
    {
        $insights = $this->app->make(PersonalityInsights::SERVICE_ID);
        $this->assertInstanceOf(PersonalityInsights::class, $insights);
    }

    /**
     * Test that the facade works as expected.
     *
     * @return void
     */
    public function testPersonalityInsightsFacade()
    {
        $insights = PersonalityInsightsFacade::addContentItems($this->contentItems);
        $this->assertInstanceOf(PersonalityInsights::class, $insights);
        $profile = $insights->getFullProfile();

        // See Full Profile
        $this->assertEquals((new JsonMapper)->map(json_decode($this->jsonResponse),
                                                  new \FindBrok\PersonalityInsights\Models\Profile()), $profile);
    }
}
