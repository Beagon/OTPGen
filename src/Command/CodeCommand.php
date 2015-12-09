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
                'The name or the id of the library entry.'
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

        if (!key_exists($keyArgument, $config) && !is_numeric($keyArgument)) {
            $output->writeln('The entry ' . $keyArgument . ' does not exist in your library. Add it by using the add command.');
            return 0;
        }

        if (is_numeric($keyArgument)) {
            if (intval($keyArgument) > (count($config) - 1)) {
                $output->writeln('There is no entry in your library with the id: ' . $keyArgument . '.');
                return 0;
            }
            $count = 0;
            foreach ($config as $key => $WhyBother) {
                if ($count == intval($keyArgument)) {
                    $keyArgument = $key;
                    continue;
                }
                $count++;
            }
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
