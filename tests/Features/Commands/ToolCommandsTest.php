<?php

declare(strict_types=1);

namespace EcEuropa\Toolkit\Tests\Features\Commands;

use EcEuropa\Toolkit\TaskRunner\Commands\ToolCommands;
use EcEuropa\Toolkit\Tests\AbstractTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Test Toolkit tool commands.
 *
 * @group tool
 */
class ToolCommandsTest extends AbstractTest
{

    /**
     * Data provider for testTool.
     *
     * @return array
     *   An array of test data arrays with assertions.
     */
    public static function dataProvider()
    {
        return self::getFixtureContent('commands/tool.yml');
    }

    /**
     * Test ToolCommands commands.
     *
     * @param string $command
     *   A command.
     * @param array $config
     *   A configuration.
     * @param array $resources
     *   Resources needed for the test.
     * @param array $expectations
     *   Tests expected.
     *
     * @dataProvider dataProvider
     */
    public function testTool(string $command, array $config = [], array $resources = [], array $expectations = [])
    {
        // Setup configuration file.
        if (!empty($config)) {
            $this->fs->dumpFile($this->getSandboxFilepath('runner.yml'), Yaml::dump($config));
        }
        $this->prepareResources($resources);

        // Run command.
        $result = $this->runCommand($command);

        $this->debugExpectations($result['output'], $expectations);
        // Assert expectations.
        foreach ($expectations as $expectation) {
            $this->assertDynamic($result['output'], $expectation);
        }
    }

    /**
     * Data provider for command toolkit:opts-review.
     *
     * @return array
     *   An array of test data arrays with assertions.
     */
    public static function dataProviderOptsReview()
    {
        return self::getFixtureContent('commands/opts-review.yml');
    }

    /**
     * Test command toolkit:opts-review.
     *
     * @param string $command
     *   A command.
     * @param array $config
     *   A configuration.
     * @param array $resources
     *   Resources needed for the test.
     * @param array $expectations
     *   Tests expected.
     *
     * @dataProvider dataProviderOptsReview
     */
    public function testOptsReview(string $command, array $config = [], array $resources = [], array $expectations = [])
    {
        // Setup configuration file.
        if (!empty($config)) {
            $this->fs->dumpFile($this->getSandboxFilepath('runner.yml'), Yaml::dump($config));
        }
        $this->prepareResources($resources);

        // Run command.
        $result = $this->runCommand($command);
        $this->debugExpectations($result['output'], $expectations);
        // Assert expectations.
        foreach ($expectations as $expectation) {
            $this->assertDynamic($result['output'], $expectation);
        }
    }

    public function testConfigurationFileExists()
    {
        $this->assertFileExists((new ToolCommands())->getConfigurationFile());
    }

}
