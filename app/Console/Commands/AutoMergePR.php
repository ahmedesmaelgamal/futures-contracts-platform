<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AutoMergePR extends Command
{
    protected $signature = 'git:merge';
    protected $description = 'Automatically merge the current branch into main';

    public function handle()
    {
        // Get the current branch name
        $branch = trim($this->executeCommand(["git", "rev-parse", "--abbrev-ref", "HEAD"]));

        if ($branch === 'main') {
            $this->error("You are already on the 'main' branch. Nothing to merge.");
            return;
        }

        $this->info("Merging current branch '$branch' into 'main'...");

        try {
            $this->executeCommand(["git", "checkout", "main"]);
            $this->executeCommand(["git", "pull", "origin", "main"]);
            $this->executeCommand(["git", "merge", $branch]);
            $this->executeCommand(["git", "push", "origin", "main"]);

            $this->info("Successfully merged '$branch' into 'main' and pushed changes.");

            // Switch back to the original branch
            $this->executeCommand(["git", "checkout", $branch]);
            $this->info("Switched back to branch '$branch'.");
        } catch (ProcessFailedException $e) {
            $this->error("Merge failed: " . $e->getMessage());
        }
    }

    protected function executeCommand(array $command)
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return trim($process->getOutput());
    }
}
