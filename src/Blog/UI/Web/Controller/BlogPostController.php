<?php

declare(strict_types=1);

namespace App\Blog\UI\Web\Controller;

use App\Blog\Application\Command\AddBlogPostCommand;
use App\Blog\Application\Service\BlogFacade;
use App\Blog\Application\Service\ImageFilenameGeneratorService;
use App\Blog\Application\Service\ParameterBagInterface;
use App\Blog\UI\Form\BlogPostForm;
use App\Shared\Common\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogPostController extends AbstractController
{
    private BlogFacade $blogFacade;

    public function __construct(BlogFacade $blogFacade)
    {
        $this->blogFacade = $blogFacade;
    }

    /**
     * @Route("/", name="add_post")
     */
    public function addPost(Request $request, ParameterBagInterface $parameterBag): Response
    {
        $form = $this->createForm(BlogPostForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blogPostId = Uuid::generate()->valueString();

            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            $imageTempName = ImageFilenameGeneratorService::generate($blogPostId);
            $image->move($parameterBag->getImageTempDir(), $imageTempName);

            $this->blogFacade->addBlogPost(
                new AddBlogPostCommand(
                    $blogPostId,
                    $form->get('title')->getData(),
                    $form->get('content')->getData(),
                    $parameterBag->getImageTempDir().'/'.$imageTempName
                )
            );

            return $this->redirectToRoute('web_blog_success_added_post', ['blogPostId' => $blogPostId]);
        }

        return $this->render('@blog/add-blog-post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success", name="success_added_post")
     */
    public function successAddedPost(Request $request): Response
    {
        return $this->render('@blog/success-added-blog-post.html.twig',
            ['blogPostId' => $request->get('blogPostId')]
        );
    }
}
