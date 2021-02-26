<?php
declare(strict_types=1);
include_once($_SERVER['DOCUMENT_ROOT'] . "/php/Post.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/php/PostLoader.php");

//declare CONST values for easy communication
const TITLE = "title";
const CONTENT = "content";
const NAME = "name";
const POST_FILE_LOCATION = "/posts/guestbook.txt";
const NAUGHTY_WORDS = ("peepee poopoo taxes ni cunt");
$naughtyWords = explode(" ", NAUGHTY_WORDS);
$newPost = $_POST;
$titleErrMsg = "";
$contentErrMsg = "";
$nameErrMsg = "";

$handler = new PostLoader($_SERVER['DOCUMENT_ROOT'] . POST_FILE_LOCATION);

session_start();

//var_dump($_POST);

//VALIDATE AND ADD NEW POST
//if the $_POST is valid and not empty, add the post to the list of posts.

if (!empty($newPost))
{
    //store post data in session
    $_SESSION[TITLE] = $newPost[TITLE];
    $_SESSION[CONTENT] = $newPost[CONTENT];
    $_SESSION[NAME] = $newPost[NAME];

    $postIsValid = true;
    if (empty($newPost[TITLE]))
    {
        //title not valid
        $titleErrMsg = ("Title is required!");
        $postIsValid = false;
    }

    if (empty($newPost[CONTENT]))
    {
        //post content not valid
        $contentErrMsg = ("Post content is required!");
        $postIsValid = false;
    }

    if (empty($newPost[NAME]))
    {
        //post name not valid
        $nameErrMsg = ("a name is required!");
        $postIsValid = false;
    }

    if ($postIsValid)
    {
        $newPost = new Post($_POST[TITLE], 'now!', $newPost[CONTENT], $newPost[NAME], $naughtyWords);
        $handler->addNewPost($newPost);

        //if the post is successful, we can unset the session and post data
        //then, we re-call this file and run through everything again
        unset($_POST);
        session_unset();
        header('Location: index.php');
        exit;
    }
}

//NEXT
//PARSE THROUGH EXISTING POSTS AND PUT THEM IN HTML
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo "./templates/css/main.css"; ?>">
    <title>My site</title>
</head>
<body>
<h1>Welcome to my site!</h1>
<h2>Recent articles</h2>
<ul>
    <?php for ($i = min($handler->getPostCount(),20) -1; $i >= 0; $i--):
        $currentPost = $handler->getPost($i); ?>
        <li>
            <h3><?php echo $currentPost->getTitle();?></h3>
            <p>by: <?php echo $currentPost->getAuthorName();?> posted on: <?php echo $currentPost->getDate();?></p>
            <div>
                <?php echo $currentPost->getContent();?>
            </div>
        </li>
    <?php endfor; ?>
</ul>

<!--post form -->
<form method="post">
    <label for="title">Title</label><br>
    <input type="text" name="<?php echo TITLE; ?>" id="title" placeholder="new Title"
           value="<?php echo $_SESSION[TITLE] ?? ""; ?>">
    <span class="error"><?php echo $titleErrMsg; ?> </span><br>
    <label for="content">Content</label><br>
    <textarea name="<?php echo CONTENT; ?>" id="content" rows="4" cols="50"
              placeholder="what you have to say here"><?php echo $_SESSION[CONTENT] ?? ""; ?></textarea>
    <span class="error"><?php echo $contentErrMsg; ?> </span>
    <br>
    <label for="name">username</label><br>
    <input type="text" name="<?php echo NAME; ?>" id="name" placeholder="new Title"
           value="<?php echo $_SESSION[NAME] ?? ""; ?>">
    <span class="error"><?php echo $nameErrMsg; ?> </span><br>
    <button type="submit" name="submit" value="true">send</button>
</form>
</body>
</html>

