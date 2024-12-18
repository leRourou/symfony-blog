<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        // Rendre la vue Twig avec les posts
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
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

        // Sérialiser le post avec le groupe 'post:read'
        return $this->json($post, Response::HTTP_CREATED, [], [
            'groups' => ['post:read']
        ]);
    }

    #[Route('/posts/{id}', name: 'post_show', methods: ['GET'])]
    public function show(Post $post, SerializerInterface $serializer): Response
    {
        // Sérialiser le post avec le groupe 'post:read'
        $json = $serializer->serialize($post, 'json', [
            AbstractNormalizer::GROUPS => ['post:read'],
        ]);

        return new Response($json, 200, ['Content-Type' => 'application/json']);
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

        // Sérialiser le post avec le groupe 'post:read'
        return $this->json($post, Response::HTTP_OK, [], [
            'groups' => ['post:read']
        ]);
    }

    #[Route('/posts/{id}', name: 'post_delete', methods: ['DELETE'])]
    public function delete(Post $post, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->json(['message' => 'Post deleted'], Response::HTTP_NO_CONTENT);
    }
}
