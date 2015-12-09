<?php
/**
 * File: src/Command/ListCommand.php
 * Author: Robin Rijkeboer <rmrijkeboer@gmail.com>
 */

namespace beagon\OTPGen\Command;

use beagon\OTPGen\BaseCommand;
use beagon\OTPGen\OTPGen;
use OTPHP\TOTP;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 *
 * @package beagon\OTPGen\Command
 */
class LibraryCommand extends BaseCommand
{
    public $loadConfig = false;
    protected function configure()
    {
        $this
            ->setName('library')
            ->setDescription('Lists your OTP Library')
        ;
    }

    /**
     * Runs the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists(OTPGEN_WORKING_DIR . '.otp')) {
            $ouput->writeln('No library found, please initialize it using the add command.');
            return 0;
        }

        $config = OTPGen::LoadConfig();
        $count = 0;
        foreach ($config as $key => $whyBother) {
            $output->writeln('('. $count .') ' . $key);
            $count++;
        }

        return 0;
    }
}
