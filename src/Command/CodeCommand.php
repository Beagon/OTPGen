<?php
/**
 * File: src/Command/CodeCommand.php
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
 * Class CodeCommand
 *
 *
 * @package beagon\OTPGen\Command
 */
class CodeCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('code')
            ->setDescription('Gets an OTP code')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name the key has in the .otp file.'
            )
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

        $config = OTPGen::LoadConfig();
        $keyArgument = $input->getArgument('name');
        if (!key_exists($keyArgument, $config)) {
            $output->writeln('The key ' . $keyArgument . ' does not exist in your configs. Add it by using the addkey command.');
            return 0;
        }

        $totp = new TOTP();
        $totp->setDigits($config[$keyArgument]['size'])
             ->setDigest($config[$keyArgument]['digest'])
             ->setInterval($config[$keyArgument]['interval'])
             ->setSecret($config[$keyArgument]['token']);

        $output->writeln('Your token is: <options=bold>' . $totp->now() . '</>');

        return 0;
    }
}
