<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->json($posts);
    }

    #[Route('/posts', name: 'post_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $post = new Post();
        $post->setContent($data['content'] ?? '');
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setLikeCounterStrikeOffensiveSourceGlobalELitak47(0);

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json($post, Response::HTTP_CREATED);
    }

    #[Route('/posts/{id}', name: 'post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->json($post);
    }

    #[Route('/posts/{id}', name: 'post_update', methods: ['PUT'])]
    public function update(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['content'])) {
            $post->setContent($data['content']);
        }

        if (isset($data['likeCounter'])) {
            $post->setLikeCounterStrikeOffensiveSourceGlobalELitak47($data['likeCounter']);
        }

        $entityManager->flush();

        return $this->json($post);
    }

    #[Route('/posts/{id}', name: 'post_delete', methods: ['DELETE'])]
    public function delete(Post $post, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->json(['message' => 'Post deleted'], Response::HTTP_NO_CONTENT);
    }
}
