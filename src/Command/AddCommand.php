<?php
/**
 * File: src/Command/AddCommand.php
 * Author: Robin Rijkeboer <rmrijkeboer@gmail.com>
 */

namespace beagon\OTPGen\Command;

use beagon\OTPGen\BaseCommand;
use beagon\OTPGen\OTPGen;
use OTPHP\TOTP;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class AddCommand
 *
 *
 * @package beagon\OTPGen\Command
 */
class AddCommand extends BaseCommand
{
    public $loadConfig = false;

    protected function configure()
    {
        $this
            ->setName('add')
            ->setDescription('Add an OTP code.')
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
        $helper = $this->getHelper('question');

        //Ask te user for a name which will be used as key
        $question = new Question('Enter a name: ');
        $question->setValidator(function ($answer) {
            if (empty($answer)) {
                throw new \RuntimeException(
                    'Please enter a name.'
                );
            }

            if (strrpos($answer, " ") === true) {
                throw new \RuntimeException(
                    'No spaces allowed in your name.'
                );
            }
            return $answer;
        });
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Enter your token: ');
        $question->setValidator(function ($answer) {
            if (empty($answer)) {
                throw new \RuntimeException(
                    'Please enter your token.'
                );
            }
        });
        $token = $helper->ask($input, $output, $question);

        $question = new Question('Enter the digest algorithm (default sha1): ', 'sha1');
        $digest = $helper->ask($input, $output, $question);

        $question = new Question('Enter the lenght of the generated token (default 6): ', 6);
        $size = $helper->ask($input, $output, $question);

        $question = new Question('Enter the interval of the token (default 30): ', 30);
        $interval = $helper->ask($input, $output, $question);

        $data = array(
            $name => array(
                'token' => $name,
                'digest' => $digest,
                'size' => $size,
                'interval' => $interval
                )
            );

        if (file_exists(OTPGEN_WORKING_DIR . '.otp')) {
            $config = OTPGen::loadConfig();
            $data = array_merge($config, $data);
        }

        $otpFile = fopen(OTPGEN_WORKING_DIR . '.otp', 'w') or die('Wasn\'t able to open or create .otp are you sure I have enough permissions?');
        fwrite($otpFile, OTPGen::toYAML($data));
        fclose($otpFile);

        $output->writeln('Added ' . $name . ' to .otp.');
        return 0;
    }
}
