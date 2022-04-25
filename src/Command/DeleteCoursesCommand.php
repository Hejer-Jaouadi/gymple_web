<?php

namespace App\Command;

use App\Repository\CoursesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCoursesCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:delete-courses';

    
    private $coursesRepository;

    public function __construct(CoursesRepository $coursesRepository)
    {
        parent::__construct();
        $this->coursesRepository = $coursesRepository;

       
    }
    protected function configure(): void
    {
        // ...
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ... put here the code to create the user

        // this method must return an integer number with the "exit status code"
        // of the command.

        // return this if there was no problem running the command
        $this->coursesRepository->removeExpired();

        return 0;

        // or return this if some error happened during the execution
        // return 1;
    }
}