<?php
declare(strict_types=1);

/**
 * PostLoader class is meant to handle the retrieval and loading of posts from a file of posts.
 */
include_once('Post.php');

class PostLoader
{
    private string $postStorageFile;
    private array $posts;

    public function __construct(string $destination)
    {
        $this->postStorageFile = $destination;
        $this->getAllPosts();
    }

    public function addNewPost(Post $newPost): void
    {
        $this->posts[] = $newPost;
        $this->storePosts();
    }

    private function getAllPosts(): ?array
    {
        $data = file_get_contents($this->postStorageFile);
        if (!$data)
        {
            $this->posts = [];
        }
        else
        {
            $data = unserialize($data, ['post']);
            $this->posts = $data;
        }
        return $this->posts;
    }

    public function getPost(int $index): ?Post
    {
        if (count($this->posts) > $index)
        {
            return $this->posts[$index];
        }
        return null;
    }

    public function getPostCount() : int
    {
        return count($this->posts);
    }

    public function storePosts(): void
    {
        $data = serialize($this->posts);
        file_put_contents($this->postStorageFile, $data);
    }

//    public function clearPosts() : void
//    {
//
//    }

}