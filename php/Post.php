<?php
declare(strict_types=1);

class Post
{
    private string $title;
    private string $date;
    private string $content;
    private string $authorName;
    private array $badWords;

    public function __construct(string $title, string $date, string $content, string $authorName, array $badWords)
    {
        $this->title = $this->parseString($title, $badWords);
        $this->date = $date;
        $this->content = $this->parseString($content, $badWords);
        $this->authorName = $this->parseString($authorName, $badWords);
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    private function parseString(string $input, array $badWords) : string
    {
        return $this->cleanseString($this->validateString($input), $badWords);
    }

    private function validateString(string $input): string
    {
        return htmlSpecialchars($input, ENT_NOQUOTES, 'UTF-8');
    }

    private function cleanseString(string $input, array $badWords):string
    {
        return str_replace($badWords, '****', $input);
    }


}