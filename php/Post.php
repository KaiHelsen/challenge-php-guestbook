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
        $this->content = $this->findSmileys($this->content);

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
        $myString = $input;
        $myString = $this->validateString($myString);
        $myString = $this->cleanseString($myString, $badWords);

        return $this->cleanseString($this->validateString($input), $badWords);
    }

    private function validateString(string $input): string
    {
        return htmlSpecialchars($input, ENT_NOQUOTES, 'UTF-8');
    }

    private function cleanseString(string $input, array $badWords):string
    {
        //just looking for naughty words in and of themselves will lead to the scunthorpe problem
        //the best and easiest solution I can think of is to at LEAST filter for the bad word, but only replace if there is NOT a space in front of it
        //sure, that could still lead to problems but at least Scunthorpe is safe.
        //penistone is still in trouble but I'd have to write exceptions.

//        array_walk($badWords, static function(&$word){$word = " " . $word;});
        return str_replace($badWords, '****', $input);

        //okay so that's not great

    }

    private function findSmileys(string $input){
        $smileys = [
            ':)' => '🙂',
            ':-)' => '🙂',
            ';-)' => '😉',
            ';)' => '😉',
            ':-o' => '😮',
            ':o' => '😮',
        ];
        return str_ireplace(array_keys($smileys), $smileys, $input);
    }


}