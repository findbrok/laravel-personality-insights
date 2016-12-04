<?php

use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use FindBrok\PersonalityInsights\Facades\PersonalityInsightsFacade;
use FindBrok\PersonalityInsights\PersonalityInsights as PersonalityInsightsConcrete;

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
        $this->contentItems = json_decode(file_get_contents(__DIR__ . '/Mocks/content-items.json'), true)['contentItems'];
        // Mock Response Body
        $this->jsonResponse = file_get_contents(__DIR__ . '/Mocks/profile-response.json');

        // Set return value of post method
        $this->bridge->method('post')->withAnyParameters()->willReturn(new Response(200, [], $this->jsonResponse));
        // Override Bridge in IOC
        $this->app->instance('PersonalityInsightsBridge', $this->bridge);
    }

    /**
     * Test to see if we have Intellect insight in the Profile.
     *
     * @return bool
     */
    public function testCheckIfIntellectInsightsWithTrueAsAnswer()
    {
        // Get Insights
        $insights = $this->app->make('PersonalityInsights')->addContentItems($this->contentItems);
        // We have intellect so its true
        $this->assertTrue($insights->hasInsight('Intellect', $insights->collectTree()));
    }

    /**
     * Test to confirm that analysis is strong.
     *
     * @return void
     */
    public function testConfirmThatAnalysisIsVeryStrong()
    {
        // Analysis is strong
        $this->assertTrue($this->app->make('PersonalityInsights')
                                    ->addContentItems($this->contentItems)
                                    ->isAnalysisStrong());
    }

    /**
     * Test the Get Profile Function.
     *
     * @return void
     */
    public function testGetFullProfileWithExpectedProfile()
    {
        // Get Full Profile
        $profile = $this->app->make('PersonalityInsights')->addContentItems($this->contentItems)->getFullProfile();
        // See Full Profile
        $this->assertJsonStringEqualsJsonString($this->jsonResponse, $profile->toJson());
    }

    /**
     * Test that we get the expected percentage for Intellect.
     *
     * @return void
     */
    public function testGetIntellectInsightsWithExpectedPercentage()
    {
        // Get Intellect
        $intellect = $this->app->make('PersonalityInsights')
                               ->addContentItems($this->contentItems)
                               ->getInsight('Intellect');
        // We see the expected percentage
        $this->assertEquals(41.7, $intellect->calculatePercentage());
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
        $this->app->make('PersonalityInsights')->addContentItems([
            'id'     => 'foo',
            'source' => 'bar',
        ]);
    }

    /**
     * Test that we are able to Create the Personality Insights object
     * from IOC.
     *
     * @return void
     */
    public function testPersonalityInsightsCanBeConstructed()
    {
        $insights = $this->app->make('PersonalityInsights');
        $this->assertInstanceOf(PersonalityInsightsConcrete::class, $insights);
    }

    /**
     * Test that the facade works as expected.
     *
     * @return void
     */
    public function testPersonalityInsightsFacade()
    {
        $insights = PersonalityInsightsFacade::addContentItems($this->contentItems);
        $this->assertInstanceOf(
            PersonalityInsightsConcrete::class,
            $insights
        );
        $profile = $insights->getFullProfile();
        $this->assertJsonStringEqualsJsonString($this->jsonResponse, $profile->toJson());
    }
}
