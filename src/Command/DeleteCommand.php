<?php
/**
 * File: src/Command/DeleteCommand.php
 * Author: Robin Rijkeboer <rmrijkeboer@gmail.com>
 */

namespace beagon\OTPGen\Command;

use beagon\OTPGen\BaseCommand;
use beagon\OTPGen\OTPGen;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteCommand
 *
 *
 * @package beagon\OTPGen\Command
 */
class DeleteCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('delete')
            ->setDescription('Delete an entry from your library by Name or Id.')
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
            $output->writeln('The entry ' . $keyArgument . ' does not exist in your library.');
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

        unset($config[$key]);

        $otpFile = fopen(OTPGEN_WORKING_DIR . '.otp', 'w') or die('Wasn\'t able to open library are you sure I have enough permissions?');
        fwrite($otpFile, OTPGen::toYAML($config));
        fclose($otpFile);

        $output->writeln('Removed ' . $keyArgument . ' from your library.');

        return 0;
    }
}
