<?php

declare(strict_types=1);

namespace App\Blog\UI\Rest;

use App\Blog\Application\Command\AddBlogPostCommand;
use App\Blog\Application\Query\BlogPostQueryRepositoryInterface;
use App\Blog\Application\Service\BlogFacade;
use App\Blog\Application\Service\ImageFilenameGeneratorService;
use App\Blog\Application\Service\ParameterBagInterface;
use App\Blog\UI\Form\BlogPostForm;
use App\Shared\Common\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/posts", name="post_")
 */
class BlogPostController extends AbstractController
{
    private const MESSAGE_STATUS_OK = 'Adding a blog post is a work in progress.';

    private BlogFacade $blogFacade;

    public function __construct(BlogFacade $blogFacade)
    {
        $this->blogFacade = $blogFacade;
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function addPost(Request $request, ParameterBagInterface $parameterBag): JsonResponse
    {
        $data = $request->request->all();
        $data['image'] = $request->files->get('image');

        $form = $this->createForm(BlogPostForm::class);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return new JsonResponse(['errorMessage' => (string) $form->getErrors(true, false)], JsonResponse::HTTP_BAD_REQUEST);
        }

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

        return new JsonResponse(['message' => ''.self::MESSAGE_STATUS_OK.'', 'id' => $blogPostId], JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/{blogPostId}", name="get", methods={"GET"})
     */
    public function getPost(string $blogPostId): JsonResponse
    {
        return new JsonResponse($this->blogFacade->findBlogPostById($blogPostId), 200);
    }

    /**
     * @Route("", name="get_all", methods={"GET"})
     */
    public function getAllPost(Request $request, BlogPostQueryRepositoryInterface $blogPostQueryRepository): JsonResponse
    {
        $page = (int) $request->query->get('page', '1');
        $maxItems = (int) $request->query->get('maxItems', '10');

        $data = [
            'totalPosts' => $blogPostQueryRepository->count([]),
            'data' => $this->blogFacade->findAllBlogPost($page, $maxItems),
        ];

        return new JsonResponse($data, 200);
    }
}
