<?php

declare(strict_types=1);

namespace App\Blog\UI\Cli;

use App\Blog\Application\Command\AddBlogPostCommand;
use App\Blog\Application\Service\BlogFacade;
use App\Blog\Application\Service\ImageFileOperationService;
use App\Blog\UI\Form\BlogPostForm;
use App\Blog\UI\Form\FormFactory;
use App\Shared\Common\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AddBlogPostConsole extends Command
{
    protected static $defaultName = 'app:add-blog-post';

    private BlogFacade $blogFacade;
    private ImageFileOperationService $imageFileOperationService;

    public function __construct(string $name = null, BlogFacade $blogFacade,
                                ImageFileOperationService $imageFileOperationService)
    {
        $this->blogFacade = $blogFacade;
        $this->imageFileOperationService = $imageFileOperationService;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add blog post.')
            ->addArgument('title', InputArgument::REQUIRED, 'Title')
            ->addArgument('content', InputArgument::REQUIRED, 'Content')
            ->addArgument('imagePath', InputArgument::REQUIRED, 'Path to image');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $blogPostId = Uuid::generate()->valueString();
        $data = $input->getArguments();
        unset($data['command']);

        $form = FormFactory::getForm()->create(BlogPostForm::class);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $output->writeln('<error>'.$form->getErrors(true, false).'</error>');

            return Command::FAILURE;
        }

        $sourceImagePath = $form->get('imagePath')->getData();
        $targetPath = $this->imageFileOperationService->copyToTempDir($sourceImagePath, $blogPostId);

        $this->blogFacade->addBlogPost(
            new AddBlogPostCommand(
                $blogPostId,
                $form->get('title')->getData(),
                $form->get('content')->getData(),
                $targetPath
            )
        );

        $output->writeln([
            '',
            '<comment>Adding a blog post is a work in progress...</comment>',
            '============',
        ]);

        $output->writeln('Post ID: '.$blogPostId);
        $output->writeln('Title: '.$form->get('title')->getData());
        $output->writeln('Content: '.$form->get('content')->getData());
        $output->writeln('Original path to image: '.$form->get('imagePath')->getData());

        return Command::SUCCESS;
    }
}
