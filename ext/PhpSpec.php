<?php

namespace WouterJ\Fred\Extension;

use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;
use WouterJ\Fred\File;
use WouterJ\Fred\Iterator\MapIterator;
use WouterJ\Fred\Step;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
class PhpSpec implements Step
{
    public function constructIterator(\Traversable $traversable)
    {
        $phpExecutable = new PhpExecutableFinder();
        $phpExecutable = $phpExecutable->find();

        return new MapIterator($traversable, function (File $file) use ($phpExecutable) {
            $processBuilder = new ProcessBuilder();
            $process = $processBuilder//->add($phpExecutable)
                ->add(getcwd().'/vendor/bin/phpspec')
                ->add('run')
                ->add($file->info()->getRealPath())
                ->getProcess();

            $process->mustRun();

            $file->setContent($process->getOutput());

            return $file;
        });
    }
}
