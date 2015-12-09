<?php
/**
 * File: src/OTPGen.php
 * Author: Robin Rijkeboer <rmrijkeboer@gmail.com>
 */

namespace beagon\OTPGen;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;

/**
 * Class OTPGen
 */
class OTPGen extends Application
{
    /**
     * Universal directory separator for *Nix and Windows
     *
     * @var string
     */
    public static $directorySeparator = '/';

    /**
     * Takes in an array of data, and turns it into blissful YAML using Symfony's YAML component.
     *
     * @param array $data
     * @return string
     */
    public static function toYAML(array $data = array())
    {
        return Yaml::dump($data, 4);
    }

    /**
     * Takes YAML, and turns it into an array using Symfony's YAML component.
     *
     * @param string $input
     * @return array
     */
    public static function fromYAML($input = '')
    {
        return Yaml::parse($input);
    }

    /**
     * @throws \RuntimeException
     */
    public static function loadConfig()
    {
        if (!file_exists(OTPGEN_WORKING_DIR . '.otp')) {
            throw new \RuntimeException('Directory is not a OTPGen directory: ' . OTPGEN_WORKING_DIR . ' or add an OTP token.');
        }
        $config = OTPGen::fromYAML(file_get_contents(OTPGEN_WORKING_DIR . '.otp'));
        if (!$config || !is_array($config)) {
            throw new \RuntimeException('Error: ' . OTPGEN_WORKING_DIR . '.otp file is not valid YAML, or is empty.');
        }

        return $config;
    }

    /**
     * Gets the default input definition.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message.'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display the OTPGen version.'),
        ));
    }
}
