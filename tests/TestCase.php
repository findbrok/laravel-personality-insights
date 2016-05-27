<?php

use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase as TestBenchTestCase;

/**
 * Class TestCase
 */
class TestCase extends TestBenchTestCase
{
    /**
     * Content Items
     *
     * @var array
     */
    public $contentItems = [];

    /**
     * Json Response mock
     *
     * @var string
     */
    public $jsonResponse;

    /**
     * Watson Bridge
     *
     * @var \FindBrok\WatsonBridge\Bridge
     */
    public $bridge;

    /**
     * Setup Test
     */
    public function setUp()
    {
        parent::setUp();
        //Mock Watson Bridge
        $this->bridge = $this->getMockBuilder('FindBrok\WatsonBridge\Bridge')
                             ->disableOriginalConstructor()
                             ->setMethods(['post'])
                             ->getMock();
        //Mock Contents
        $this->contentItems = json_decode(file_get_contents(__DIR__.'/Mocks/content-items.json'), true)['contentItems'];
        //Mock Response Body
        $this->jsonResponse = file_get_contents(__DIR__.'/Mocks/profile-response.json');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['FindBrok\PersonalityInsights\InsightsServiceProvider'];
    }

    /**
     * Test the Get Profile Function
     *
     * @return void
     */
    public function testGetFullProfileWithExpectedProfile()
    {
        //Set return value of post method
        $this->bridge->method('post')->withAnyParameters()->willReturn(new Response(200, [], $this->jsonResponse));
        //Override Bridge in IOC
        $this->app->instance('PersonalityInsightsBridge', $this->bridge);

        //Get Full Profile
        $profile = app()->make('PersonalityInsights')->addContentItems($this->contentItems)->getFullProfile();
        //See Full Profile
        $this->assertJsonStringEqualsJsonString($this->jsonResponse, $profile->toJson());
    }

    /**
     * Test to see if we have Intellect insight in the Profile
     *
     * @return bool
     */
    public function testCheckIfIntellectInsightsWithTrueAsAnswer()
    {
        //Set return value of post method
        $this->bridge->method('post')->withAnyParameters()->willReturn(new Response(200, [], $this->jsonResponse));
        //Override Bridge in IOC
        $this->app->instance('PersonalityInsightsBridge', $this->bridge);

        //Get Insights
        $insights = app()->make('PersonalityInsights')->addContentItems($this->contentItems);

        //We have intellect so its true
        $this->assertTrue($insights->hasInsight('Intellect', $insights->collectTree()));
    }

    /**
     * Test that we get the expected percentage for Intellect
     *
     * @return void
     */
    public function testGetIntellectInsightsWithExpectedPercentage()
    {
        //Set return value of post method
        $this->bridge->method('post')->withAnyParameters()->willReturn(new Response(200, [], $this->jsonResponse));
        //Override Bridge in IOC
        $this->app->instance('PersonalityInsightsBridge', $this->bridge);

        //Get Intellect
        $intellect = app()->make('PersonalityInsights')->addContentItems($this->contentItems)->getInsight('Intellect');
        //We see the expected percentage
        $this->assertEquals(41.7, $intellect->calculatePercentage());
    }

    /**
     * Test to confirm that analysis is strong
     *
     * @return void
     */
    public function testConfirmThatAnalysisIsVeryStrong()
    {
        //Set return value of post method
        $this->bridge->method('post')->withAnyParameters()->willReturn(new Response(200, [], $this->jsonResponse));
        //Override Bridge in IOC
        $this->app->instance('PersonalityInsightsBridge', $this->bridge);

        //Analysis is strong
        $this->assertTrue(app()->make('PersonalityInsights')->addContentItems($this->contentItems)->isAnalysisStrong());
    }

    /**
     * Test that When we do not pass content item param we get an exception
     *
     * @expectedException \FindBrok\PersonalityInsights\Exceptions\MissingParameterContentItemException
     * @return void
     */
    public function testNoContentPassedToContentItemObjectMissingParameterExceptionThrown()
    {
        //Set return value of post method
        $this->bridge->method('post')->withAnyParameters()->willReturn(new Response(200, [], $this->jsonResponse));
        //Override Bridge in IOC
        $this->app->instance('PersonalityInsightsBridge', $this->bridge);

        //Add content and exception is thrown here
        app()->make('PersonalityInsights')->addContentItems([
            'id' => 'foo',
            'source' => 'bar'
        ]);
    }
}
