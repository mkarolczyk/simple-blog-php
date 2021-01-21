<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Blog\UI\Form\BlogPostForm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

final class BlogPostFormTest extends TypeTestCase
{
    public function testShouldCreateFormAndStripTagsFromTitle(): void
    {
        $requestData = [
            'title' => ' <b>This is my blog post.</b>',
            'content' => '<strong></strong><p></p><ul></ul><ol></ol><li></li>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
            'imagePath' => $this->jpgFilePath(),
        ];

        $form = $this->factory->create(BlogPostForm::class);
        $form->submit($requestData);

        $formData = $form->getData();

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid(), (string) $form->getErrors(true, false));
        self::assertEquals('This is my blog post.', $formData['title']);
        self::assertEquals($requestData['content'], $formData['content']);
    }

    public function testShouldCreateFormWithTooShortTitleAndEmptyContentGenerateValidateError(): void
    {
        $requestData = [
            'title' => 'Too short',
            'imagePath' => $this->jpgFilePath(),
        ];

        $form = $this->factory->create(BlogPostForm::class, $requestData);
        $form->submit($requestData);

        self::assertTrue($form->isSubmitted());
        self::assertFalse($form->isValid());
        self::assertCount(2, $form->getErrors(true, false));
    }

    public function testShouldCreateFormWithTooShortContentAndEmptyTitleGenerateValidateError(): void
    {
        $requestData = [
            'content' => 'Too short',
            'imagePath' => $this->jpgFilePath(),
        ];
        $form = $this->factory->create(BlogPostForm::class, $requestData);
        $form->submit($requestData);

        self::assertTrue($form->isSubmitted());
        self::assertFalse($form->isValid());
        self::assertCount(2, $form->getErrors(true, false));
    }

    protected function jpgFilePath(): string
    {
        return __DIR__.'/../example.jpg';
    }

    protected function pngFilePath(): string
    {
        return __DIR__.'/../example.png';
    }

    protected function getExtensions(): array
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }
}
